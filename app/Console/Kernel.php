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
            ->dailyAt('00:10')
            ->timezone('Asia/Jakarta')
            ->when(function () {
                return now('Asia/Jakarta')->isJanuary() &&
                    now('Asia/Jakarta')->day === 1;
            });

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
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta');

        $schedule->command('kenaikan:kontrak')
            ->dailyAt('00:30')
            ->timezone('Asia/Jakarta');

        // $schedule->command('generate:absen-timeout')
        //     ->everyFiveMinutes()
        //     ->withoutOverlapping(10) // biar lock auto-lepas
        //     ->timezone('Asia/Jakarta')
        //     ->appendOutputTo(storage_path('logs/absen-timeout.log'));
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
