<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\JadwalAbsensi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateAbsenTimeout extends Command
{
    protected $signature = 'generate:absen-timeout 
                            {--force : Paksa tutup tanpa menunggu toleransi}
                            {--tolerance=1 : Toleransi jam setelah jam keluar}';
    protected $description = 'Menutup otomatis absensi normal dan lembur yang belum selesai jika shift sudah lewat toleransi jam setelah jam keluar';

    public function handle()
    {
        $zone         = 'Asia/Jakarta';
        $toleranceHrs = (int) $this->option('tolerance') ?: 1;

        $this->info("⏳ Mengecek absensi tanpa time_out (toleransi: {$toleranceHrs} jam)...");
        Log::info("⏳ Mengecek absensi tanpa time_out...", ['tolerance_hours' => $toleranceHrs]);

        $updated = 0;

        // metrik & kegagalan
        $skippedToleransi = 0;
        $fails = [
            'missing_jadwal' => [],
            'missing_shift'  => [],
            'holiday'        => [],
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
            ->where(function ($q) {                         // sudah checkin (support int/datetime/string)
                $q->whereNotNull('time_in')
                    ->where(function ($qq) {
                        $qq->where('time_in', '!=', 0)
                            ->orWhereRaw("CAST(time_in AS CHAR) <> ''");
                    });
            })
            ->where('present', 1)
            ->where(function ($q) {
                $q->whereNull('absent')->orWhere('absent', 0);
            })
            ->with(['jadwalAbsen:id,tanggal_jadwal,shift_id', 'jadwalAbsen.shift:id,jam_masuk,jam_keluar'])
            ->chunkById(200, function ($chunk) use (&$updated, $zone, $toleranceHrs, $fail, &$fails, &$skippedToleransi) {

                foreach ($chunk as $absen) {
                    $jadwal = $absen->jadwalAbsen;
                    if (!$jadwal) {
                        Log::warning("❌ Absen {$absen->id} tidak memiliki jadwal.");
                        $fail('missing_jadwal', $absen, []);
                        continue;
                    }

                    $shift = $jadwal->shift;
                    if (!$shift) {
                        Log::warning("❌ Absen {$absen->id} tidak memiliki shift.");
                        $fail('missing_shift', $absen, ['tanggal_jadwal' => $jadwal->tanggal_jadwal]);
                        continue;
                    }

                    // --- parse jam masuk/keluar ---
                    $actualCheckIn = Carbon::createFromTimestamp($absen->time_in, $zone);
                    $shiftDate = Carbon::parse($jadwal->tanggal_jadwal, $zone)->startOfDay();
                    if (!$shiftDate->isValid()) {
                        $shiftDate = $actualCheckIn->copy()->startOfDay();
                    }

                    $jmRaw   = trim((string) $shift->jam_masuk);
                    $jkRaw   = trim((string) $shift->jam_keluar);

                    if ($jmRaw === '' || $jkRaw === '') {
                        Log::info("📌 Absen {$absen->id} dilewati karena shift libur pada {$jadwal->tanggal_jadwal}");
                        $fail('holiday', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                        ]);
                        continue;
                    }

                    $jmStr = $shiftDate->toDateString() . ' ' . $jmRaw;
                    $jkStr = $shiftDate->toDateString() . ' ' . $jkRaw;

                    try {
                        [$jm, $jk, $isShiftMalam] = $this->parseShiftTimes($jmStr, $jkStr, $zone);
                    } catch (\Throwable $e) {
                        Log::warning("⛔ Gagal parse jam absen {$absen->id}: {$e->getMessage()}", ['jm_str' => $jmStr, 'jk_str' => $jkStr]);
                        $fail('parse_error', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                            'jam_masuk_raw'  => $jmStr,
                            'jam_keluar_raw' => $jkStr,
                            'error'          => $e->getMessage(),
                        ]);
                        continue;
                    }

                    // Hitung batas auto-close.
                    // - absensi biasa: 1 jam setelah jam keluar shift (atau sesuai opsi --tolerance)
                    // - absensi lembur: 10 menit sebelum jam masuk shift yang relevan
                    //   (hari yang sama jika lembur dimulai sebelum shift, atau hari berikutnya jika lembur dimulai setelah shift)
                    $shiftMulai   = $jm->copy();
                    $shiftSelesai = $jk->copy();
                    if ($absen->is_lembur) {
                        if ($actualCheckIn->lt($shiftMulai)) {
                            // Lembur sebelum shift (hari yang sama)
                            $toleransi = $shiftMulai->copy()->subMinutes(10);
                        } else {
                            // Lembur setelah shift -> cari jadwal besok
                            $besok = Carbon::parse($jadwal->tanggal_jadwal, $zone)->addDay()->toDateString();
                            $jadwalBesok = JadwalAbsensi::where('tanggal_jadwal', $besok)
                                ->latest()
                                ->first();

                            if ($jadwalBesok && $jadwalBesok->shift) {
                                // Gunakan jam masuk shift besok
                                $jmBesokRaw = trim((string) $jadwalBesok->shift->jam_masuk);
                                $shiftMulaiBesok = Carbon::createFromFormat('Y-m-d H:i:s', $besok . ' ' . $jmBesokRaw, $zone);
                                $toleransi = $shiftMulaiBesok->subMinutes(10);
                            } else {
                                // Fallback jika besok tidak ada jadwal (libur), gunakan jam masuk hari ini + 1 hari
                                $toleransi = $shiftMulai->copy()->addDay()->subMinutes(10);
                            }
                        }
                    } else {
                        $toleransi = $shiftSelesai->copy()->addHours($toleranceHrs);
                    }
                    $now          = now($zone);

                    // Hanya skip jika waktu sekarang BELUM melewati toleransi
                    if ($now->lt($toleransi) && !$this->option('force')) {
                        $skippedToleransi++;
                        Log::info("⏸️ Absen {$absen->id} masih dalam masa toleransi s/d {$toleransi->toDateTimeString()} WIB", [
                            'now' => $now->toDateTimeString(),
                            'shift_selesai' => $shiftSelesai->toDateTimeString(),
                            'malam' => $isShiftMalam,
                        ]);
                        continue;
                    }

                    Log::info("DEBUG TIME", [
                        'absen_id'    => $absen->id,
                        'shift_in'    => $jm->toDateTimeString(),
                        'shift_out'   => $jk->toDateTimeString(),
                        'final_saved' => Carbon::createFromTimestamp($shiftSelesai->timestamp, $zone)->toDateTimeString(),
                    ]);

                    // pakai finalTime hasil kalkulasi, bukan absen->time_out (masih null)
                    if ($absen->is_lembur) {
                        $finalTime = $toleransi; // untuk lembur, final time adalah waktu toleransi (10 menit sebelum shift berikutnya)
                    } else {
                        $finalTime = $shiftSelesai->copy()->setTimezone($zone);
                    }

                    Log::info("FINAL SAVE", [
                        'absen_id' => $absen->id,
                        'unix'     => $finalTime->timestamp,
                        'datetime' => $finalTime->toDateTimeString(),
                    ]);

                    Log::info("✔️ Close Absen {$absen->id}", [
                        'is_lembur'     => (bool) $absen->is_lembur,
                        'shift_selesai' => $shiftSelesai->toDateTimeString() . ' WIB',
                        'toleransi'     => $toleransi->toDateTimeString() . ' WIB',
                        'now'           => $now->toDateTimeString() . ' WIB',
                        'malam'         => $isShiftMalam,
                    ]);

                    // simpan epoch
                    try {
                        $finalTimeInZone = $finalTime->setTimezone($zone);
                        $absen->time_out      = $finalTimeInZone->timestamp;
                        $label = $absen->is_lembur ? 'absen lembur' : 'absen kerja';
                        $absen->keterangan    = trim(($absen->keterangan ? $absen->keterangan . ', ' : '')
                            . "Timer otomatis ditutup oleh sistem untuk {$label} (shift melebihi {$toleranceHrs} jam)");
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
        $counts = array_map(fn($arr) => count($arr), $fails);

        $totalGagal = $counts['missing_jadwal']
            + $counts['missing_shift']
            + $counts['empty_time']
            + $counts['parse_error']
            + $counts['save_error'];

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        $this->line("ℹ️  Di-skip karena toleransi: {$skippedToleransi}");
        $this->line("📌 Dilewati karena libur: {$counts['holiday']}");
        Log::info("📌 Skip karena libur", ['count' => $counts['holiday']]);
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
    private function parseShiftTimes(string $jmStr, string $jkStr, string $zone): array
    {
        $jm = Carbon::hasFormat($jmStr, 'Y-m-d H:i:s')
            ? Carbon::createFromFormat('Y-m-d H:i:s', $jmStr, $zone)
            : Carbon::createFromFormat('Y-m-d H:i',    $jmStr, $zone);

        $jk = Carbon::hasFormat($jkStr, 'Y-m-d H:i:s')
            ? Carbon::createFromFormat('Y-m-d H:i:s', $jkStr, $zone)
            : Carbon::createFromFormat('Y-m-d H:i',    $jkStr, $zone);


        $isShiftMalam = $jk->lt($jm);
        if ($isShiftMalam) {
            $jk->addDay();
        }
        return [$jm, $jk, $isShiftMalam];
    }
}
