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
            ['fungsi_id' => 1, 'nama' => 'Dokter Spesialis', 'nominal' => 25000, 'deskripsi' => 'Potongan untuk Dokter Spesialis'],
            ['fungsi_id' => 2, 'nama' => 'Dokter Umum', 'nominal' => 20000, 'deskripsi' => 'Potongan untuk Dokter Umum'],
            ['fungsi_id' => 3, 'nama' => 'Dokter Gigi', 'nominal' => 10000, 'deskripsi' => 'Potongan untuk Dokter Gigi'],
            ['fungsi_id' => 6, 'nama' => 'IPCN', 'nominal' => 20000, 'deskripsi' => 'Potongan untuk IPCN'],
            ['fungsi_id' => 7, 'nama' => 'Ners', 'nominal' => 10000, 'deskripsi' => 'Potongan untuk Ners'],
            ['fungsi_id' => 10, 'nama' => 'Perawat Diploma', 'nominal' => 12500, 'deskripsi' => 'Potongan untuk Perawat Diploma'],
            ['fungsi_id' => 9, 'nama' => 'Penata Anestesi', 'nominal' => 8500, 'deskripsi' => 'Potongan untuk Penata Anestesi'],
            ['fungsi_id' => 10, 'nama' => 'Perawat Medik', 'nominal' => 8000, 'deskripsi' => 'Potongan untuk Perawat Medik'],
            ['fungsi_id' => 11, 'nama' => 'Bidan', 'nominal' => 5000, 'deskripsi' => 'Potongan untuk Bidan'],
        ];

        foreach ($master_potongan as $potongan) {
            MasterPotongan::create($potongan);
        }
    }
}
