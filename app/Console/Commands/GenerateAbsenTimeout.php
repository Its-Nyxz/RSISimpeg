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
        $gagal   = []; // <- simpan ID yang gagal diproses
        $zone    = 'Asia/Jakarta';

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
            ->chunkById(200, function ($chunk) use (&$updated, &$gagal, $zone) {
                foreach ($chunk as $absen) {
                    $jadwal = $absen->jadwalAbsen;
                    $shift  = $jadwal?->shift;

                    if (!$jadwal || !$shift) {
                        Log::warning("❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.");
                        $gagal[] = $absen->id;
                        continue;
                    }

                    $tanggal = Carbon::parse($jadwal->tanggal_jadwal, $zone)->startOfDay();
                    try {
                        $jmStr = $tanggal->toDateString() . ' ' . trim($shift->jam_masuk);
                        $jkStr = $tanggal->toDateString() . ' ' . trim($shift->jam_keluar);

                        $jm = Carbon::hasFormat($jmStr, 'Y-m-d H:i:s')
                            ? Carbon::createFromFormat('Y-m-d H:i:s', $jmStr, $zone)
                            : Carbon::createFromFormat('Y-m-d H:i',    $jmStr, $zone);

                        $jk = Carbon::hasFormat($jkStr, 'Y-m-d H:i:s')
                            ? Carbon::createFromFormat('Y-m-d H:i:s', $jkStr, $zone)
                            : Carbon::createFromFormat('Y-m-d H:i',    $jkStr, $zone);
                    } catch (\Throwable $e) {
                        Log::warning("⛔ Gagal parse jam absen {$absen->id}: {$e->getMessage()}");
                        $gagal[] = $absen->id;
                        continue;
                    }

                    $isShiftMalam = $jk->lt($jm);
                    if ($isShiftMalam) {
                        $jk->addDay();
                    }
                    Log::debug("Shift malam? " . ($isShiftMalam ? 'YA' : 'TIDAK'), [
                        'jm' => $jm->toDateTimeString() . ' WIB',
                        'jk' => $jk->toDateTimeString() . ' WIB', // jk sudah mungkin +1 hari
                    ]);

                    // 3) Hitung selesai & toleransi
                    $shiftSelesai = $jk->copy();
                    $toleransi    = $shiftSelesai->copy()->addHours(self::TOLERANSI_JAM_SETELAH_SHIFT);
                    $now          = now($zone);

                    // 4) GANTI blok pengecekan toleransimu dengan ini:
                    if ($isShiftMalam) {
                        // Untuk shift malam, jangan auto-close di 00:00; tunggu jam keluar + toleransi
                        if ($now->lt($toleransi) && !$this->option('force')) {
                            Log::info("⏸️ Shift malam Absen {$absen->id} masih dalam waktu toleransi s/d {$toleransi->toDateTimeString()} WIB");
                            continue;
                        }
                    } else {
                        // Shift siang normal
                        if ($now->lte($toleransi) && !$this->option('force')) {
                            Log::info("⏸️ Absen {$absen->id} masih toleransi s/d {$toleransi->toDateTimeString()} WIB");
                            continue;
                        }
                    }

                    // 5) (opsional) log verifikasi
                    Log::info("Check Absen {$absen->id}: selesai={$shiftSelesai->toDateTimeString()} toleransi={$toleransi->toDateTimeString()} now={$now->toDateTimeString()}");

                    // 6) Simpan hasil (punyamu sudah OK)
                    $absen->time_out      = $shiftSelesai->timestamp;
                    $absen->keterangan    = $absen->keterangan
                        ? $absen->keterangan . ' , Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)'
                        : 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';
                    $absen->deskripsi_out = 'Timer otomatis ditutup oleh sistem';
                    $absen->save();

                    $updated++;
                }
            });

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        Log::info("🏁 Selesai. Total diperbarui: {$updated}");

        if (!empty($gagal)) {
            $this->warn("⚠️ Gagal diproses: " . implode(', ', $gagal));
            Log::warning("⚠️ Absen gagal diproses", ['ids' => $gagal]);
        }
    }
}
