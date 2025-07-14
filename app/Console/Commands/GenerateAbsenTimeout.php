<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Console\Command;

class GenerateAbsenTimeout extends Command
{
    protected $signature = 'generate:absen-timeout {--force}';
    protected $description = 'Menutup otomatis absensi yang belum selesai jika shift sudah lewat 6 jam';

    public function handle()
    {
        $this->info("⏳ Mengecek absensi tanpa time_out...");

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
                continue;
            }

            $jamMasuk = Carbon::parse($shift->jam_masuk);
            $jamKeluar = Carbon::parse($shift->jam_keluar);
            $isShiftMalam = $jamKeluar->lessThan($jamMasuk);

            $shiftSelesai = Carbon::parse($jadwal->tanggal_jadwal)
                ->addDays($isShiftMalam ? 1 : 0)
                ->setTimeFrom($jamKeluar);

            $now = now();
            $jamTerlambat = $now->diffInHours($shiftSelesai, false);

            if ($jamTerlambat <= 1 && !$this->option('force')) {
                $this->line("⏸️  Absen ID {$absen->id} masih dalam toleransi (<=1 jam setelah shift).");
                continue;
            }

            $keteranganLama = $absen->keterangan;
            $keteranganBaru = 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';

            $absen->update([
                'time_out' => $shiftSelesai->timestamp,
                'keterangan' => $keteranganLama
                    ? $keteranganLama . ' , ' . $keteranganBaru
                    : $keteranganBaru,
                'deskripsi_out' => 'Timer otomatis ditutup oleh sistem',
            ]);

            $this->info("✅ Timer absen ID {$absen->id} ditutup otomatis.");
            $updated++;
        }

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
    }
}
