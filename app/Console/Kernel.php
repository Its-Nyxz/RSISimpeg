<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;

class Kernel extends ConsoleKernel
{
    /**
     * Tentukan perintah Artisan yang tersedia untuk aplikasi ini.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Tentukan jadwal perintah aplikasi ini.
     */
    protected function schedule(Schedule $schedule)
    {
        // Reset cuti tahunan setiap tahun
        $schedule->call(function () {
            User::all()->each(function ($karyawan) {
                $karyawan->resetCutiTahunan(); // Fungsi reset pada model
            });
        })->yearly(); // Eksekusi setiap awal tahun (1 Januari)
    }
}
