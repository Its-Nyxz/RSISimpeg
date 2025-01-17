<?php

namespace Database\Seeders;

use App\Models\ShiftPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shift = [
            ['nama' => '1 Shift', 'point' => 1],
            ['nama' => '2 Shift', 'point' => 2],
            ['nama' => '3 Shift', 'point' => 3],
        ];

        foreach ($shift as $data) {
            ShiftPoint::create($data);
        }
    }
}
