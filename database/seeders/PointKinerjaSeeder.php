<?php

namespace Database\Seeders;

use App\Models\PointKinerja;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PointKinerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Istimewa (91-100)', 'batas_bawah' => 91, 'batas_atas' => 100, 'point' => 6],
            ['nama' => 'Sangat Baik (81-90)', 'batas_bawah' => 81, 'batas_atas' => 90, 'point' => 5],
            ['nama' => 'Baik (71-80)', 'batas_bawah' => 71, 'batas_atas' => 80, 'point' => 4],
            ['nama' => 'Cukup (61-70)', 'batas_bawah' => 61, 'batas_atas' => 70, 'point' => 3],
            ['nama' => 'Buruk (51-60)', 'batas_bawah' => 51, 'batas_atas' => 60, 'point' => 2],
            ['nama' => 'Sangat Buruk (<50)', 'batas_bawah' => 0, 'batas_atas' => 50, 'point' => 1],
        ];

        foreach ($data as $item) {
            PointKinerja::create($item);
        }
    }
}
