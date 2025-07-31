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
            // Jabatan
            ['nama' => 'Direktur', 'tunjangan' => 'jabatan'],
            ['nama' => 'Wadir Pelayanan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Wadir Umum dan Keuangan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Keperawatan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Penunjang', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Humas dan Program RS', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer SDM', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Keuangan+ Plt. Ka. Seksi Akuntansi', 'tunjangan' => 'jabatan'],
            ['nama' => 'Manajer Pelayanan Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Pel Medik Rajal, Gadar Ranap', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Keperawatan Rajal, Ranap, Gadar', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Keperawatan Bedah, Intensif, HD, MP, Rehabilitasi Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Penunjang Medik+ Plt. Instalasi Gas Medik dan Alkes', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Penunjang Non Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Hukum dan Kerjasama', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Manajemen Informasi dan Pelaporan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Kepegawaian', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Pendidikan, Pelatihan dan Pengembangan SDM', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Keuangan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Aset dan Logistik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Pel. Medik Bedah, Intensif, HD, MP, Rehab Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Ka. Seksi Perencanaan dan Pengembangan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Kepala Instalasi Rawat Jalan', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Saraf', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Anak', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Orthopedi & Traumatologi', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Kulit dan Kelamin', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Obstetri dan Ginekologi', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Penyakit Dalam', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Bedah', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis THT-KL', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Jantung Pembuluh Darah', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Paru', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Kesehatan Jiwa', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Mikrobiologi Klinik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Neurologi', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Mata', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Bedah + Ka. Instalasi Bedah Sentral', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Anastesi + Ka. IAPI', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Rehabilitasi Medik + Ka. Instalasi Rehabilitasi Medik', 'tunjangan' => 'jabatan'],
            ['nama' => 'Dokter Spesialis Patologi Klinik+ Ka. Instalasi Labroratorium', 'tunjangan' => 'jabatan'],


            // Fungsi
            // Rp 1.500.000
            ['nama' => 'Dokter Umum Fungsional', 'tunjangan' => 'fungsi'],
            ['nama' => 'Dokter Umum Fungsional + PJ Klaim', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Instalasi Farmasi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Apoteker', 'tunjangan' => 'fungsi'],

            // Rp 1.300.000
            ['nama' => 'Dokter Gigi', 'tunjangan' => 'fungsi'],

            // Rp 1.000.000
            ['nama' => 'Karu IBS', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu IGD', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Instalasi Dialisis', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu ICU', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. IMP', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu At Taqwa', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Al Amin', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Haji', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Assyfa', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Al Munawaroh', 'tunjangan' => 'fungsi'],
            ['nama' => 'Koordinator MPP', 'tunjangan' => 'fungsi'],
            ['nama' => 'MPP', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Instalasi CSSD', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Unit MCU dan Poskes', 'tunjangan' => 'fungsi'],
            ['nama' => 'IPCN', 'tunjangan' => 'fungsi'],

            // Rp 900.000
            ['nama' => 'Perawat Anastesi', 'tunjangan' => 'fungsi'],

            // Rp 700.000
            ['nama' => 'Perawat Pelaksana', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Instalasi Rawat Jalan', 'tunjangan' => 'fungsi'],
            ['nama' => 'Perawat Gigi Pelaksana', 'tunjangan' => 'fungsi'],
            ['nama' => 'Bidan Pelaksana', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Al Zaitun', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu VK', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Perinatologi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Instalasi Rawat Inap', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Assalam', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Firdaus', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Aziziah', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Instalasi Rehabilitasi Medik', 'tunjangan' => 'fungsi'],
            ['nama' => 'Fisioterapis', 'tunjangan' => 'fungsi'],
            ['nama' => 'Okupasi Terapi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Pelaksana Masak', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Instalasi Radiologi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Radiografer', 'tunjangan' => 'fungsi'],
            ['nama' => 'Karu Instalasi Labroatorium', 'tunjangan' => 'fungsi'],
            ['nama' => 'Analis Kesehatan', 'tunjangan' => 'fungsi'],
            ['nama' => 'Teknisi Bank Darah', 'tunjangan' => 'fungsi'],
            ['nama' => 'Perawat Pelaksana Poskes dr. Nono', 'tunjangan' => 'fungsi'],

            // Rp 600.000
            ['nama' => 'Pelaksana Teknis Kefarmasian', 'tunjangan' => 'fungsi'],
            ['nama' => 'Tenaga Teknis Kefarmasian', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Instalasi Sanitasi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Elektromedis', 'tunjangan' => 'fungsi'],
            ['nama' => 'Staf Unit Pemasaran', 'tunjangan' => 'fungsi'],

            // Rp 500.000
            ['nama' => 'Ka. Instalasi Rekam Medik', 'tunjangan' => 'fungsi'],
            ['nama' => 'Staf Instalasi Rekam Medik', 'tunjangan' => 'fungsi'],
            ['nama' => 'Asembling', 'tunjangan' => 'fungsi'],
            ['nama' => 'Koding', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ka. Instalasi Gizi', 'tunjangan' => 'fungsi'],
            ['nama' => 'Ahli Gizi', 'tunjangan' => 'fungsi'],

            // Umum
            // Ketua dan Anggota SPI
            ['nama' => 'Ketua SPI', 'tunjangan' => 'umum'],
            ['nama' => 'Anggota SPI', 'tunjangan' => 'umum'],

            // Staf Manajer
            ['nama' => 'Staf Manajer Pelayanan Medik', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Manajer Keperawatan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Manajer Penunjang', 'tunjangan' => 'umum'],

            // Supervisor dan Instalasi
            ['nama' => 'Supervisor', 'tunjangan' => 'umum'],
            ['nama' => 'Supervisor Instalasi Dialisis', 'tunjangan' => 'umum'],
            ['nama' => 'Dokter Pelaksana Instalasi Dialisis', 'tunjangan' => 'umum'],
            ['nama' => 'Administrasi Instalasi Dialisis', 'tunjangan' => 'umum'],

            // Kepala Unit dan Instalasi
            ['nama' => 'Ka. Instalasi Peml. Sarpras', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Unit Ambulance', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Unit Transportasi', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Unit Pengelolaan Linen', 'tunjangan' => 'umum'],

            // Staf Unit dan Seksi
            ['nama' => 'Staf Unit Transportasi dan Ambulance + Anggota Komite K3RS', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Perencanaan dan Pengembangan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Hukum dan Kerjasama', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Manajer SDM', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Kepegawaian', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Pendidikan, Pelatihan dan Pengembangan SDM', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Kajian dan Budaya Islam', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Akuntansi', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Keuangan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Keuangan (Kasir)', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Seksi Asuransi', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Asuransi', 'tunjangan' => 'umum'],

            // Unit dan Komite
            ['nama' => 'Ka. Unit Gudang', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Aset dan Logistik', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'tunjangan' => 'umum'],
            ['nama' => 'Plt. Ka. Unit Pemasaran', 'tunjangan' => 'umum'],
            ['nama' => 'Ka. Instalasi Teknologi Informasi', 'tunjangan' => 'umum'],
            ['nama' => 'Ketua Komite Keperawatan', 'tunjangan' => 'umum'],
            ['nama' => 'Sekretaris Komite Mutu', 'tunjangan' => 'umum'],
            ['nama' => 'Dewan Pengawas', 'tunjangan' => 'umum'],

            // Pekarya dan Staf Pelaksana
            ['nama' => 'Pekarya Kesehatan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Administrasi IBS', 'tunjangan' => 'umum'],
            ['nama' => 'Terapis Komplementer', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Instalasi Rawat Jalan', 'tunjangan' => 'umum'],
            ['nama' => 'Pekarya Kesehatan ALZAITUN DAN AT TAQWA', 'tunjangan' => 'umum'],
            ['nama' => 'Pekarya Kesehatan Ruang VK dan Perinatologi', 'tunjangan' => 'umum'],
            ['nama' => 'Pekarya Kesehatan HAJI DAN AL AMIN', 'tunjangan' => 'umum'],
            ['nama' => 'Pekarya Kesehatan Ruang Assyfa dan Aziziah', 'tunjangan' => 'umum'],
            ['nama' => 'Pendaftaran', 'tunjangan' => 'umum'],
            ['nama' => 'Informasi + Filling', 'tunjangan' => 'umum'],
            ['nama' => 'Petugas Entry SKU', 'tunjangan' => 'umum'],
            ['nama' => 'Pelaksana Teknis Non Kefarmasian', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Instalasi Farmasi', 'tunjangan' => 'umum'],

            // Koordinator dan Pramusaji
            ['nama' => 'Koordinator Gudang', 'tunjangan' => 'umum'],
            ['nama' => 'Koordinator Distribusi', 'tunjangan' => 'umum'],
            ['nama' => 'Pramusaji', 'tunjangan' => 'umum'],

            // Instalasi dan Staf Lainnya
            ['nama' => 'Ka. Instalasi Radiologi', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Administrasi', 'tunjangan' => 'umum'],
            ['nama' => 'Sanitarian', 'tunjangan' => 'umum'],
            ['nama' => 'IPAL', 'tunjangan' => 'umum'],
            ['nama' => 'Cleaning Service', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Instalasi Sanitasi', 'tunjangan' => 'umum'],
            ['nama' => 'Staf CSSD', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Instalasi Peml. Sarpras', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Instalasi Gas Medik dan Alkes', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Administrasi Unit MCU dan Poskes', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Unit Transportasi dan Ambulance', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Unit Pemulasaran Jenazah dan Binroh', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Unit Pengelolaan Linen', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Seksi Manajemen Informasi dan Pelaporan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Unit Gudang', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Unit Pengamanan dan Peduli Lingkungan', 'tunjangan' => 'umum'],
            ['nama' => 'Staf Instalasi Teknologi Informasi', 'tunjangan' => 'umum'],
        ];

        foreach ($data as $item) {
            KategoriJabatan::create($item);
        }
    }
}
