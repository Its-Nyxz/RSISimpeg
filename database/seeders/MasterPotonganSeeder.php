<?php

namespace Database\Seeders;

use App\Models\MasterGolongan;
use App\Models\MasterPotongan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterPotonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'bpjs tenaga kerja', 'jenis' => 'persentase', 'is_wajib' => true],
            ['nama' => 'bpjs kesehatan', 'jenis' => 'persentase', 'is_wajib' => true],
            ['nama' => 'bpjs kesehatan ortu/tambahan', 'jenis' => 'persentase'],
            ['nama' => 'simpanan wajib', 'jenis' => 'nominal'],
            ['nama' => 'simpanan pokok', 'jenis' => 'nominal'],
            ['nama' => 'pph21', 'jenis' => 'nominal'],
            ['nama' => 'idi', 'jenis' => 'nominal'],
        ];

        foreach ($data as $item) {
            MasterPotongan::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
