<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterGapok;
use App\Models\GapokKontrak;
use Illuminate\Console\Command;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class UpdateKenaikanKontrak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kenaikan:kontrak {--test : Mode debug untuk simulasi kenaikan kontrak tanpa update DB}';
    protected $description = 'Cek dan proses kenaikan gaji kontrak tiap 1 tahun sekali';

    public function handle()
    {
        $updated = 0;
        $today = Carbon::today();
        $isTest = $this->option('test');

        $users = User::with(['pendidikanUser'])->where('jenis_id', 3)->whereNotNull('tmt')->get();

        foreach ($users as $user) {
            $tmt = Carbon::parse($user->tmt);
            $masaKerjaBulan = $tmt->diffInMonths($today);
            $masaKerjaBulanNaik = $masaKerjaBulan + 12; // 1 tahun berikutnya

            $kategoriJabatanId = $user->jabatan_id ?? $user->fungsi_id ?? $user->umum_id;
            $pendidikanId = $user->pendidikanUser?->id;

            if (!$kategoriJabatanId || !$pendidikanId) {
                $this->warn("⏸ {$user->name} abaikan: jabatan/pendidikan belum lengkap.");
                continue;
            }

            // Cek data kenaikan (1 tahun kemudian)
            $gapokNaik = GapokKontrak::where('kategori_jabatan_id', $kategoriJabatanId)
                ->where('pendidikan_id', $pendidikanId)
                ->where('min_masa_kerja', '<=', (int) $masaKerjaBulanNaik)
                ->where('max_masa_kerja', '>=', (int) $masaKerjaBulanNaik)
                ->first();

            if (!$gapokNaik) {
                $this->warn("⚠ {$user->name} tidak ditemukan gaji naik untuk MK (int) $masaKerjaBulanNaik bulan.");
                continue;
            }

            $kenaikanTanggal = $tmt->copy()->addMonths((int) $masaKerjaBulanNaik);
            $penyesuaian = $gapokNaik->penyesuaian()
                ->where('tanggal_berlaku', '<=', $kenaikanTanggal)
                ->orderByDesc('tanggal_berlaku')
                ->first();

            $nominalNaik = $penyesuaian?->nominal_baru ?? $gapokNaik->nominal;
            $nominalSekarang = $user->gapok ?? 0;

            if ($isTest) {
                $this->line("👀 [TEST] {$user->name} - Gaji Naik → Rp" . number_format($nominalNaik, 0, ',', '.'));
                continue;
            }

            // Simpan ke t_gapok
            DB::table('t_gapok')->insert([
                'user_id' => $user->id,
                'gol_id' => $user->gol_id ?? null,
                'masa_kerja' => (int) $masaKerjaBulanNaik,
                'gapok' => $nominalNaik,
                'gapok_lama' => $nominalSekarang,
                'jenis_kenaikan' => 'berkala',
                'status' => true,
                'tanggal_kenaikan' => $kenaikanTanggal->toDateString(),
                'catatan' => 'Naik Berkala Kontrak',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update gaji aktif user (jika disimpan di user)
            $user->masa_kerja = (int) $masaKerjaBulanNaik;
            $user->save();

            Notification::send($user, new UserNotification(
                "Selamat! Gaji Anda naik <b>berkala kontrak</b>.<br>Gaji baru: <b>Rp " . number_format($nominalNaik, 0, ',', '.') . "</b>",
                null
            ));

            $this->info("✔ {$user->name} gaji naik kontrak → Rp" . number_format($nominalNaik, 0, ',', '.'));
            Log::channel('kenaikan_kontrak')->info("✔ {$user->name} naik gaji kontrak Rp" . number_format($nominalNaik, 0, ',', '.'));
            $updated++;
        }

        $this->info("🎯 Total user yang naik gaji kontrak: $updated");
        Log::channel('kenaikan_kontrak')->info("Total kenaikan berkala kontrak: $updated");
    }
}
