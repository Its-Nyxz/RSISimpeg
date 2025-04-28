<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Tentukan perintah Artisan yang tersedia untuk aplikasi ini.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('cuti:reset-tahunan')
            ->yearlyOn(1, 1, '00:00')
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
