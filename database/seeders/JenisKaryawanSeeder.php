<?php

namespace Database\Seeders;

use App\Models\JenisKaryawan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisdata = [
            ['nama' => 'Tetap'],
            ['nama' => 'Part Time'],
            ['nama' => 'Kontrak'],
            ['nama' => 'Magang'],
        ];

        foreach ($jenisdata as $data) {
            JenisKaryawan::create($data);
        }
    }
}
