<?php

namespace Database\Seeders;

use App\Models\LevelPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelpointData = [
            ['nama' => 'Level I', 'point' => 25],
            ['nama' => 'Level II', 'point' => 12],
            ['nama' => 'Level III', 'point' => 8],
            ['nama' => 'Level IV', 'point' => 7],
            ['nama' => 'Level V', 'point' => 6],
            ['nama' => 'Level VI', 'point' => 5],
            ['nama' => 'Level VII', 'point' => 4],
            ['nama' => 'Level VIII', 'point' => 3],
            ['nama' => 'Level IX', 'point' => 2.5],
            ['nama' => 'Level X', 'point' => 2],
            ['nama' => 'Level XI', 'point' => 1],
        ];

        foreach ($levelpointData as $data) {
            LevelPoint::create($data);
        }
    }
}
