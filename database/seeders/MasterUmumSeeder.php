<?php

namespace Database\Seeders;

use App\Models\MasterUmum;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umums = [
            ['nama' => 'Ka. Instalasi CSSD', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Instalasi CSSD', 'parent_id' => null],
            ['nama' => 'Ka. Instalasi Pemeliharaan Sarpras', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Instalasi Pemeliharaan Sarpras', 'parent_id' => null],
            ['nama' => 'Ka. Unit Ambulance', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Ambulance', 'parent_id' => null],
            ['nama' => 'Ka. Unit PJBR', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit PJBR', 'parent_id' => null],
            ['nama' => 'Ka. Unit Pengelolaan Linen', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Pengelolaan Linen', 'parent_id' => null],
            ['nama' => 'Ka. Unit Gudang', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Gudang', 'parent_id' => null],
            ['nama' => 'Ka. Unit Pengamanan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Pengamanan', 'parent_id' => null],
            ['nama' => 'Ka. Unit Transportasi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Transportasi', 'parent_id' => null],
            ['nama' => 'Ka. Unit Pemasaran', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Pemasaran', 'parent_id' => null],
            ['nama' => 'Ka. Instalasi Teknologi Informasi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Instalasi Teknologi Informasi', 'parent_id' => null],
            ['nama' => 'Komite Full Time', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Komite Full Time', 'parent_id' => null],
            ['nama' => 'SPI', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk SPI', 'parent_id' => null],
            ['nama' => 'Supervisor', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Supervisor', 'parent_id' => null],
            ['nama' => 'Staf Humas dan Program RS', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Humas dan Program RS', 'parent_id' => null],
            ['nama' => 'Staf SDM', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf SDM', 'parent_id' => null],
            ['nama' => 'Staf Akuntansi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Akuntansi', 'parent_id' => null],
            ['nama' => 'Staf Keuangan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Keuangan', 'parent_id' => null],
            ['nama' => 'Staf Asuransi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Asuransi', 'parent_id' => null],
            ['nama' => 'Staf Aset dan Logistik', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Aset dan Logistik', 'parent_id' => null],
            ['nama' => 'Staf Pelayanan Medik', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Pelayanan Medik', 'parent_id' => null],
            ['nama' => 'Staf Keperawatan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Keperawatan', 'parent_id' => null],
            ['nama' => 'Staf Penunjang', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Penunjang', 'parent_id' => null],
            ['nama' => 'Staf Unit Pemasaran', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Unit Pemasaran', 'parent_id' => null],
            ['nama' => 'Staf Anggota SPI', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Anggota SPI', 'parent_id' => null],
            ['nama' => 'Staf Administrasi IBS', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Administrasi IBS', 'parent_id' => null],
            ['nama' => 'Staf Administrasi IRJ', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Administrasi IRJ', 'parent_id' => null],
            ['nama' => 'Pekarya Kesehatan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Pekarya Kesehatan', 'parent_id' => null],
            ['nama' => 'Staf Instalasi Rekam Medik', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Instalasi Rekam Medik', 'parent_id' => null],
            ['nama' => 'Tenaga Non Kefarmasian', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Tenaga Non Kefarmasian', 'parent_id' => null],
            ['nama' => 'Staf Administrasi Inst Laboratorium', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Administrasi Inst Laboratorium', 'parent_id' => null],
            ['nama' => 'Pelaksana IPAL', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Pelaksana IPAL', 'parent_id' => null],
            ['nama' => 'Cleaning Service', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Cleaning Service', 'parent_id' => null],
            ['nama' => 'Staf Instalasi Gas Medik', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Instalasi Gas Medik', 'parent_id' => null],
            ['nama' => 'Staf Unit Ambulance', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Unit Ambulance', 'parent_id' => null],
            ['nama' => 'Staf Instalasi Gizi', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Instalasi Gizi', 'parent_id' => null],
            ['nama' => 'Staf Unit Pengamanan', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Unit Pengamanan', 'parent_id' => null],
            ['nama' => 'Staf Unit Transportasi', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Unit Transportasi', 'parent_id' => null],
            ['nama' => 'Staf Instalasi Teknologi Informasi', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Instalasi Teknologi Informasi', 'parent_id' => null],
        ];

        foreach ($umums as $umum) {
            MasterUmum::create($umum);
        }
    }
}
