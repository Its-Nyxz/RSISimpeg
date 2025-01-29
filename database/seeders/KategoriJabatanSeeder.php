<?php

namespace Database\Seeders;

use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Direktur', 'tunjangan' => 'jabatan'],
            ['nama' => 'Wadir Pelayanan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Pelayanan Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Wadir Umum dan Keuangan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Pel. Medik Bedah, Intensif, HD, MP, Rehab Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Keperawatan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Penunjang', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Humas dan Program RS', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer SDM', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Keuangan+ Plt. Ka. Seksi Akuntansi', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Pel Medik Rajal, Gadar Ranap', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Keperawatan Rajal, Ranap, Gadar', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka Seksi Keperawatan Bedah, Intensif, HD, MP, Rehabilitasi Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Penunjang Medik+ Plt. Instalasi Gas Medik dan Alkes', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Penunjang Non Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Hukum dan Kerjasama', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Manajemen Informasi dan Pelaporan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Kepegawaian', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Pendidikan, Pelatihan dan Pengembangan SDM + Plt. Ka. Seksi Kajian dan Budaya Islam', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Keuangan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Aset dan Logistik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Saraf', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Anak', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Umum Fungsional', 'tunjangan' => 'fungsi'],
            ['nama' => 'Dokter Umum Fungsional + PJ Klaim', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Instalasi Farmasi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Apoteker', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Seksi Perencanaan dan Pengembangan', 'tunjangan' => 'fungsi'],
            ['nama' => 'Dokter Gigi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu IBS', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu IGD', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Instalasi Dialisis', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu ICU', 'tunjangan' => 'fungsi'],
            ['nama' => 'Staf Manajer Pelayanan Medik', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Manajer Keperawatan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Manajer Penunjang', 'tunjangan' => 'umum'],
            ['nama' => 'Supervisor Instalasi Dialisis', 'tunjangan' => 'umum'],
            ['nama' => 'Pekarya Kesehatan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Administrasi IBS', 'tunjangan' => 'umum'],
            ['nama' => 'Cleaning Service', 'tunjangan' => 'umum'],
        ];

        foreach ($data as $item) {
            KategoriJabatan::create($item);
        }
    }
}
