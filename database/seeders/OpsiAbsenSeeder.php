<?php

namespace Database\Seeders;

use App\Models\OpsiAbsen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpsiAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $master_opsiabsen = [
            ['name' => 'Masuk'],
            ['name' => 'Pulang'],
            ['name' => 'Masuk Lembur'],
            ['name' => 'Pulang Lembur'],
        ];

        foreach ($master_opsiabsen as $optiabsen) {
            OpsiAbsen::create($optiabsen);
        }
    }
}
