<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\MasterGapok;
use App\Models\MasterGolongan;
use Illuminate\Console\Command;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

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

        $unitKepegawaianId = 87;
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();

        $users = User::whereNotNull('tmt')
            ->whereNotNull('gol_id')
            ->with(['pendidikanUser', 'golongan'])
            ->get();

        foreach ($users as $user) {
            if (!$user->pendidikanUser) {
                $this->warn("⚠ {$user->name} tidak memiliki data pendidikan.");
                continue;
            }

            $tmt = Carbon::parse($user->tmt);
            $masaKerja = $tmt->diffInYears(Carbon::today());

            $jumlahNaik = $testMode ? 1 : min(4, floor($masaKerja / 4));

            if ($jumlahNaik < 1) {
                $this->line("⏭ {$user->name} belum cukup masa kerja.");
                continue;
            }

            // Hitung golongan berikutnya
            $nextGolongan = MasterGolongan::where('id', '>', $user->gol_id)
                ->where('id', '<=', $user->pendidikanUser->maxim_gol)
                ->orderBy('id')
                ->first();

            if (!$nextGolongan || $nextGolongan->id === $user->gol_id) {
                $this->line("⏭ {$user->name} sudah di golongan tertinggi.");

                Notification::send($user, new UserNotification(
                    'Anda saat ini berada di <strong>golongan tertinggi</strong> sesuai pendidikan Anda. Tidak ada kenaikan golongan lebih lanjut yang tersedia.',
                    null
                ));

                Log::channel('kenaikan_golongan')->info("⏹ {$user->name} sudah di golongan maksimal | UserID: {$user->id}");
                continue;
            }

            // Hitung total penundaan karena SP2 (sanksi = 2)
            $penundaanTahun = $this->hitungTahunPenundaan($user);
            $tahunKenaikan = ($jumlahNaik * 4) + $penundaanTahun;
            $tanggalKenaikan = $tmt->copy()->addYears($tahunKenaikan);
            $statusNaik = $penundaanTahun === 0;

            if ($penundaanTahun > 0) {
                $jumlahSP2 = $penundaanTahun / 4;
                $tundaHingga = $tanggalKenaikan; // Sudah diset sebelumnya
                $tanggalFormatted = $tundaHingga->translatedFormat('d F Y');

                // Log ke console
                $this->warn("⏸ {$user->name} ditunda kenaikan golongan karena ada {$jumlahSP2} SP2 (tingkat III) dalam 4 tahun. Kenaikan ditunda hingga {$tanggalFormatted}.");

                // Kirim notifikasi ke user
                $pesanTunda = "Kenaikan golongan Anda <strong>ditunda</strong> karena adanya <strong>{$jumlahSP2} Surat Peringatan Tingkat III (SP2)</strong> " .
                    "dalam 4 tahun terakhir.<br>Kenaikan ditunda hingga <strong>{$tanggalFormatted}</strong>.";

                Notification::send($user, new UserNotification($pesanTunda, null));
            }

            // Jika sudah pernah diajukan
            $sudahAda = DB::table('t_gapok')->where([
                ['user_id', '=', $user->id],
                ['jenis_kenaikan', '=', 'golongan'],
                ['gol_id_baru', '=', $nextGolongan->id],
            ])->exists();

            if ($sudahAda && !$testMode) {
                $this->line("⏭ {$user->name} sudah diajukan sebelumnya.");
                continue;
            }
            $gapokBaru = MasterGapok::where('gol_id', $nextGolongan->id)
                ->where('masa_kerja', '<=', $masaKerja)
                ->orderByDesc('masa_kerja')
                ->first();

            // Gapok lama
            $gapokLama = MasterGapok::where('gol_id', $user->gol_id)
                ->where('masa_kerja', '<=', $masaKerja)
                ->orderByDesc('masa_kerja')
                ->first();

            $gapokLamaNominal = $gapokLama?->nominal_gapok ?? 0;
            $gapokBaruNominal = $gapokBaru?->nominal_gapok ?? 0;

            // Simpan ke t_gapok
            DB::table('t_gapok')->insert([
                'user_id' => $user->id,
                'gol_id' => $user->gol_id,
                'gol_id_baru' => $nextGolongan->id,
                'masa_kerja' => $masaKerja,
                'gapok' => $gapokBaruNominal,
                'gapok_lama' => $gapokLamaNominal,
                'jenis_kenaikan' => 'golongan',
                'tanggal_kenaikan' => $tanggalKenaikan->toDateString(),
                'status' => $statusNaik,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // Notifikasi ke kepegawaian
            // $notifMsg = 'Pengajuan Kenaikan Golongan ' . $user->name .
            //     ' dari gol <span class="font-bold">' . ($user->golongan?->nama ?? '-') .
            //     ' ke ' . $nextGolongan->nama .
            //     '</span> di tanggal ' . $tanggalKenaikan->translatedFormat('d F Y') . ' membutuhkan persetujuan Anda.';

            if ($statusNaik) {
                $user->gol_id = $nextGolongan->id;
                $user->save();

                $logMsg = "✔ {$user->name} naik golongan ke {$nextGolongan->nama} (ID: {$nextGolongan->id}) pada {$tanggalKenaikan->format('d-m-Y')}";
                $this->info($logMsg);
                Log::channel('kenaikan_golongan')->info($logMsg);

                Notification::send($user, new UserNotification(
                    'Selamat! Anda <strong>naik golongan</strong> dari <strong>' . ($user->golongan?->nama ?? '-') .
                        '</strong> ke <strong>' . $nextGolongan->nama . '</strong>.' .
                        '<br><span class="text-sm text-gray-700">Gaji lama: Rp' . number_format($gapokLamaNominal, 0, ',', '.') .
                        '<br>Gaji baru: Rp' . number_format($gapokBaruNominal, 0, ',', '.') . '</span>',
                    null
                ));
            }

            $updated++;
        }

        $this->info("🎯 Total kenaikan golongan: $updated");
        Log::channel('kenaikan_golongan')->info("Total: $updated pada " . now()->toDateString());
    }


    protected function hitungTahunPenundaan(User $user): int
    {
        $sp2Count = PeringatanKaryawan::where('user_id', $user->id)
            ->where('sanksi', 2)
            ->where('tanggal_sp', '>=', now()->subYears(4))
            ->count();

        return $sp2Count * 4; // setiap SP2 menambah 4 tahun penundaan
    }
}
