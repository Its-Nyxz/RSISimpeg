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
            $alreadyGenerated = SisaCutiTahunan::where('tahun', $currentYear)->exists();

            if (!$alreadyGenerated) {
                $users = User::all();

                foreach ($users as $user) {
                    $golonganId = $user->gol_id;
                    $jatahCuti = MasterJatahCuti::where('tahun', $currentYear)
                        ->where('golongan_id', $golonganId)
                        ->value('jumlah_cuti') ?? 12;

                    SisaCutiTahunan::create([
                        'user_id' => $user->id,
                        'tahun' => $currentYear,
                        'sisa_cuti' => $jatahCuti,
                    ]);
                }

                $this->info('Sisa cuti berhasil digenerate.');
            } else {
                $this->info('Data sudah ada untuk tahun ini.');
            }
        }
    }
}
