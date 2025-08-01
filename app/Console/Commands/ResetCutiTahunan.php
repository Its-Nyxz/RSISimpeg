<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetCutiTahunan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuti:reset-tahunan'; // Nama command yang akan dipanggil

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset sisa cuti tahunan semua karyawan ke jatah awal setiap tahun baru.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->update([
                'sisa_cuti_tahunan' => $user->jatah_cuti_tahunan, // Reset
            ]);
        }

        $this->info('Sukses reset semua sisa cuti tahunan!');
    }
}
