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

    public function handle()
    {
        $this->info("⏳ Mengecek absensi tanpa time_out...");
        Log::channel('absen_timeout')->info("⏳ Mengecek absensi tanpa time_out...");

        $absens = Absen::whereNull('time_out')
            ->whereNull('is_lembur')
            ->where('present', 1)
            ->whereNull('absent')
            ->with(['jadwalAbsen.shift'])
            ->get();

        $updated = 0;
        $tz = 'Asia/Jakarta';

        foreach ($absens as $absen) {
            $jadwal = $absen->jadwalAbsen;
            $shift  = $jadwal?->shift;

            if (!$jadwal || !$shift) {
                $this->warn("❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.");
                Log::channel('absen_timeout')->warning("❌ Absen ID {$absen->id} tidak memiliki jadwal atau shift.");
                continue;
            }

            if (!$absen->time_in) {
                $this->warn("❌ Absen ID {$absen->id} tidak punya time_in.");
                Log::channel('absen_timeout')->warning("❌ Absen ID {$absen->id} tidak punya time_in.");
                continue;
            }

            // Normalisasi format jam ke H:i:s bila perlu
            $jamMasukStr  = strlen($shift->jam_masuk)  === 5 ? $shift->jam_masuk . ':00'  : $shift->jam_masuk;
            $jamKeluarStr = strlen($shift->jam_keluar) === 5 ? $shift->jam_keluar . ':00' : $shift->jam_keluar;

            $jamMasuk  = Carbon::createFromFormat('H:i:s', $jamMasukStr,  $tz);
            $jamKeluar = Carbon::createFromFormat('H:i:s', $jamKeluarStr, $tz);
            $isShiftMalam = $jamKeluar->lessThan($jamMasuk); // contoh 23:00–07:00

            // Anchor: time_in absensi (timestamp)
            $timeIn = Carbon::createFromTimestamp($absen->time_in, $tz);
            $now    = now($tz);

            // Bangun dua kandidat window: berdasarkan date(time_in) dan date(time_in - 1)
            $kandidat = [];
            foreach ([0, -1] as $offset) {
                $tanggal = $timeIn->copy()->addDays($offset)->toDateString();
                $start = Carbon::parse($tanggal . ' ' . $jamMasukStr, $tz);
                $end   = Carbon::parse($tanggal . ' ' . $jamKeluarStr, $tz)
                    ->addDays($isShiftMalam ? 1 : 0);
                $kandidat[] = compact('start', 'end');
            }

            // Pilih window yang benar-benar mencakup time_in (tanpa toleransi masuk)
            $pakai = collect($kandidat)->first(function ($w) use ($timeIn) {
                return $timeIn->between($w['start'], $w['end']);
            }) ?? $kandidat[0];

            $shiftSelesai = $pakai['end'];

            // Toleransi tutup: 1 jam setelah jam keluar
            $toleransi = $shiftSelesai->copy()->addHour();

            // Masih dalam toleransi & bukan --force → skip
            if ($now->lessThanOrEqualTo($toleransi) && !$this->option('force')) {
                $this->line("⏸️  Absen ID {$absen->id} masih dalam toleransi (<=1 jam setelah shift).");
                Log::channel('absen_timeout')->info("⏸️  Absen ID {$absen->id} masih dalam toleransi (<=1 jam setelah shift).");
                continue;
            }

            // Update time_out ke jam selesai shift (bukan jam sekarang)
            $keteranganBaru = 'Timer otomatis ditutup oleh sistem (shift melebihi 1 jam)';
            $absen->update([
                'time_out'      => $shiftSelesai->timestamp,
                'keterangan'    => $absen->keterangan ? $absen->keterangan . ' , ' . $keteranganBaru : $keteranganBaru,
                'deskripsi_out' => 'Timer otomatis ditutup oleh sistem',
            ]);

            // Logging detail untuk audit
            Log::channel('absen_timeout')->debug("▶️ DEBUG Absen ID {$absen->id}");
            Log::channel('absen_timeout')->debug("- time_in: {$timeIn->toDateTimeString()}");
            Log::channel('absen_timeout')->debug("- jam_masuk: {$jamMasukStr}");
            Log::channel('absen_timeout')->debug("- jam_keluar: {$jamKeluarStr}");
            Log::channel('absen_timeout')->debug("- is_shift_malam: " . ($isShiftMalam ? 'Ya' : 'Tidak'));
            Log::channel('absen_timeout')->debug("- window_start: {$pakai['start']->toDateTimeString()}");
            Log::channel('absen_timeout')->debug("- window_end  : {$shiftSelesai->toDateTimeString()}");
            Log::channel('absen_timeout')->debug("- toleransi   : {$toleransi->toDateTimeString()}");
            Log::channel('absen_timeout')->debug("- now         : {$now->toDateTimeString()}");

            $this->info("✅ Timer absen ID {$absen->id} ditutup otomatis.");
            Log::channel('absen_timeout')->info("✅ Timer absen ID {$absen->id} ditutup otomatis.");

            $updated++;
        }

        $this->info("🏁 Selesai. Total diperbarui: {$updated}");
        Log::channel('absen_timeout')->info("🏁 Selesai. Total diperbarui: {$updated}");
    }
}
