<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Settings;
use App\Models\MasterJatahCuti;
use App\Models\SisaCutiTahunan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        // $today = Carbon::now('Asia/Jakarta');
        // $currentYear = $today->year;

        // if (Schema::hasTable('sisa_cuti_tahunans') && Schema::hasTable('master_jatah_cutis')) {
        //     $alreadyGenerated = SisaCutiTahunan::where('tahun', $currentYear)->exists();

        //     if (!$alreadyGenerated) {
        //         $users = User::all();

        //         foreach ($users as $user) {
        //             $golonganId = $user->gol_id; // Sesuaikan field user
        //             $jatahCuti = MasterJatahCuti::where('tahun', $currentYear)
        //                 ->where('golongan_id', $golonganId)
        //                 ->value('jumlah_cuti') ?? 12; // Default 12

        //             SisaCutiTahunan::create([
        //                 'user_id' => $user->id,
        //                 'tahun' => $currentYear,
        //                 'sisa_cuti' => $jatahCuti,
        //             ]);
        //         }
        //     }
        // }
    }
}
