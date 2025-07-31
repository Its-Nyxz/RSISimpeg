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
        $master_potongan = [
            ['katjab_id' => 1, 'nama' => 'Dokter Spesialis', 'nominal' => 25000, 'deskripsi' => 'Potongan untuk Dokter Spesialis'],
            ['katjab_id' => 2, 'nama' => 'Dokter Umum', 'nominal' => 20000, 'deskripsi' => 'Potongan untuk Dokter Umum'],
            ['katjab_id' => 3, 'nama' => 'Dokter Gigi', 'nominal' => 10000, 'deskripsi' => 'Potongan untuk Dokter Gigi'],
            ['katjab_id' => 6, 'nama' => 'IPCN', 'nominal' => 20000, 'deskripsi' => 'Potongan untuk IPCN'],
            ['katjab_id' => 7, 'nama' => 'Ners', 'nominal' => 10000, 'deskripsi' => 'Potongan untuk Ners'],
            ['katjab_id' => 10, 'nama' => 'Perawat Diploma', 'nominal' => 12500, 'deskripsi' => 'Potongan untuk Perawat Diploma'],
            ['katjab_id' => 9, 'nama' => 'Penata Anestesi', 'nominal' => 8500, 'deskripsi' => 'Potongan untuk Penata Anestesi'],
            ['katjab_id' => 10, 'nama' => 'Perawat Medik', 'nominal' => 8000, 'deskripsi' => 'Potongan untuk Perawat Medik'],
            ['katjab_id' => 11, 'nama' => 'Bidan', 'nominal' => 5000, 'deskripsi' => 'Potongan untuk Bidan'],
        ];

        foreach ($master_potongan as $potongan) {
            MasterPotongan::create($potongan);
        }
    }
}
