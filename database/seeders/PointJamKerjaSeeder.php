<?php

namespace Database\Seeders;

use App\Models\PointJamKerja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointJamKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => '0 - 40 Jam', 'batas_bawah' => 0, 'batas_atas' => 40, 'point' => 1],
            ['nama' => '40,1 - 80 Jam', 'batas_bawah' => 40.1, 'batas_atas' => 80, 'point' => 2],
            ['nama' => '80,1 - 120 Jam', 'batas_bawah' => 80.1, 'batas_atas' => 120, 'point' => 3],
            ['nama' => '120,1 - 160 Jam', 'batas_bawah' => 120.1, 'batas_atas' => 160, 'point' => 4],
            ['nama' => '160,1 - 200 Jam', 'batas_bawah' => 160.1, 'batas_atas' => 200, 'point' => 5],
        ];

        foreach ($data as $item) {
            PointJamKerja::create($item);
        }
    }
}
