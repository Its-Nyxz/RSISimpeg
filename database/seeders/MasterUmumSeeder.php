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
        // Data master umum untuk katjab_id 95–161
        $masterUmum = [
            // Rp 350.000
            ['katjab_id' => 95, 'deskripsi' => 'Tunjangan untuk Ketua SPI', 'nominal' => 350000],
            ['katjab_id' => 96, 'deskripsi' => 'Tunjangan untuk Anggota SPI', 'nominal' => 350000],
            ['katjab_id' => 97, 'deskripsi' => 'Tunjangan untuk Staf Manajer Pelayanan Medik', 'nominal' => 350000],
            ['katjab_id' => 98, 'deskripsi' => 'Tunjangan untuk Staf Manajer Keperawatan', 'nominal' => 350000],
            ['katjab_id' => 99, 'deskripsi' => 'Tunjangan untuk Staf Manajer Penunjang', 'nominal' => 350000],
            ['katjab_id' => 100, 'deskripsi' => 'Tunjangan untuk Supervisor', 'nominal' => 350000],
            ['katjab_id' => 101, 'deskripsi' => 'Tunjangan untuk Supervisor Instalasi Dialisis', 'nominal' => 350000],
            ['katjab_id' => 102, 'deskripsi' => 'Tunjangan untuk Dokter Pelaksana Instalasi Dialisis', 'nominal' => 350000],
            ['katjab_id' => 103, 'deskripsi' => 'Tunjangan untuk Administrasi Instalasi Dialisis', 'nominal' => 350000],
            ['katjab_id' => 104, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Peml. Sarpras', 'nominal' => 350000],
            ['katjab_id' => 105, 'deskripsi' => 'Tunjangan untuk Ka. Unit Ambulance', 'nominal' => 350000],
            ['katjab_id' => 106, 'deskripsi' => 'Tunjangan untuk Ka. Unit Transportasi', 'nominal' => 350000],
            ['katjab_id' => 107, 'deskripsi' => 'Tunjangan untuk Staf Unit Transportasi dan Ambulance + Anggota Komite K3RS', 'nominal' => 350000],
            ['katjab_id' => 108, 'deskripsi' => 'Tunjangan untuk Ka. Unit Pemulasaran Jenazah dan Binroh', 'nominal' => 350000],
            ['katjab_id' => 109, 'deskripsi' => 'Tunjangan untuk Ka. Unit Pengelolaan Linen', 'nominal' => 350000],
            ['katjab_id' => 110, 'deskripsi' => 'Tunjangan untuk Staf Seksi Perencanaan dan Pengembangan', 'nominal' => 350000],
            ['katjab_id' => 111, 'deskripsi' => 'Tunjangan untuk Staf Seksi Hukum dan Kerjasama', 'nominal' => 350000],
            ['katjab_id' => 112, 'deskripsi' => 'Tunjangan untuk Staf Manajer SDM', 'nominal' => 350000],
            ['katjab_id' => 113, 'deskripsi' => 'Tunjangan untuk Staf Seksi Kepegawaian', 'nominal' => 350000],
            ['katjab_id' => 114, 'deskripsi' => 'Tunjangan untuk Staf Seksi Pendidikan, Pelatihan dan Pengembangan SDM', 'nominal' => 350000],
            ['katjab_id' => 115, 'deskripsi' => 'Tunjangan untuk Staf Seksi Kajian dan Budaya Islam', 'nominal' => 350000],
            ['katjab_id' => 116, 'deskripsi' => 'Tunjangan untuk Staf Seksi Akuntansi', 'nominal' => 350000],
            ['katjab_id' => 117, 'deskripsi' => 'Tunjangan untuk Staf Seksi Keuangan', 'nominal' => 350000],
            ['katjab_id' => 118, 'deskripsi' => 'Tunjangan untuk Staf Seksi Keuangan (Kasir)', 'nominal' => 350000],
            ['katjab_id' => 119, 'deskripsi' => 'Tunjangan untuk Ka. Seksi Asuransi', 'nominal' => 350000],
            ['katjab_id' => 120, 'deskripsi' => 'Tunjangan untuk Staf Seksi Asuransi', 'nominal' => 350000],
            ['katjab_id' => 121, 'deskripsi' => 'Tunjangan untuk Ka. Unit Gudang', 'nominal' => 350000],
            ['katjab_id' => 122, 'deskripsi' => 'Tunjangan untuk Staf Seksi Aset dan Logistik', 'nominal' => 350000],
            ['katjab_id' => 123, 'deskripsi' => 'Tunjangan untuk Ka. Unit Pengamanan dan Peduli Lingkungan', 'nominal' => 350000],
            ['katjab_id' => 124, 'deskripsi' => 'Tunjangan untuk Plt. Ka. Unit Pemasaran', 'nominal' => 350000],
            ['katjab_id' => 125, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Teknologi Informasi', 'nominal' => 350000],
            ['katjab_id' => 126, 'deskripsi' => 'Tunjangan untuk Ketua Komite Keperawatan', 'nominal' => 350000],
            ['katjab_id' => 127, 'deskripsi' => 'Tunjangan untuk Sekretaris Komite Mutu', 'nominal' => 350000],
            ['katjab_id' => 128, 'deskripsi' => 'Tunjangan untuk Dewan Pengawas', 'nominal' => 350000],

            // Rp 250.000
            ['katjab_id' => 129, 'deskripsi' => 'Tunjangan untuk Pekarya Kesehatan', 'nominal' => 250000],
            ['katjab_id' => 130, 'deskripsi' => 'Tunjangan untuk Staf Administrasi IBS', 'nominal' => 250000],
            ['katjab_id' => 131, 'deskripsi' => 'Tunjangan untuk Terapis Komplementer', 'nominal' => 250000],
            ['katjab_id' => 132, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Rawat Jalan', 'nominal' => 250000],
            ['katjab_id' => 133, 'deskripsi' => 'Tunjangan untuk Pekarya Kesehatan ALZAITUN DAN AT TAQWA', 'nominal' => 250000],
            ['katjab_id' => 134, 'deskripsi' => 'Tunjangan untuk Pekarya Kesehatan Ruang VK dan Perinatologi', 'nominal' => 250000],
            ['katjab_id' => 135, 'deskripsi' => 'Tunjangan untuk Pekarya Kesehatan HAJI DAN AL AMIN', 'nominal' => 250000],
            ['katjab_id' => 136, 'deskripsi' => 'Tunjangan untuk Pekarya Kesehatan Ruang Assyfa dan Aziziah', 'nominal' => 250000],
            ['katjab_id' => 137, 'deskripsi' => 'Tunjangan untuk Pendaftaran', 'nominal' => 250000],
            ['katjab_id' => 138, 'deskripsi' => 'Tunjangan untuk Informasi + Filling', 'nominal' => 250000],
            ['katjab_id' => 139, 'deskripsi' => 'Tunjangan untuk Petugas Entry SKU', 'nominal' => 250000],
            ['katjab_id' => 140, 'deskripsi' => 'Tunjangan untuk Pelaksana Teknis Non Kefarmasian', 'nominal' => 250000],
            ['katjab_id' => 141, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Farmasi', 'nominal' => 250000],
            ['katjab_id' => 142, 'deskripsi' => 'Tunjangan untuk Koordinator Gudang', 'nominal' => 250000],
            ['katjab_id' => 143, 'deskripsi' => 'Tunjangan untuk Koordinator Distribusi', 'nominal' => 250000],
            ['katjab_id' => 144, 'deskripsi' => 'Tunjangan untuk Pramusaji', 'nominal' => 250000],
            ['katjab_id' => 145, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Radiologi', 'nominal' => 250000],
            ['katjab_id' => 146, 'deskripsi' => 'Tunjangan untuk Staf Administrasi', 'nominal' => 250000],
            ['katjab_id' => 147, 'deskripsi' => 'Tunjangan untuk Sanitarian', 'nominal' => 250000],
            ['katjab_id' => 148, 'deskripsi' => 'Tunjangan untuk IPAL', 'nominal' => 250000],
            ['katjab_id' => 149, 'deskripsi' => 'Tunjangan untuk Cleaning Service', 'nominal' => 250000],
            ['katjab_id' => 150, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Sanitasi', 'nominal' => 250000],
            ['katjab_id' => 151, 'deskripsi' => 'Tunjangan untuk Staf CSSD', 'nominal' => 250000],
            ['katjab_id' => 152, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Peml. Sarpras', 'nominal' => 250000],
            ['katjab_id' => 153, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Gas Medik dan Alkes', 'nominal' => 250000],
            ['katjab_id' => 154, 'deskripsi' => 'Tunjangan untuk Staf Administrasi Unit MCU dan Poskes', 'nominal' => 250000],
            ['katjab_id' => 155, 'deskripsi' => 'Tunjangan untuk Staf Unit Transportasi dan Ambulance', 'nominal' => 250000],
            ['katjab_id' => 156, 'deskripsi' => 'Tunjangan untuk Staf Unit Pemulasaran Jenazah dan Binroh', 'nominal' => 250000],
            ['katjab_id' => 157, 'deskripsi' => 'Tunjangan untuk Staf Unit Pengelolaan Linen', 'nominal' => 250000],
            ['katjab_id' => 158, 'deskripsi' => 'Tunjangan untuk Staf Seksi Manajemen Informasi dan Pelaporan', 'nominal' => 250000],
            ['katjab_id' => 159, 'deskripsi' => 'Tunjangan untuk Staf Unit Gudang', 'nominal' => 250000],
            ['katjab_id' => 160, 'deskripsi' => 'Tunjangan untuk Staf Unit Pengamanan dan Peduli Lingkungan', 'nominal' => 250000],
            ['katjab_id' => 161, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Teknologi Informasi', 'nominal' => 250000],
        ];

        // Insert data ke tabel `master_umum`
        foreach ($masterUmum as $umum) {
            MasterUmum::create($umum);
        }
    }
}
