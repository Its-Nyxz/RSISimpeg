<?php

namespace Database\Seeders;

use App\Models\MasterFungsi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterFungsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fungsionals = [
            ['nama' => 'Dokter Spesialis', 'nominal' => 2000000, 'deskripsi' => 'Tunjangan untuk Dokter Spesialis'],
            ['nama' => 'Dokter Umum', 'nominal' => 1500000, 'deskripsi' => 'Tunjangan untuk Dokter Umum'],
            ['nama' => 'Dokter Gigi', 'nominal' => 1300000, 'deskripsi' => 'Tunjangan untuk Dokter Gigi'],
            ['nama' => 'Apoteker', 'nominal' => 1500000, 'deskripsi' => 'Tunjangan untuk Apoteker'],
            ['nama' => 'Ners', 'nominal' => 1000000, 'deskripsi' => 'Tunjangan untuk Ners'],
            ['nama' => 'Psikologi Klinik', 'nominal' => 1000000, 'deskripsi' => 'Tunjangan untuk Psikologi Klinik'],
            ['nama' => 'Penata Anestesi', 'nominal' => 900000, 'deskripsi' => 'Tunjangan untuk Penata Anestesi'],
            ['nama' => 'Perawat Diploma', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Perawat Diploma'],
            ['nama' => 'Bidan', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Bidan'],
            ['nama' => 'Analis Kesehatan', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Analis Kesehatan'],
            ['nama' => 'Radiografer', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Radiografer'],
            ['nama' => 'Fisioterapi', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Fisioterapi'],
            ['nama' => 'Tenaga Informasi Teknologi/ IT', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Tenaga Informasi Teknologi/ IT'],
            ['nama' => 'Ahli Gizi/ Dietisien', 'nominal' => 500000, 'deskripsi' => 'Tunjangan untuk Ahli Gizi/ Dietisien'],
            ['nama' => 'Tenaga Teknis Kefarmasian (TTK)', 'nominal' => 600000, 'deskripsi' => 'Tunjangan untuk Tenaga Teknis Kefarmasian (TTK)'],
            ['nama' => 'Kesehatan Lingkungan/ Sanitarian', 'nominal' => 600000, 'deskripsi' => 'Tunjangan untuk Kesehatan Lingkungan/ Sanitarian'],
            ['nama' => 'Perekam Medik', 'nominal' => 500000, 'deskripsi' => 'Tunjangan untuk Perekam Medik'],
            ['nama' => 'Elektromedik', 'nominal' => 600000, 'deskripsi' => 'Tunjangan untuk Elektromedik'],
            ['nama' => 'Terapi Wicara, Okupasi Terapi', 'nominal' => 700000, 'deskripsi' => 'Tunjangan untuk Terapi Wicara, Okupasi Terapi'],
        ];

        foreach ($fungsionals as $fungsional) {
            MasterFungsi::create($fungsional);
        }
    }
}
