<?php

namespace Database\Seeders;

use App\Models\Holidays;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Holidays::create([
            'date' => '2025-01-01',
            'description' => 'Tahun Baru',
        ]);

        Holidays::create([
            'date' => '2025-12-25',
            'description' => 'Natal',
        ]);
    }
}
