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
            ->dailyAt('02:00')
            ->timezone('Asia/Jakarta');

        $schedule->command('kenaikan:golongan')
            ->dailyAt('03:00')
            ->timezone('Asia/Jakarta');

        $schedule->command('update:masa-kerja')
            ->dailyAt('01:00')
            ->timezone('Asia/Jakarta');

        $schedule->command('generate:absen-alpha')
            ->dailyAt('08:15')
            ->timezone('Asia/Jakarta');

        $schedule->command('kenaikan:kontrak')
            ->dailyAt('00:30')
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
