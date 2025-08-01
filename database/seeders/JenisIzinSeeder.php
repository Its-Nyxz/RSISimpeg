<?php

namespace Database\Seeders;

use App\Models\JenisIzin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $izin = [
            ['nama_izin' => 'Izin Sakit', 'durasi_default' => 3, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_izin' => 'Izin Menjadi Petugas TKHI/PPIH', 'durasi_default' => 6, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_izin' => 'Izin Pernikahan', 'durasi_default' => 3, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_izin' => 'Izin Anak / Suami/ Istri Opname di Rumah Sakit', 'durasi_default' => 2, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_izin' => 'Izin Anggota Keluarga Meninggal', 'durasi_default' => 3, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_izin' => 'Izin Istri Karyawan Menjalani Persalinan', 'durasi_default' => 2, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_izin' => 'Izin Menghitankan Anak', 'durasi_default' => 2, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
        ];

        foreach ($izin as $item) {
            JenisIzin::create($item);
        }
    }
}
