<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterGapok;
use Illuminate\Console\Command;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

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
            $currentMasaKerja = (int) ($user->masa_kerja ?? 0);
            $actualMasaKerja = $tmt->diffInYears($today);

            $spList = PeringatanKaryawan::where('user_id', $user->id)
                ->whereIn('tingkat', ['II', 'III'])
                ->where('tanggal_sp', '>=', $today->copy()->subYears(2))
                ->orderBy('tanggal_sp')
                ->get();

            $totalPenundaanTahun = 0;

            foreach ($spList as $sp) {
                $totalPenundaanTahun += 2; // Setiap SP tingkat II / III tunda 2 tahun
            }

            if ($totalPenundaanTahun > 0) {
                // Hitung tanggal kenaikan berkala normal berikutnya
                $kenaikanNormal = $tmt->copy();
                while ($kenaikanNormal->addYears(2)->lte($today)) {
                    // loop tetap jalan, tapi tidak perlu isi apa-apa
                }
                $kenaikanNormalBerikutnya = $kenaikanNormal; // sekarang sudah > today

                // Hitung tanggal setelah penundaan
                $tundaHingga = $kenaikanNormalBerikutnya->copy()->addYears($totalPenundaanTahun);
                $jumlahSP1 = $spList->where('tingkat', 'II')->count(); // SP1
                $pesanTunda = "Kenaikan gaji berkala Anda <strong>ditunda</strong> karena adanya " .
                    "<strong>{$jumlahSP1} SP1</strong>" .
                    " dalam 2 tahun terakhir.<br>" .
                    "Kenaikan ditunda hingga <strong>{$tundaHingga->translatedFormat('d F Y')}</strong>.";

                Notification::send($user, new UserNotification($pesanTunda, null));

                $this->warn("⏸ {$user->name} ditunda kenaikan gaji karena ada {$spList->count()} SP dalam 2 tahun");
                continue;
            }

            $newMasaKerja = $isTest ? $currentMasaKerja + 2 : $actualMasaKerja;

            if (!$isTest && ($newMasaKerja - $currentMasaKerja) < 2) {
                continue;
            }

            $alreadyLogged = DB::table('t_gapok')
                ->where('user_id', $user->id)
                ->where('jenis_kenaikan', 'berkala')
                ->where('masa_kerja', $newMasaKerja)
                ->exists();

            if ($alreadyLogged && !$isTest) {
                continue;
            }

            $gapokLama = MasterGapok::where('gol_id', $user->gol_id)
                ->where('masa_kerja', '<=', $currentMasaKerja)
                ->orderByDesc('masa_kerja')
                ->first();

            $gapokBaru = MasterGapok::where('gol_id', $user->gol_id)
                ->where('masa_kerja', '<=', $newMasaKerja)
                ->orderByDesc('masa_kerja')
                ->first();

            if (!$gapokBaru) {
                $this->warn("⚠ {$user->name} tidak ditemukan data gapok baru untuk masa kerja $newMasaKerja");
                continue;
            }

            // Simpan ke riwayat
            DB::table('t_gapok')->insert([
                'user_id' => $user->id,
                'gol_id' => $user->gol_id,
                'masa_kerja' => $newMasaKerja,
                'gapok' => $gapokBaru->nominal_gapok,
                'gapok_lama' => $gapokLama?->nominal_gapok ?? 0,
                'jenis_kenaikan' => 'berkala',
                'status' => true,
                'tanggal_kenaikan' => $today->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->masa_kerja = $newMasaKerja;
            $user->save();

            // ✅ Kirim notifikasi berhasil
            $pesanNaik = "Selamat! Gaji Anda telah <span class='text-green-600 font-bold'>naik secara berkala</span>.<br>" .
                "Gaji sebelumnya: <strong>Rp " . number_format($gapokLama?->nominal_gapok ?? 0, 0, ',', '.') . "</strong><br>" .
                "Gaji baru: <strong>Rp " . number_format($gapokBaru->nominal_gapok, 0, ',', '.') . "</strong>";

            Notification::send($user, new UserNotification($pesanNaik, null));

            $this->info("✔ {$user->name} naik gaji berkala → Rp" . number_format($gapokBaru->nominal_gapok, 0, ',', '.') . " (MK: $newMasaKerja)");
            Log::channel('kenaikan_berkala')->info("✔ {$user->name} naik berkala → Rp" . number_format($gapokBaru->nominal_gapok, 0, ',', '.') . " | MK: $newMasaKerja | UID: {$user->id}");
            $updated++;
        }

        $this->info("🎯 Total user yang naik gaji berkala: $updated");
        Log::channel('kenaikan_berkala')->info("Total kenaikan berkala: $updated pada {$today->toDateString()}");
    }

    protected function kenaikanBerkalaDitunda(User $user, Carbon $today): bool
    {
        return PeringatanKaryawan::where('user_id', $user->id)
            ->whereIn('tingkat', ['II', 'III']) // SP 1 dan SP 2
            ->where('tanggal_sp', '>=', $today->copy()->subYears(2))
            ->exists();
    }
}
