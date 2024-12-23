<?php

namespace Database\Seeders;

use App\Models\MasterJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            ['nama' => 'Wadir', 'kualifikasi' => 'Strata 1', 'nominal' => 4000000, 'deskripsi' => 'Tunjangan untuk Wadir'],
            ['nama' => 'Manajer', 'kualifikasi' => 'Strata 1', 'nominal' => 3000000, 'deskripsi' => 'Tunjangan untuk Manajer'],
            ['nama' => 'Kepala Seksi', 'kualifikasi' => 'Minimal DIII', 'nominal' => 2500000, 'deskripsi' => 'Tunjangan untuk Kepala Seksi'],
            ['nama' => 'Kepala Instalasi (Dokter)', 'kualifikasi' => 'Min DIII', 'nominal' => 2200000, 'deskripsi' => 'Tunjangan untuk Kepala Instalasi (Dokter)'],
        ];

        foreach ($jabatans as $jabatan) {
            MasterJabatan::create($jabatan);
        }
    }
}
