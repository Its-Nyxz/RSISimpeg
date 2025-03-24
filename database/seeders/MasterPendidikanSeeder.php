<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\MasterPendidikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikans = [
            ['nama' => 'SD', 'deskripsi' => 'Sekolah Dasar', 'minim_gol' => 1, 'maxim_gol' => 5],
            ['nama' => 'SLTP', 'deskripsi' => 'Sekolah Menengah Pertama (SMP)', 'minim_gol' => 3, 'maxim_gol' => 7],
            ['nama' => 'SLTA', 'deskripsi' => 'Sekolah Menengah Atas (SMA)', 'minim_gol' => 5, 'maxim_gol' => 9],
            ['nama' => 'Kejuruan', 'deskripsi' => 'Pendidikan Menengah Kejuruan', 'minim_gol' => 6, 'maxim_gol' => 10],
            ['nama' => 'DIII', 'deskripsi' => 'Diploma Tingkat III (D3)', 'minim_gol' => 7, 'maxim_gol' => 11],
            ['nama' => 'DIV', 'deskripsi' => 'Diploma Tingkat IV (D4)', 'minim_gol' => 8, 'maxim_gol' => 12],
            ['nama' => 'S1 - Umum', 'deskripsi' => 'Sarjana (S1) Umum', 'minim_gol' => 9, 'maxim_gol' => 12],
            ['nama' => 'S1 - Apoteker', 'deskripsi' => 'Sarjana (S1) Farmasi - Apoteker', 'minim_gol' => 10, 'maxim_gol' => 13],
            ['nama' => 'S1 - Nurse', 'deskripsi' => 'Sarjana (S1) Keperawatan', 'minim_gol' => 10, 'maxim_gol' => 13],
            ['nama' => 'S1 - Dokter', 'deskripsi' => 'Sarjana (S1) Kedokteran', 'minim_gol' => 10, 'maxim_gol' => 13],
            ['nama' => 'S1 - Informatika', 'deskripsi' => 'Sarjana (S1) Teknik Informatika', 'minim_gol' => 10, 'maxim_gol' => 13],
            ['nama' => 'S2 - Umum', 'deskripsi' => 'Magister (S2) Umum', 'minim_gol' => 11, 'maxim_gol' => 15],
            ['nama' => 'S2 - Spesialis', 'deskripsi' => 'Magister (S2) Spesialis', 'minim_gol' => 12, 'maxim_gol' => 16],
            ['nama' => 'S2 - Direktur', 'deskripsi' => 'Magister (S2) Manajemen - Direktur', 'minim_gol' => 13, 'maxim_gol' => 17],
        ];


        foreach ($pendidikans as $pendidikan) {
            MasterPendidikan::create($pendidikan);
        }
    }
}
