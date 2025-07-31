<?php

namespace Database\Seeders;

use App\Models\StatusAbsen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status_absen = [
            ['nama' => 'Tepat Waktu', 'keterangan' => 'Masuk masih dalam waktu toleransi'],
            ['nama' => 'Keterlambatan', 'keterangan' => 'Masuk lebih dari waktu toleransi'],
            ['nama' => 'Pulang Awal', 'keterangan' => 'Pulang sebelum jam yang ditentukan'],
            ['nama' => 'Tidak Absen', 'keterangan' => 'Tidak melakukan absensi sama sekali'],
        ];

        foreach ($status_absen as $status) {
            StatusAbsen::create($status);
        }
    }
}
