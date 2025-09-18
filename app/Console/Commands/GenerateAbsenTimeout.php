<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateAbsenTimeout extends Command
{
    protected $signature = 'generate:absen-timeout 
                            {--force : Paksa tutup tanpa menunggu toleransi}
                            {--tolerance=1 : Toleransi jam setelah jam keluar}';
    protected $description = 'Menutup otomatis absensi yang belum selesai jika shift sudah lewat toleransi jam setelah jam keluar';

    public function handle()
    {
        $zone         = 'Asia/Jakarta';
        $toleranceHrs = max(1, (int) $this->option('tolerance'));

        $this->info("⏳ Mengecek absensi tanpa time_out (toleransi: {$toleranceHrs} jam)...");
        Log::info("⏳ Mengecek absensi tanpa time_out...", ['tolerance_hours' => $toleranceHrs]);

        $updated = 0;

        // metrik & kegagalan
        $skippedToleransi = 0;
        $fails = [
            'missing_jadwal' => [],
            'missing_shift'  => [],
            'empty_time'     => [], // jam masuk/keluar kosong
            'parse_error'    => [],
            'save_error'     => [],
        ];

        // helper fail recorder
        $fail = function (string $reason, Absen $absen, array $extra = []) use (&$fails) {
            $fails[$reason][] = array_merge(['id' => $absen->id], $extra);
        };

        Absen::query()
            ->select([
                'id',
                'time_in',
                'time_out',
                'is_lembur',
                'present',
                'absent',
                'jadwal_id'
            ])
            ->whereNull('time_out')                         // belum checkout
            ->where(function ($q) { // sudah checkin
                $q->whereNotNull('time_in')
                    // kalau kolom time_in kamu epoch (INT):
                    ->where('time_in', '>', 0);
                // jika campuran (epoch atau datetime string), gunakan ini:
                // ->where(function ($qq) {
                //     $qq->where('time_in', '>', 0)
                //        ->orWhereRaw("time_in REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2} '");
                // });
            })
            ->where(function ($q) {
                $q->whereNull('is_lembur')->orWhere('is_lembur', 0);
            })
            ->where('present', 1)
            ->where(function ($q) {
                $q->whereNull('absent')->orWhere('absent', 0);
            })
            ->whereHas('jadwalAbsen.shift', function ($q) {
                $q->whereNotNull('jam_masuk')->whereRaw("TRIM(jam_masuk) <> ''")
                    ->whereNotNull('jam_keluar')->whereRaw("TRIM(jam_keluar) <> ''");
            })
            ->with([
                'jadwalAbsen:id,tanggal_jadwal,shift_id',
                'jadwalAbsen.shift:id,jam_masuk,jam_keluar'
            ])
            ->chunkById(200, function ($chunk) use (&$updated, $zone, $toleranceHrs, $fail, &$fails, &$skippedToleransi) {

                foreach ($chunk as $absen) {
                    $jadwal = $absen->jadwalAbsen;
                    if (!$jadwal) {
                        Log::warning("❌ Absen {$absen->id} tidak memiliki jadwal.");
                        $fail('missing_jadwal', $absen);
                        continue;
                    }

                    $shift = $jadwal->shift;
                    if (!$shift) {
                        Log::warning("❌ Absen {$absen->id} tidak memiliki shift.");
                        $fail('missing_shift', $absen, ['tanggal_jadwal' => $jadwal->tanggal_jadwal]);
                        continue;
                    }

                    // --- parse jam masuk/keluar ---
                    // $tanggal = Carbon::parse($jadwal->tanggal_jadwal, $zone)->startOfDay();
                    $jmRaw   = trim((string) $shift->jam_masuk);
                    $jkRaw   = trim((string) $shift->jam_keluar);

                    if ($jmRaw === '' || $jkRaw === '') {
                        // 🔎 Tambahkan log detail di sini
                        Log::warning("⛔ Jam shift kosong", [
                            'absen_id'        => $absen->id,
                            'jadwal_tanggal'  => $jadwal->tanggal_jadwal,
                            'shift_id'        => $shift->id ?? null,
                            'jam_masuk_raw'   => $jmRaw,
                            'jam_keluar_raw'  => $jkRaw,
                        ]);

                        // existing logic kamu
                        Log::warning("⛔ Jam masuk/keluar kosong untuk absen {$absen->id}");
                        $fail('empty_time', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                            'jam_masuk'      => $jmRaw,
                            'jam_keluar'     => $jkRaw,
                        ]);
                        continue;
                    }

                    // Anchor ke tanggal_jadwal saja (bukan time_in), lalu koreksi jk jika nyebrang hari
                    $anchorDate = Carbon::parse($jadwal->tanggal_jadwal, $zone)->toDateString();

                    $norm = function (string $t) {
                        $t = trim(str_replace('.', ':', $t));
                        if (preg_match('/^(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?$/', $t, $m)) {
                            $H = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                            $i = str_pad($m[2], 2, '0', STR_PAD_LEFT);
                            $s = isset($m[3]) ? str_pad($m[3], 2, '0', STR_PAD_LEFT) : '00';
                            return "$H:$i:$s";
                        }
                        return $t;
                    };

                    $jmN = $norm($jmRaw);
                    $jkN = $norm($jkRaw);

                    // 24:00.* -> 00:00 hari berikutnya (tangani SEBELUM parse)
                    $jkIs24 = preg_match('/^24:00(:00)?$/', $jkN) === 1;
                    if ($jkIs24) {
                        $jkN = '00:00:00';
                    }

                    $jm = Carbon::parse($anchorDate . ' ' . $jmN, $zone);
                    $jk = Carbon::parse($anchorDate . ' ' . $jkN, $zone);

                    // Jika 24:00 atau jam_keluar ≤ jam_masuk → shift malam → tambah 1 hari
                    if ($jkIs24 || $jk->lte($jm)) {
                        $jk->addDay();
                    }

                    $isShiftMalam = $jk->isAfter($jm) && $jk->diffInDays($jm) >= 1;


                    // hitung selesai & toleransi
                    $shiftSelesai = $jk->copy();
                    $toleransi    = $shiftSelesai->copy()->addHours($toleranceHrs);
                    $now          = now($zone);

                    if ($now->lt($toleransi) && !$this->option('force')) {
                        $skippedToleransi++;
                        Log::info("⏸️ Absen {$absen->id} masih toleransi", [
                            'jm' => $jm->toDateTimeString() . ' WIB',
                            'jk' => $jk->toDateTimeString() . ' WIB',
                            'toleransi' => $toleransi->toDateTimeString() . ' WIB',
                            'now' => $now->toDateTimeString() . ' WIB',
                            'malam' => $isShiftMalam,
                        ]);
                        continue;
                    }

                    Log::info("✔️ Close Absen {$absen->id}", [
                        'shift_selesai' => $shiftSelesai->toDateTimeString() . ' WIB',
                        'toleransi'     => $toleransi->toDateTimeString() . ' WIB',
                        'now'           => $now->toDateTimeString() . ' WIB',
                        'malam'         => $isShiftMalam,
                    ]);

                    // simpan epoch
                    try {
                        $absen->time_out      = $shiftSelesai->timestamp;
                        $absen->keterangan    = $absen->keterangan
                            ? $absen->keterangan . ' , Timer otomatis ditutup oleh sistem (shift melebihi ' . $toleranceHrs . ' jam)'
                            : 'Timer otomatis ditutup oleh sistem (shift melebihi ' . $toleranceHrs . ' jam)';
                        $absen->deskripsi_out = 'Timer otomatis ditutup oleh sistem';
                        $absen->save();
                        $updated++;
                    } catch (\Throwable $e) {
                        Log::error("💥 Gagal menyimpan Absen {$absen->id}: {$e->getMessage()}");
                        $fail('save_error', $absen, ['error' => $e->getMessage()]);
                        continue;
                    }
                }
            });

        // --- ringkasan ---
        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        $counts = array_map(fn($arr) => count($arr), $fails);
        $totalGagal = array_sum($counts);

        $this->line("ℹ️  Di-skip karena toleransi: {$skippedToleransi}");
        Log::info("ℹ️ Skip toleransi", ['count' => $skippedToleransi]);

        if ($totalGagal > 0) {
            $this->warn("⚠️ Gagal diproses total: {$totalGagal}  " .
                "(missing_jadwal: {$counts['missing_jadwal']}, " .
                "missing_shift: {$counts['missing_shift']}, " .
                "empty_time: {$counts['empty_time']}, " .
                "parse_error: {$counts['parse_error']}, " .
                "save_error: {$counts['save_error']})");

            foreach ($fails as $reason => $items) {
                $ids = array_column($items, 'id');
                Log::warning("⚠️ Gagal ({$reason}) count=" . count($items), ['ids' => $ids]);
            }
            Log::warning("⚠️ Detail gagal proses absen", ['fails' => $fails]);
        }
    }

    /**
     * Parse jam masuk/keluar menjadi Carbon dengan zona waktu konsisten.
     * Mengembalikan [Carbon $jm, Carbon $jk, bool $isShiftMalam] (jk ditambah 1 hari jika malam).
     */
    // private function parseShiftTimes(string $jmStr, string $jkStr, string $zone): array
    // {
    //     [$dJm, $tJm] = explode(' ', $jmStr, 2);
    //     [$dJk, $tJk] = explode(' ', $jkStr, 2);

    //     $normalize = function (string $t): string {
    //         $t = trim(str_replace('.', ':', $t));
    //         if (preg_match('/^24:00(:00)?$/', $t)) return '24:00'; // flag khusus
    //         if (preg_match('/^(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?$/', $t, $m)) {
    //             $H = str_pad($m[1], 2, '0', STR_PAD_LEFT);
    //             $i = str_pad($m[2], 2, '0', STR_PAD_LEFT);
    //             $s = isset($m[3]) ? str_pad($m[3], 2, '0', STR_PAD_LEFT) : null;
    //             return $s ? "$H:$i:$s" : "$H:$i";
    //         }
    //         return $t;
    //     };

    //     $tJmN = $normalize($tJm);
    //     $tJkN = $normalize($tJk);

    //     $addDayOut = false;
    //     if ($tJkN === '24:00') {
    //         $tJkN = '00:00:00';
    //         $addDayOut = true;
    //     }

    //     $loose = function (string $date, string $time) use ($zone) {
    //         foreach (['Y-m-d H:i:s', 'Y-m-d H:i'] as $fmt) {
    //             try {
    //                 if (Carbon::hasFormat("$date $time", $fmt)) {
    //                     return Carbon::createFromFormat($fmt, "$date $time", $zone);
    //                 }
    //             } catch (\Throwable $e) {
    //             }
    //         }
    //         return Carbon::parse("$date $time", $zone); // fallback
    //     };

    //     $jm = $loose($dJm, $tJmN);
    //     $jk = $loose($dJk, $tJkN);

    //     $isShiftMalam = $jk->lt($jm) || $addDayOut;
    //     if ($isShiftMalam) $jk->addDay();

    //     return [$jm, $jk, $isShiftMalam];
    // }
}
