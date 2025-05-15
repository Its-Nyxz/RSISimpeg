<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\MasterGapok;
use App\Models\MasterGolongan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class UpdateKenaikanGolongan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kenaikan:golongan {--test : Jalankan mode test untuk kenaikan golongan langsung}';
    protected $description = 'Update golongan user jika memenuhi syarat kenaikan (setiap 4 tahun, maksimal 4 kali)';

    public function handle()
    {
        $updated = 0;
        $today = Carbon::today();
        $testMode = $this->option('test');

        $unitKepegawaianId = UnitKerja::whereRaw('LOWER(nama) = ?', ['kepegawaian'])->value('id');
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();

        $users = User::whereNotNull('tmt')
            ->whereNotNull('gol_id')
            ->with('pendidikanUser')
            ->get();

        foreach ($users as $user) {
            $tmt = Carbon::parse($user->tmt);
            $masaKerja = $tmt->diffInYears($today);
            $pendidikan = $user->pendidikanUser;

            if (!$pendidikan) {
                $this->warn("⚠ {$user->name} tidak memiliki data pendidikan.");
                continue;
            }



            $currentGolId = $user->gol_id;
            $maxGolId = $testMode ? $pendidikan->maxim_gol : $pendidikan->maxim_gol;

            // Hitung berapa kali bisa naik golongan (setiap 4 tahun, maksimal 4x)
            $jumlahNaik = $testMode ? 1 : min(4, floor($masaKerja / 4));

            if ($jumlahNaik < 1) {
                $this->line("⏭ {$user->name} belum cukup masa kerja untuk naik golongan.");
                continue;
            }

            // Ambil golongan berikutnya
            $golonganSetelah = MasterGolongan::where('id', '>', $currentGolId)
                ->where('id', '<=', $maxGolId)
                ->orderBy('id')
                ->take($jumlahNaik)
                ->get();

            if ($golonganSetelah->isEmpty()) {
                $this->line("⏭ {$user->name} sudah di golongan tertinggi.");
                Log::channel('kenaikan_golongan')->warning("⚠ {$user->name} gagal diproses: tidak memiliki data pendidikan | UserID: {$user->id}");
                continue;
            }

            $golonganBerikutnya = $golonganSetelah->first();

            if ($golonganBerikutnya->id === $currentGolId) {
                continue; // sudah sesuai
            }

            // Cek apakah user sudah pernah diajukan kenaikan golongan dengan gol_id_baru ini
            $alreadySubmitted = DB::table('t_gapok')
                ->where('user_id', $user->id)
                ->where('jenis_kenaikan', 'golongan')
                ->where('gol_id_baru', $golonganBerikutnya->id)
                ->exists();

            if ($alreadySubmitted && !$testMode) {
                $this->line("⏭ {$user->name} sudah pernah diajukan untuk golongan ini.");
                continue;
            }

            // Ambil gapok lama
            $gapokLama = MasterGapok::where('gol_id', $currentGolId)
                ->where('masa_kerja', '<=', $masaKerja)
                ->orderByDesc('masa_kerja')
                ->first();

            Carbon::setLocale('id');
            $tanggalKenaikan = Carbon::parse($tmt)->addYears($jumlahNaik * 4)->translatedFormat('d F Y');

            // Simpan riwayat ke t_gapok
            DB::table('t_gapok')->insert([
                'user_id' => $user->id,
                'gol_id' => $currentGolId,
                'gol_id_baru' => $golonganBerikutnya->id,
                'masa_kerja' => (int) $masaKerja,
                'gapok' => $gapokLama?->nominal_gapok ?? 0,
                'jenis_kenaikan' => 'golongan',
                'tanggal_kenaikan' =>  Carbon::parse($tmt)->addYears($jumlahNaik * 4)->toDateString(),
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Pengajuan Kenaikan Golongan ' . $user->name .
                ' dari gol <span class="font-bold">' . ($user->golongan?->nama ?? 'Tidak diketahui') .
                ' ke ' . $golonganBerikutnya->nama .
                '</span>  di tanggal ' . $tanggalKenaikan . ' membutuhkan persetujuan Anda.';

            $url = "/kenaikan";
            if ($kepegawaianUsers) {
                Notification::send($kepegawaianUsers, new UserNotification($message, $url));
            }

            // Update golongan user
            // $user->gol_id = $golonganBerikutnya->id;
            // $user->save();

            $this->info("✔ {$user->name} naik golongan → {$golonganBerikutnya->nama} (ID: {$golonganBerikutnya->id})");
            Log::channel('kenaikan_golongan')->info("✔ {$user->name} naik golongan dari ID {$currentGolId} ke {$golonganBerikutnya->id} ({$golonganBerikutnya->nama}) pada tanggal $tanggalKenaikan | UserID: {$user->id}");
            $updated++;
        }

        $this->info("Total user yang naik golongan: $updated");
        Log::channel('kenaikan_golongan')->info("Total user naik golongan: $updated pada " . now()->toDateString());
    }
}
