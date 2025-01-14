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
            ['nama' => 'Kontrak', 'point' => 1],
            ['nama' => 'Masa Kerja 0-4 tahun', 'point' => 2],
            ['nama' => 'Masa Kerja 5-8 tahun', 'point' => 3],
            ['nama' => 'Masa Kerja 9-12 tahun', 'point' => 4],
            ['nama' => 'Masa Kerja 13-16 tahun', 'point' => 5],
            ['nama' => 'Masa Kerja 17-20 tahun', 'point' => 6],
            ['nama' => 'Masa Kerja 21-24 tahun', 'point' => 7],
            ['nama' => 'Masa Kerja ≥25 tahun', 'point' => 8],
        ];

        foreach ($masaKerjaData as $data) {
            MasaKerja::create($data);
        }
    }
}
