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
        $toleranceHrs = (int) $this->option('tolerance') ?: 1;

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
                'jadwal_absen_id'
            ])
            ->whereNull('time_out')                         // belum checkout
            ->where(function ($q) {                         // sudah checkin (support int/datetime/string)
                $q->whereNotNull('time_in')
                    ->where(function ($qq) {
                        $qq->where('time_in', '!=', 0)
                            ->orWhereRaw("CAST(time_in AS CHAR) <> ''");
                    });
            })
            ->where(function ($q) {
                $q->whereNull('is_lembur')->orWhere('is_lembur', 0);
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
                    $tanggal = Carbon::parse($jadwal->tanggal_jadwal, $zone)->startOfDay();
                    $jmRaw   = trim((string) $shift->jam_masuk);
                    $jkRaw   = trim((string) $shift->jam_keluar);

                    if ($jmRaw === '' || $jkRaw === '') {
                        Log::warning("⛔ Jam masuk/keluar kosong untuk absen {$absen->id}");
                        $fail('empty_time', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                            'jam_masuk'      => $jmRaw,
                            'jam_keluar'     => $jkRaw,
                        ]);
                        continue;
                    }

                    $jmStr = $tanggal->toDateString() . ' ' . $jmRaw;
                    $jkStr = $tanggal->toDateString() . ' ' . $jkRaw;

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

                    // hitung selesai & toleransi
                    $shiftSelesai = $jk->copy();
                    $toleransi    = $shiftSelesai->copy()->addHours($toleranceHrs);
                    $now          = now($zone);

                    // hormati toleransi (shift malam & siang)
                    $shouldSkip =
                        $isShiftMalam ? $now->lt($toleransi) : $now->lte($toleransi);

                    if ($shouldSkip && !$this->option('force')) {
                        $skippedToleransi++;
                        Log::info(($isShiftMalam ? "⏸️ Shift malam" : "⏸️")
                            . " Absen {$absen->id} masih toleransi s/d {$toleransi->toDateTimeString()} WIB");
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
