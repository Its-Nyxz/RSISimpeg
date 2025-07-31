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
            ['nama_shift' => 'Morning Shift', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'keterangan' => 'Shift Pagi','unit_id' => 1,],
            ['nama_shift' => 'Evening Shift', 'jam_masuk' => '16:00:00', 'jam_keluar' => '00:00:00', 'keterangan' => 'Shift Sore','unit_id' => 1,],
            ['nama_shift' => 'Night Shift', 'jam_masuk' => '00:00:00', 'jam_keluar' => '08:00:00', 'keterangan' => 'Shift Malam','unit_id' => 1,],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}