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
            ['nama' => 'Ka. Instalasi CSSD', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Instalasi CSSD'],
            ['nama' => 'Ka. Instalasi Pemeliharaan Sarpras', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Instalasi Pemeliharaan Sarpras'],
            ['nama' => 'Ka. Unit Ambulance', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Ambulance'],
            ['nama' => 'Ka. Unit PJBR', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit PJBR'],
            ['nama' => 'Ka. Unit Pengelolaan Linen', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Pengelolaan Linen'],
            ['nama' => 'Ka. Unit Gudang', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Gudang'],
            ['nama' => 'Ka. Unit Pengamanan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Pengamanan'],
            ['nama' => 'Ka. Unit Transportasi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Transportasi'],
            ['nama' => 'Ka. Unit Pemasaran', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Unit Pemasaran'],
            ['nama' => 'Ka. Instalasi Teknologi Informasi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Ka. Instalasi Teknologi Informasi'],
            ['nama' => 'Komite Full Time', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Komite Full Time'],
            ['nama' => 'SPI', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk SPI'],
            ['nama' => 'Supervisor', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Supervisor'],
            ['nama' => 'Staf Humas dan Program RS', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Humas dan Program RS'],
            ['nama' => 'Staf SDM', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf SDM'],
            ['nama' => 'Staf Akuntansi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Akuntansi'],
            ['nama' => 'Staf Keuangan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Keuangan'],
            ['nama' => 'Staf Asuransi', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Asuransi'],
            ['nama' => 'Staf Aset dan Logistik', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Aset dan Logistik'],
            ['nama' => 'Staf Pelayanan Medik', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Pelayanan Medik'],
            ['nama' => 'Staf Keperawatan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Keperawatan'],
            ['nama' => 'Staf Penunjang', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Penunjang'],
            ['nama' => 'Staf Unit Pemasaran', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Unit Pemasaran'],
            ['nama' => 'Staf Anggota SPI', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Anggota SPI'],
            ['nama' => 'Staf Administrasi IBS', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Administrasi IBS'],
            ['nama' => 'Staf Administrasi IRJ', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Administrasi IRJ'],
            ['nama' => 'Pekarya Kesehatan', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Pekarya Kesehatan'],
            ['nama' => 'Staf Instalasi Rekam Medik', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Instalasi Rekam Medik'],
            ['nama' => 'Tenaga Non Kefarmasian', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Tenaga Non Kefarmasian'],
            ['nama' => 'Staf Administrasi Inst Laboratorium', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Staf Administrasi Inst Laboratorium'],
            ['nama' => 'Pelaksana IPAL', 'nominal' => 350000, 'deskripsi' => 'Gaji untuk Pelaksana IPAL'],
            ['nama' => 'Cleaning Service', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Cleaning Service'],
            ['nama' => 'Staf Instalasi Gas Medik', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Instalasi Gas Medik'],
            ['nama' => 'Staf Unit Ambulance', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Unit Ambulance'],
            ['nama' => 'Staf Instalasi Gizi', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Instalasi Gizi'],
            ['nama' => 'Staf Unit Pengamanan', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Unit Pengamanan'],
            ['nama' => 'Staf Unit Transportasi', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Unit Transportasi'],
            ['nama' => 'Staf Instalasi Teknologi Informasi', 'nominal' => 250000, 'deskripsi' => 'Gaji untuk Staf Instalasi Teknologi Informasi'],
        ];

        foreach ($umums as $umum) {
            MasterUmum::create($umum);
        }
    }
}
