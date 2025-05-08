<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\GenerateAnnualLeave;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Tentukan perintah Artisan yang tersedia untuk aplikasi ini.
     */
    protected $commands = [
        GenerateAnnualLeave::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('generate:cuti-tahunan')
            ->yearlyOn(1, 1, '00:00')
            ->timezone('Asia/Jakarta');

        $schedule->command('kenaikan:berkala')
            ->monthlyOn(1, '02:00') // Setiap tanggal 1 pukul 02:00 WIB
            ->timezone('Asia/Jakarta');

        $schedule->command('kenaikan:golongan')
            ->monthlyOn(1, '03:00') // misal tanggal 1 jam 03:00 WIB
            ->timezone('Asia/Jakarta');

        $schedule->command('update:masa-kerja')
            ->dailyAt('02:00')
            ->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
