<?php

namespace Database\Seeders;

use App\Models\MasterKhusus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterKhususSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tunjanganKhusus = [
            [
                'nama' => 'Dokter Spesialis dengan Surat Pengangkatan Karyawan tetap sebelum tahun 2019',
                'nominal' => 20000000,
                'deskripsi' => 'Tunjangan untuk dokter spesialis dengan surat pengangkatan sebelum tahun 2019',
            ],
            [
                'nama' => 'Dokter Spesialis dengan Surat Pengangkatan Karyawan tetap periode tahun 2019 - 2023',
                'nominal' => 10000000,
                'deskripsi' => 'Tunjangan untuk dokter spesialis periode tahun 2019 - 2023',
            ],
            [
                'nama' => 'Dokter Umum dengan Surat Pengangkatan Karyawan tetap sebelum tahun 2016',
                'nominal' => 15000000,
                'deskripsi' => 'Tunjangan untuk dokter umum dengan surat pengangkatan sebelum tahun 2016',
            ],
            [
                'nama' => 'Dokter Umum dengan Surat Pengangkatan Karyawan tetap periode tahun 2016 - 2018',
                'nominal' => 10000000,
                'deskripsi' => 'Tunjangan untuk dokter umum periode tahun 2016 - 2018',
            ],
            [
                'nama' => 'Dokter Umum dengan Surat Pengangkatan Karyawan tetap periode tahun 2019 - 2022',
                'nominal' => 2500000,
                'deskripsi' => 'Tunjangan untuk dokter umum periode tahun 2019 - 2022',
            ],
            [
                'nama' => 'Dokter Gigi dengan Surat Pengangkatan Karyawan tetap sebelum tahun 2023',
                'nominal' => 2000000,
                'deskripsi' => 'Tunjangan untuk dokter gigi sebelum tahun 2023',
            ],
            [
                'nama' => 'Dokter penanggungjawab klaim asuransi rumah sakit',
                'nominal' => 2200000,
                'deskripsi' => 'Tunjangan untuk dokter penanggungjawab klaim asuransi rumah sakit',
            ],
            [
                'nama' => 'Tenaga Kesehatan yang memberikan lebih dari 1 (satu) STR untuk kepentingan Rumah Sakit',
                'nominal' => 500000,
                'deskripsi' => 'Tunjangan untuk tenaga kesehatan dengan lebih dari satu STR',
            ],
        ];

        foreach ($tunjanganKhusus as $tunjangan) {
            MasterKhusus::create($tunjangan);
        }
    }
}
