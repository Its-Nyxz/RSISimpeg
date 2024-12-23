<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\MasterGolongan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterGolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golongans = [
            ['nama' => 'Ia'],
            ['nama' => 'Ib'],
            ['nama' => 'Ic'],
            ['nama' => 'Id'],
            ['nama' => 'IIa'],
            ['nama' => 'IIb'],
            ['nama' => 'IIc'],
            ['nama' => 'IId'],
            ['nama' => 'IIIa'],
            ['nama' => 'IIIb'],
            ['nama' => 'IIIc'],
            ['nama' => 'IIId'],
            ['nama' => 'IVa'],
            ['nama' => 'IVb'],
            ['nama' => 'IVc'],
            ['nama' => 'IVd'],
            ['nama' => 'IVe'],
        ];

        foreach ($golongans as $golongan) {
            MasterGolongan::create($golongan);
        }
    }
}
