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

        Absen::query()
            // time_out kosong bisa null / 0 / '0000-00-00 00:00:00'
            ->where(function ($q) {
                $q->whereNull('time_out')
                    ->orWhere('time_out', 0)
                    ->orWhere('time_out', '0000-00-00 00:00:00');
            })
            // is_lembur bisa null / 0
            ->where(function ($q) {
                $q->whereNull('is_lembur')
                    ->orWhere('is_lembur', 0);
            })
            // present = 1
            ->where('present', 1)
            // absent bisa null / 0
            ->where(function ($q) {
                $q->whereNull('absent')
                    ->orWhere('absent', 0);
            })
            ->with(['jadwalAbsen.shift'])
            ->chunkById(200, function ($chunk) use (&$updated) {
                foreach ($chunk as $absen) {
                    $jadwal = $absen->jadwalAbsen;
                    $shift  = $jadwal?->shift;

                    if (!$jadwal || !$shift) {
                        $msg = "❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.";
                        $this->warn($msg);
                        Log::warning($msg);
                        continue;
                    }

                    // Gabungkan tanggal_jadwal dengan jam shift
                    $tanggal    = Carbon::parse($jadwal->tanggal_jadwal)->startOfDay();
                    $jamMasuk   = Carbon::parse($tanggal->toDateString() . ' ' . $shift->jam_masuk);
                    $jamKeluar  = Carbon::parse($tanggal->toDateString() . ' ' . $shift->jam_keluar);

                    // Handle shift malam (jam keluar < jam masuk)
                    $isShiftMalam = $jamKeluar->lt($jamMasuk);
                    if ($isShiftMalam) {
                        $jamKeluar->addDay();
                    }

                    $shiftSelesai = $jamKeluar;
                    $toleransi    = $shiftSelesai->copy()->addHour();
                    $now          = now();

                    // Skip jika masih dalam toleransi dan bukan --force
                    if ($now->lte($toleransi) && !$this->option('force')) {
                        $msg = "⏸️  Absen ID {$absen->id} masih dalam toleransi (<= 1 jam setelah shift).";
                        $this->line($msg);
                        Log::info($msg);
                        continue;
                    }

                    // Keterangan baru
                    $keteranganLama = $absen->keterangan;
                    $keteranganBaru = 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';

                    // Update data absen
                    $absen->time_out      = $shiftSelesai->timestamp; // Simpan epoch
                    $absen->keterangan    = $keteranganLama
                        ? $keteranganLama . ' , ' . $keteranganBaru
                        : $keteranganBaru;
                    $absen->deskripsi_out = 'Timer otomatis ditutup oleh sistem';
                    $absen->save();

                    // Logging detail
                    Log::debug("▶️ DEBUG Absen ID {$absen->id}", [
                        'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                        'jam_masuk'      => $shift->jam_masuk,
                        'jam_keluar'     => $shift->jam_keluar,
                        'is_shift_malam' => $isShiftMalam ? 'Ya' : 'Tidak',
                        'shift_selesai'  => $shiftSelesai->toDateTimeString(),
                        'now'            => $now->toDateTimeString(),
                        'toleransi'      => $toleransi->toDateTimeString(),
                        'time_out_epoch' => $absen->time_out,
                    ]);

                    $this->info("✅ Timer absen ID {$absen->id} ditutup otomatis.");
                    Log::info("✅ Timer absen ID {$absen->id} ditutup otomatis.");

                    $updated++;
                }
            });

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        Log::info("🏁 Selesai. Total diperbarui: {$updated}");
    }
}
