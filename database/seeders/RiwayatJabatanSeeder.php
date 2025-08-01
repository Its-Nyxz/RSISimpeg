<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\RiwayatJabatan;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RiwayatJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // === Jabatan Struktural ===
            if ($user->jabatan_id) {
                $kategori = KategoriJabatan::find($user->jabatan_id);

                if ($kategori) {
                    RiwayatJabatan::create([
                        'user_id' => $user->id,
                        'kategori_jabatan_id' => $kategori->id,
                        'tunjangan' => $kategori->tunjangan ?? 'jabatan',
                        'tanggal_mulai' => now()->subMonths(rand(1, 24))->startOfMonth(),
                        'tanggal_selesai' => null,
                    ]);
                }
            }

            // === Jabatan Fungsional (jika ada dan berbeda dari jabatan_id) ===
            if ($user->fungsi_id && $user->fungsi_id !== $user->jabatan_id) {
                $kategoriFungsi = KategoriJabatan::find($user->fungsi_id);

                if ($kategoriFungsi) {
                    RiwayatJabatan::create([
                        'user_id' => $user->id,
                        'kategori_jabatan_id' => $kategoriFungsi->id,
                        'tunjangan' => $kategoriFungsi->tunjangan ?? 'fungsi',
                        'tanggal_mulai' => now()->subMonths(rand(1, 24))->startOfMonth(),
                        'tanggal_selesai' => null,
                    ]);
                }
            }
        }
    }
}
