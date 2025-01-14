<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            ['name' => 'Morning Shift', 'start_time' => '08:00:00', 'end_time' => '16:00:00'],
            ['name' => 'Evening Shift', 'start_time' => '16:00:00', 'end_time' => '00:00:00'],
            ['name' => 'Night Shift', 'start_time' => '00:00:00', 'end_time' => '08:00:00'],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}