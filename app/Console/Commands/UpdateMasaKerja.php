<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateMasaKerja extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:masa-kerja {--test=}';
    protected $description = 'Perbarui masa kerja user secara otomatis setiap hari';

    public function handle(): void
    {
        $testDate = $this->option('test');

        // Gunakan test date jika diberikan, else pakai tanggal hari ini
        $today = $testDate ? Carbon::parse($testDate)->startOfDay() : Carbon::today();

        $this->info('Menjalankan update masa kerja pada tanggal: ' . $today->toDateString());

        $users = User::with('jenis')->whereNotNull('tmt')->get();
        $updated = 0;

        foreach ($users as $user) {
            $jenis = strtolower($user->jenis?->nama ?? '');
            $tmt = Carbon::parse($user->tmt);

            if ($jenis === 'kontrak') {
                if ($today->day === $tmt->day && $today->greaterThan($tmt)) {
                    $bulan = $tmt->diffInMonths($today);
                    $user->masa_kerja = (int) $bulan;
                    $user->save();
                    $updated++;
                }
            } elseif ($jenis === 'tetap') {
                if ($today->day === $tmt->day && $today->month === $tmt->month && $today->greaterThan($tmt)) {
                    $tahun = $tmt->diffInYears($today);
                    $user->masa_kerja = (int) $tahun;
                    $user->save();
                    $updated++;
                }
            }
        }

        $this->info("Selesai. Total user yang diperbarui: {$updated}");
    }
}
