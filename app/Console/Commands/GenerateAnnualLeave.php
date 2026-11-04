<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterJatahCuti;
use App\Models\SisaCutiTahunan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class GenerateAnnualLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:cuti-tahunan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sisa cuti tahunan untuk seluruh user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now('Asia/Jakarta');
        $currentYear = $today->year;

        if (Schema::hasTable('sisa_cuti_tahunans') && Schema::hasTable('master_jatah_cutis')) {
            // Ambil hanya user dengan jenis karyawan 'Tetap'
            $users = User::whereHas('jenis', function ($query) {
                $query->where('nama', 'Tetap');
            })->get();

            $jatahCuti = MasterJatahCuti::where('tahun', $currentYear)
                ->value('jumlah_cuti') ?? 12;

            $countNew = 0;
            foreach ($users as $user) {
                // firstOrCreate memastikan karyawan yang sudah kasbon (saldo < 12) tidak tertimpa
                $record = SisaCutiTahunan::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'tahun' => $currentYear,
                    ],
                    [
                        'sisa_cuti' => $jatahCuti,
                    ]
                );

                if ($record->wasRecentlyCreated) {
                    $countNew++;
                }
            }

            $this->info("Sisa cuti berhasil disinkronisasi untuk karyawan tetap ($countNew data baru dibuat).");
        }
    }
}
