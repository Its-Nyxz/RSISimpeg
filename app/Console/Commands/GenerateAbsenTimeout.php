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
        $zone = 'Asia/Jakarta';

        Absen::query()
            ->where(function ($q) {
                $q->whereNull('time_out')
                    ->orWhere('time_out', 0)
                    ->orWhere('time_out', '0000-00-00 00:00:00'); // kalau kolom integer, baris ini tidak akan match — tidak masalah
            })
            ->where(function ($q) {
                $q->whereNull('is_lembur')->orWhere('is_lembur', 0);
            })
            ->where('present', 1)
            ->where(function ($q) {
                $q->whereNull('absent')->orWhere('absent', 0);
            })
            ->with(['jadwalAbsen.shift'])
            // kalau PK bukan 'id' atau bukan auto increment, ganti ke ->chunk(200)
            ->chunkById(200, function ($chunk) use (&$updated, $zone) {
                foreach ($chunk as $absen) {
                    $jadwal = $absen->jadwalAbsen;
                    $shift  = $jadwal?->shift;

                    if (!$jadwal || !$shift) {
                        $msg = "❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.";
                        Log::warning($msg);
                        continue;
                    }

                    // Bangun datetime dengan zona WIB agar konsisten
                    $tanggal   = Carbon::parse($jadwal->tanggal_jadwal, $zone)->startOfDay();
                    $jamMasuk  = Carbon::parse($tanggal->toDateString() . ' ' . $shift->jam_masuk, $zone);
                    $jamKeluar = Carbon::parse($tanggal->toDateString() . ' ' . $shift->jam_keluar, $zone);

                    // Shift malam
                    $isShiftMalam = $jamKeluar->lt($jamMasuk);
                    if ($isShiftMalam) {
                        $jamKeluar->addDay();
                    }

                    $shiftSelesai = $jamKeluar->copy();
                    $toleransi    = $shiftSelesai->copy()->addHours(self::TOLERANSI_JAM_SETELAH_SHIFT);
                    $now          = now($zone);

                    // Masih dalam toleransi? skip (kecuali --force)
                    if ($now->lte($toleransi) && !$this->option('force')) {
                        Log::info("⏸️ Absen {$absen->id} masih toleransi s/d {$toleransi->toDateTimeString()} WIB");
                        continue;
                    }

                    // SIMPAN EPOCH (integer)
                    $absen->time_out      = $shiftSelesai->timestamp; // <— epoch
                    $absen->keterangan    = $absen->keterangan
                        ? $absen->keterangan . ' , Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)'
                        : 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';
                    $absen->deskripsi_out = 'Timer otomatis ditutup oleh sistem';
                    $absen->save();

                    Log::debug("▶️ DEBUG Absen {$absen->id}", [
                        'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                        'jam_masuk'      => $shift->jam_masuk,
                        'jam_keluar'     => $shift->jam_keluar,
                        'is_shift_malam' => $isShiftMalam,
                        'shift_selesai'  => $shiftSelesai->toDateTimeString() . ' WIB',
                        'toleransi'      => $toleransi->toDateTimeString() . ' WIB',
                        'now'            => $now->toDateTimeString() . ' WIB',
                        'time_out_epoch' => $absen->time_out,
                    ]);

                    $updated++;
                }
            });

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        Log::info("🏁 Selesai. Total diperbarui: {$updated}");
    }
}
