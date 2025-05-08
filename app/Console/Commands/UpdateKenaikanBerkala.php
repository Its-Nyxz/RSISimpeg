<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterGapok;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateKenaikanBerkala extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kenaikan:berkala {--test : Jalankan mode pengujian tanpa batas 2 tahun}';
    protected $description = 'Simpan riwayat dan update masa kerja jika user naik gaji berkala tiap 2 tahun';

    public function handle()
    {
        $updated = 0;
        $today = Carbon::today();
        $isTest = $this->option('test');

        $users = User::whereNotNull('tmt')
            ->whereNotNull('gol_id')
            ->get();

        foreach ($users as $user) {
            $tmt = Carbon::parse($user->tmt);
            $masaKerjaSebelumnya = (int) ($user->masa_kerja ?? 0);

            $masaKerjaAktual = $isTest
                ? $masaKerjaSebelumnya + 2
                : $tmt->diffInYears($today);

            if (!$isTest && ($masaKerjaAktual - $masaKerjaSebelumnya) < 2) {
                continue; // Belum waktunya naik jika bukan mode test
            }

            // Cek apakah sudah pernah naik dengan masa kerja ini
            $alreadyRaised = DB::table('t_gapok')
                ->where('user_id', $user->id)
                ->where('jenis_kenaikan', 'berkala')
                ->where('masa_kerja', $masaKerjaSebelumnya)
                ->exists();

            if ($alreadyRaised && !$isTest) {
                continue;
            }

            // Ambil gapok lama
            $gapokLama = MasterGapok::where('gol_id', $user->gol_id)
                ->where('masa_kerja', '<=', $masaKerjaSebelumnya)
                ->orderByDesc('masa_kerja')
                ->first();

            // Ambil gapok baru
            $gapokBaru = MasterGapok::where('gol_id', $user->gol_id)
                ->where('masa_kerja', '<=', $masaKerjaAktual)
                ->orderByDesc('masa_kerja')
                ->first();

            if (!$gapokBaru) {
                $this->warn("⚠ {$user->name} tidak ditemukan data gapok barunya, dilewati.");
                continue;
            }

            // Simpan ke t_gapok
            DB::table('t_gapok')->insert([
                'user_id' => $user->id,
                'gol_id' => $user->gol_id,
                'masa_kerja' => $masaKerjaSebelumnya,
                'gapok' => $gapokLama?->nominal_gapok ?? 0,
                'jenis_kenaikan' => 'berkala',
                'tanggal_kenaikan' => $today->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update masa kerja
            $user->masa_kerja = (int) $masaKerjaAktual;
            $user->save();

            $this->info("✔ {$user->name} naik gaji → Rp" . number_format($gapokBaru->nominal_gapok, 0, ',', '.') . " | masa kerja: $masaKerjaAktual tahun");
            $updated++;
        }

        $this->info("Total user yang naik gaji berkala: $updated");
    }
}
