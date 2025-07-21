<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateAbsenTimeout extends Command
{
    protected $signature = 'generate:absen-timeout {--force}';
    protected $description = 'Menutup otomatis absensi yang belum selesai jika shift sudah lewat 6 jam';

    public function handle()
    {
        $this->info("⏳ Mengecek absensi tanpa time_out...");
        Log::info("⏳ Mengecek absensi tanpa time_out...");

        $absens = Absen::whereNull('time_out')
            ->whereNull('is_lembur')
            ->where('present', 1)
            ->whereNull('absent')
            ->with(['jadwalAbsen.shift'])
            ->get();

        $updated = 0;

        foreach ($absens as $absen) {
            $jadwal = $absen->jadwalAbsen;
            $shift = $jadwal?->shift;

            if (!$jadwal || !$shift) {
                $this->warn("❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.");
                Log::warning("❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.");
                continue;
            }

            $jamMasuk = Carbon::parse($shift->jam_masuk);
            $jamKeluar = Carbon::parse($shift->jam_keluar);
            $isShiftMalam = $jamKeluar->lessThan($jamMasuk); // shift malam (misalnya jam 22:00 - 06:00)

            // Jika shift malam, set waktu selesai shift ke hari berikutnya (jam 6 pagi)
            $shiftSelesai = Carbon::parse($jadwal->tanggal_jadwal)
                ->addDays($isShiftMalam ? 1 : 0)  // Menambahkan 1 hari jika shift malam
                ->setTimeFrom($jamKeluar);

            $now = now();
            $jamTerlambat = $now->diffInHours($shiftSelesai, false);

            // Cek jika waktu sekarang lebih dari 1 jam setelah shift selesai, dan pastikan not force
            if ($jamTerlambat <= 1 && !$this->option('force')) {
                $this->line("⏸️  Absen ID {$absen->id} masih dalam toleransi (<=1 jam setelah shift).");
                Log::info("⏸️  Absen ID {$absen->id} masih dalam toleransi (<=1 jam setelah shift).");
                continue;
            }

            $keteranganLama = $absen->keterangan;
            $keteranganBaru = 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';

            // Update data absen
            $absen->update([
                'time_out' => $shiftSelesai->timestamp,
                'keterangan' => $keteranganLama
                    ? $keteranganLama . ' , ' . $keteranganBaru
                    : $keteranganBaru,
                'deskripsi_out' => 'Timer otomatis ditutup oleh sistem',
            ]);

            $this->info("✅ Timer absen ID {$absen->id} ditutup otomatis.");
            Log::info("✅ Timer absen ID {$absen->id} ditutup otomatis.");

            $updated++;
        }

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        Log::info("🏁 Selesai. Total diperbarui: {$updated}");
    }
}
