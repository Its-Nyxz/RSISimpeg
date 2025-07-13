<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Console\Command;

class GenerateAbsenTimeout extends Command
{
    protected $signature = 'app:generate-absen-timeout {--force}';
    protected $description = 'Menutup otomatis absensi yang belum selesai jika shift sudah lewat 6 jam';

    public function handle()
    {
        $this->info("⏳ Mengecek absensi tanpa time_out...");

        $absens = Absen::whereNull('time_out')
            ->where('present', 1)
            ->where('is_lembur', false)
            ->with(['jadwal.shift'])
            ->get();

        $updated = 0;

        foreach ($absens as $absen) {
            $jadwal = $absen->jadwal;
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
            $jamTerlambat = $shiftSelesai->diffInHours($now, false);

            if ($jamTerlambat < 6 && !$this->option('force')) {
                $this->line("⏸️  Absen ID {$absen->id} masih dalam toleransi (<6 jam setelah shift).");
                continue;
            }

            $absen->update([
                'time_out' => $shiftSelesai->timestamp,
                'keterangan' => 'Timer otomatis ditutup oleh sistem (shift melebihi 6 jam)',
            ]);

            $this->info("✅ Timer absen ID {$absen->id} ditutup otomatis.");
            $updated++;
        }

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
    }
}
