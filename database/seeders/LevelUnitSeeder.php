<?php

namespace Database\Seeders;

use App\Models\LevelUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelunitData = [
            ['unit_id' => 14, 'level_id' => 1],
            ['unit_id' => 15, 'level_id' => 2],
            ['unit_id' => 16, 'level_id' => 3],
            ['unit_id' => 17, 'level_id' => 3],
            ['unit_id' => 2, 'level_id' => 3],
            ['unit_id' => 19, 'level_id' => 3],
            ['unit_id' => 23, 'level_id' => 3],
            ['unit_id' => 24, 'level_id' => 3],
            ['unit_id' => 46, 'level_id' => 4],
            ['unit_id' => 3, 'level_id' => 5],
            ['unit_id' => 22, 'level_id' => 5],
            ['unit_id' => 35, 'level_id' => 6],
            ['unit_id' => 36, 'level_id' => 6],
            ['unit_id' => 37, 'level_id' => 6],
            ['unit_id' => 39, 'level_id' => 6],
            ['unit_id' => 45, 'level_id' => 6],
            ['unit_id' => 55, 'level_id' => 6],
            ['unit_id' => 20, 'level_id' => 7],
            ['unit_id' => 30, 'level_id' => 7],
            ['unit_id' => 34, 'level_id' => 7],
            ['unit_id' => 50, 'level_id' => 7],
            ['unit_id' => 44, 'level_id' => 7],
            ['unit_id' => 18, 'level_id' => 8],
            ['unit_id' => 1, 'level_id' => 8],
            ['unit_id' => 5, 'level_id' => 8],
            ['unit_id' => 4, 'level_id' => 8],
            ['unit_id' => 8, 'level_id' => 8],
            ['unit_id' => 10, 'level_id' => 8],
            ['unit_id' => 11, 'level_id' => 8],
            ['unit_id' => 12, 'level_id' => 8],
            ['unit_id' => 21, 'level_id' => 8],
            ['unit_id' => 25, 'level_id' => 8],
            ['unit_id' => 31, 'level_id' => 8],
            ['unit_id' => 32, 'level_id' => 8],
            ['unit_id' => 38, 'level_id' => 8],
            ['unit_id' => 40, 'level_id' => 8],
            ['unit_id' => 56, 'level_id' => 8],
            ['unit_id' => 41, 'level_id' => 8],
            ['unit_id' => 42, 'level_id' => 8],
            ['unit_id' => 43, 'level_id' => 8],
            ['unit_id' => 6, 'level_id' => 9],
            ['unit_id' => 9, 'level_id' => 9],
            ['unit_id' => 13, 'level_id' => 9],
            ['unit_id' => 7, 'level_id' => 10],
            ['unit_id' => 13, 'level_id' => 10],
            ['unit_id' => 26, 'level_id' => 10],
            ['unit_id' => 27, 'level_id' => 10],
            ['unit_id' => 28, 'level_id' => 10],
            ['unit_id' => 33, 'level_id' => 10],
            ['unit_id' => 47, 'level_id' => 10],
            ['unit_id' => 48, 'level_id' => 11],
            ['unit_id' => 49, 'level_id' => 11],
            ['unit_id' => 29, 'level_id' => 11],
        ];

        foreach ($levelunitData as $data) {
            LevelUnit::create($data);
        }
    }
}
