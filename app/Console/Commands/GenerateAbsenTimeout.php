<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateAbsenTimeout extends Command
{
    protected $signature = 'generate:absen-timeout {--force}';
    protected $description = 'Menutup otomatis absensi yang belum selesai jika shift sudah lewat 1 jam';

    private const TOLERANSI_JAM_SETELAH_SHIFT = 1; // 1 jam toleransi

    public function handle()
    {
        $this->info("⏳ Mengecek absensi tanpa time_out...");
        Log::info("⏳ Mengecek absensi tanpa time_out...");

        $updated = 0;
        $zone    = 'Asia/Jakarta';

        // wadah kegagalan: alasan => list detail
        $fails = [
            'missing_jadwal' => [],
            'missing_shift'  => [],
            'parse_error'    => [],
            'invalid_data'   => [], // mis. jam kosong / null / format aneh
        ];

        // helper utk mencatat gagal
        $fail = function (string $reason, Absen $absen, array $extra = []) use (&$fails) {
            $fails[$reason][] = array_merge([
                'id' => $absen->id,
            ], $extra);
        };

        Absen::query()
            ->whereNull('time_out')
            ->where(function ($q) {
                $q->whereNull('is_lembur')->orWhere('is_lembur', 0);
            })
            ->where('present', 1)
            ->where(function ($q) {
                $q->whereNull('absent')->orWhere('absent', 0);
            })
            ->with(['jadwalAbsen.shift'])
            ->chunkById(200, function ($chunk) use (&$updated, $zone, &$fails, $fail) {
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
                        $fail('missing_shift', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                        ]);
                        continue;
                    }

                    $tanggal = Carbon::parse($jadwal->tanggal_jadwal, $zone)->startOfDay();

                    // raw string untuk logging
                    $jmRaw = trim((string) $shift->jam_masuk);
                    $jkRaw = trim((string) $shift->jam_keluar);

                    if ($jmRaw === '' || $jkRaw === '') {
                        Log::warning("⛔ Jam masuk/keluar kosong untuk absen {$absen->id}");
                        $fail('invalid_data', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                            'jam_masuk'      => $jmRaw,
                            'jam_keluar'     => $jkRaw,
                            'alasan'         => 'empty_time',
                        ]);
                        continue;
                    }

                    $jmStr = $tanggal->toDateString() . ' ' . $jmRaw;
                    $jkStr = $tanggal->toDateString() . ' ' . $jkRaw;

                    try {
                        $jm = Carbon::hasFormat($jmStr, 'Y-m-d H:i:s')
                            ? Carbon::createFromFormat('Y-m-d H:i:s', $jmStr, $zone)
                            : Carbon::createFromFormat('Y-m-d H:i',    $jmStr, $zone);

                        $jk = Carbon::hasFormat($jkStr, 'Y-m-d H:i:s')
                            ? Carbon::createFromFormat('Y-m-d H:i:s', $jkStr, $zone)
                            : Carbon::createFromFormat('Y-m-d H:i',    $jkStr, $zone);
                    } catch (\Throwable $e) {
                        Log::warning("⛔ Gagal parse jam absen {$absen->id}: {$e->getMessage()}", [
                            'jm_str' => $jmStr,
                            'jk_str' => $jkStr,
                        ]);
                        $fail('parse_error', $absen, [
                            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                            'jam_masuk_raw'  => $jmStr,
                            'jam_keluar_raw' => $jkStr,
                            'error'          => $e->getMessage(),
                        ]);
                        continue;
                    }

                    // Deteksi shift malam (keluar < masuk)
                    $isShiftMalam = $jk->lt($jm);
                    if ($isShiftMalam) {
                        $jk->addDay();
                    }
                    Log::debug("Shift malam? " . ($isShiftMalam ? 'YA' : 'TIDAK'), [
                        'jm' => $jm->toDateTimeString() . ' WIB',
                        'jk' => $jk->toDateTimeString() . ' WIB',
                    ]);

                    $shiftSelesai = $jk->copy();
                    $toleransi    = $shiftSelesai->copy()->addHours(self::TOLERANSI_JAM_SETELAH_SHIFT);
                    $now          = now($zone);

                    // Toleransi: malam & siang
                    if ($isShiftMalam) {
                        if ($now->lt($toleransi) && !request()->boolean('force', false) && !$this->option('force')) {
                            Log::info("⏸️ Shift malam Absen {$absen->id} masih toleransi s/d {$toleransi->toDateTimeString()} WIB");
                            continue;
                        }
                    } else {
                        if ($now->lte($toleransi) && !$this->option('force')) {
                            Log::info("⏸️ Absen {$absen->id} masih toleransi s/d {$toleransi->toDateTimeString()} WIB");
                            continue;
                        }
                    }

                    Log::info("Check Absen {$absen->id}: selesai={$shiftSelesai->toDateTimeString()} toleransi={$toleransi->toDateTimeString()} now={$now->toDateTimeString()}");

                    // Simpan epoch
                    $absen->time_out      = $shiftSelesai->timestamp;
                    $absen->keterangan    = $absen->keterangan
                        ? $absen->keterangan . ' , Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)'
                        : 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';
                    $absen->deskripsi_out = 'Timer otomatis ditutup oleh sistem';
                    $absen->save();

                    $updated++;
                }
            });

        // Ringkasan
        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        Log::info("🏁 Selesai. Total diperbarui: {$updated}");

        // Rekap gagal per alasan
        $counts = array_map(fn($arr) => count($arr), $fails);
        $totalGagal = array_sum($counts);

        if ($totalGagal > 0) {
            $this->warn("⚠️ Gagal diproses total: {$totalGagal}  " .
                "(missing_jadwal: {$counts['missing_jadwal']}, " .
                "missing_shift: {$counts['missing_shift']}, " .
                "parse_error: {$counts['parse_error']}, " .
                "invalid_data: {$counts['invalid_data']})");

            // Log ringkas (IDs) per alasan
            foreach ($fails as $reason => $items) {
                $ids = array_column($items, 'id');
                Log::warning("⚠️ Gagal ({$reason}) count=" . count($items), ['ids' => $ids]);
            }

            // Log detail lengkap (payload besar sekali saja)
            Log::warning("⚠️ Detail gagal proses absen", ['fails' => $fails]);
        }
    }
}
