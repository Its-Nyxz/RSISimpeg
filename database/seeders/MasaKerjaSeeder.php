<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasaKerja;

class MasaKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masaKerjaData = [
            ['nama' => 'Kontrak', 'batas_bawah' => null, 'batas_atas' => null, 'point' => 1],
            ['nama' => 'Masa Kerja 0-4 Tahun', 'batas_bawah' => 0, 'batas_atas' => 4, 'point' => 2],
            ['nama' => 'Masa Kerja 5-8 Tahun', 'batas_bawah' => 5, 'batas_atas' => 8, 'point' => 3],
            ['nama' => 'Masa Kerja 9-12 Tahun', 'batas_bawah' => 9, 'batas_atas' => 12, 'point' => 4],
            ['nama' => 'Masa Kerja 13-16 Tahun', 'batas_bawah' => 13, 'batas_atas' => 16, 'point' => 5],
            ['nama' => 'Masa Kerja 17-20 Tahun', 'batas_bawah' => 17, 'batas_atas' => 20, 'point' => 6],
            ['nama' => 'Masa Kerja 21-24 Tahun', 'batas_bawah' => 21, 'batas_atas' => 24, 'point' => 7],
            ['nama' => 'Masa Kerja ≥25 Tahun', 'batas_bawah' => 25, 'batas_atas' => 100, 'point' => 8],
        ];

        foreach ($masaKerjaData as $data) {
            MasaKerja::create($data);
        }
    }
}
