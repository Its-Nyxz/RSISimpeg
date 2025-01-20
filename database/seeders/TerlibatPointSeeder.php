<?php

namespace Database\Seeders;

use App\Models\TerlibatPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TerlibatPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terlibat = [
            ['nama' => 'Terlibat 1-2 Tim Produktif/Aktif', 'point' => 1],
            ['nama' => 'Terlibat 3-4 Tim Produktif/Aktif', 'point' => 2],
            ['nama' => 'Terlibat 5-6 Tim Produktif/Aktif', 'point' => 3],
        ];

        foreach ($terlibat as $data) {
            TerlibatPoint::create($data);
        }
    }
}
