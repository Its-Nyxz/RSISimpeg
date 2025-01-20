<?php

namespace Database\Seeders;

use App\Models\PointPelatihan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelatihan = [
            ['nama' => 'Pelatihan Dasar', 'point' => 1],
            ['nama' => 'Pelatihan Menengah', 'point' => 2],
            ['nama' => 'Pelatihan Lanjut', 'point' => 3],
        ];

        foreach ($pelatihan as $data) {
            PointPelatihan::create($data);
        }
    }
}
