<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\PointPeran;
use App\Models\MasterFungsi;
use App\Models\MasterJabatan;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PointPeranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Data untuk tabel Fungsional
        $fungsional = [
            ['name' => 'Apoteker', 'point' => 4],
            ['name' => 'Karu IBS', 'point' => 3],
            ['name' => 'Karu IGD', 'point' => 4],
            ['name' => 'Karu Instalasi Dialisis', 'point' => 4],
            ['name' => 'Karu ICU', 'point' => 3],
            ['name' => 'Ka. IMP', 'point' => 4],
            ['name' => 'Karu At Taqwa', 'point' => 4],
            ['name' => 'Karu Al Amin', 'point' => 4],
            ['name' => 'Karu Haji', 'point' => 4],
            ['name' => 'Karu Assyfa', 'point' => 4],
            ['name' => 'Karu Al Munawaroh', 'point' => 4],
            ['name' => 'Koordinator MPP', 'point' => 4],
            ['name' => 'MPP', 'point' => 3],
            ['name' => 'Ka. Instalasi CSSD', 'point' => 4],
            ['name' => 'Ka. Unit MCU dan Poskes', 'point' => 4],
            ['name' => 'IPCN', 'point' => 4],
            ['name' => 'Perawat Pelaksana', 'point' => 3],
            ['name' => 'Karu Instalasi Rawat Jalan', 'point' => 3],
            ['name' => 'Perawat Gigi Pelaksana', 'point' => 3],
            ['name' => 'Bidan Pelaksana', 'point' => 3],
            ['name' => 'Karu Al Zaitun', 'point' => 3],
            ['name' => 'Karu VK', 'point' => 3],
            ['name' => 'Karu Perinatologi', 'point' => 3],
            ['name' => 'Ka. Instalasi Rawat Inap', 'point' => 3],
            ['name' => 'Karu Assalam', 'point' => 3],
            ['name' => 'Karu Firdaus', 'point' => 3],
            ['name' => 'Karu Aziziah', 'point' => 3],
            ['name' => 'Karu Instalasi Rehabilitasi Medik', 'point' => 3],
            ['name' => 'Fisioterapis', 'point' => 3],
            ['name' => 'Okupasi Terapi', 'point' => 3],
            ['name' => 'Pelaksana Masak', 'point' => 1],
            ['name' => 'Karu Instalasi Radiologi', 'point' => 3],
            ['name' => 'Radiografer', 'point' => 3],
            ['name' => 'Karu Instalasi Labroatorium', 'point' => 3],
            ['name' => 'Analis Kesehatan', 'point' => 3],
            ['name' => 'Teknisi Bank Darah', 'point' => 3],
            ['name' => 'Perawat Pelaksana Poskes dr. Nono', 'point' => 4],
            ['name' => 'Pelaksana Teknis Kefarmasian', 'point' => 3],
            ['name' => 'Tenaga Teknis Kefarmasian', 'point' => 3],
            ['name' => 'Ka. Instalasi Sanitasi', 'point' => 3],
            ['name' => 'Elektromedis', 'point' => 3],
            ['name' => 'Staf Unit Pemasaran', 'point' => 2],
            ['name' => 'Ka. Instalasi Rekam Medik', 'point' => 3],
            ['name' => 'Staf Instalasi Rekam Medik', 'point' => 3],
            ['name' => 'Asembling', 'point' => 3],
            ['name' => 'Koding', 'point' => 3],
            ['name' => 'Ka. Instalasi Gizi', 'point' => 3],
            ['name' => 'Ahli Gizi', 'point' => 3],
        ];

        // Data untuk tabel Umum
        $umum = [
            ['name' => 'Ketua SPI', 'point' => 2],
            ['name' => 'Anggota SPI', 'point' => 2],
            ['name' => 'Staf Manajer Pelayanan Medik', 'point' => 2],
            ['name' => 'Staf Manajer Keperawatan', 'point' => 2],
            ['name' => 'Staf Manajer Penunjang', 'point' => 2],
            ['name' => 'Supervisor', 'point' => 2],
            ['name' => 'Administrasi Instalasi Dialisis', 'point' => 1],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 2],
            ['name' => 'Ka. Unit Ambulance', 'point' => 2],
            ['name' => 'Ka. Unit Transportasi', 'point' => 2],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 2],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 2],
            ['name' => 'Staf Unit Transportasi dan Ambulance + Anggota Komite K3RS', 'point' => 2],
            ['name' => 'Staf Seksi Perencanaan dan Pengembangan', 'point' => 2],
            ['name' => 'Staf Seksi Hukum dan Kerjasama', 'point' => 2],
            ['name' => 'Staf Manajer SDM', 'point' => 2],
            ['name' => 'Staf Seksi Kepegawaian', 'point' => 2],
            ['name' => 'Staf Seksi Pendidikan, Pelatihan dan Pengembangan SDM', 'point' => 2],
            ['name' => 'Staf Seksi Kajian dan Budaya Islam', 'point' => 2],
            ['name' => 'Staf Seksi Akuntansi', 'point' => 2],
            ['name' => 'Staf Seksi Keuangan', 'point' => 2],
            ['name' => 'Staf Seksi Keuangan (Kasir)', 'point' => 2],
            ['name' => 'Ka. Seksi Asuransi', 'point' => 3],
            ['name' => 'Staf Seksi Asuransi', 'point' => 2],
            ['name' => 'Ka. Unit Gudang', 'point' => 3],
            ['name' => 'Staf Seksi Aset dan Logistik', 'point' => 1],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 1],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 3],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 2],
            ['name' => 'Ketua Komite Keperawatan', 'point' => 2],
            ['name' => 'Sekretaris Komite Mutu', 'point' => 2],
            ['name' => 'Pekarya Kesehatan', 'point' => 1],
            ['name' => 'Staf Administrasi IBS', 'point' => 1],
            ['name' => 'Terapis Komplementer', 'point' => 1],
            ['name' => 'Staf Instalasi Rawat Jalan', 'point' => 1],
            ['name' => 'Pekarya Kesehatan ALZAITUN DAN AT TAQWA', 'point' => 1],
            ['name' => 'Pekarya Kesehatan Ruang VK dan Perinatologi', 'point' => 1],
            ['name' => 'Pekarya Kesehatan HAJI DAN AL AMIN', 'point' => 1],
            ['name' => 'Pekarya Kesehatan Ruang Assyfa dan Aziziah', 'point' => 1],
            ['name' => 'Pendaftaran', 'point' => 1],
            ['name' => 'Informasi + Filling', 'point' => 1],
            ['name' => 'Petugas Entry SKU', 'point' => 1],
            ['name' => 'Pelaksana Teknis Non Kefarmasian', 'point' => 1],
            ['name' => 'Staf Instalasi Farmasi', 'point' => 1],
            ['name' => 'Koordinator Gudang', 'point' => 1],
            ['name' => 'Koordinator Distribusi', 'point' => 1],
            ['name' => 'Pramusaji', 'point' => 1],
            ['name' => 'Staf Administrasi', 'point' => 1],
            ['name' => 'Sanitarian', 'point' => 3],
            ['name' => 'IPAL', 'point' => 1],
            ['name' => 'Cleaning Service', 'point' => 1],
            ['name' => 'Staf Instalasi Sanitasi', 'point' => 1],
            ['name' => 'Staf CSSD', 'point' => 1],
            ['name' => 'Staf Instalasi Peml. Sarpras', 'point' => 1],
            ['name' => 'Staf Instalasi Gas Medik dan Alkes', 'point' => 1],
            ['name' => 'Staf Administrasi Unit MCU dan Poskes', 'point' => 1],
            ['name' => 'Staf Unit Transportasi dan Ambulance', 'point' => 1],
            ['name' => 'Staf Unit Pemulasaran Jenazah dan Binroh', 'point' => 1],
            ['name' => 'Staf Unit Pengelolaan Linen', 'point' => 1],
            ['name' => 'Staf Seksi Manajemen Informasi dan Pelaporan', 'point' => 2],
            ['name' => 'Staf Unit Gudang', 'point' => 2],
            ['name' => 'Staf Unit Pengamanan dan Peduli Lingkungan', 'point' => 1],
            ['name' => 'Staf Instalasi Teknologi Informasi', 'point' => 1],
        ];

        // Simpan data fungsional dengan id kategori fungsional berdasarkan tunjangan
        foreach ($fungsional as $item) {
            $kategoriFungsionalId = KategoriJabatan::where('nama', $item['name'])->value('id');

            $fungsionalId = MasterFungsi::where('katjab_id', $kategoriFungsionalId)->value('id');
            if ($fungsionalId) {
                PointPeran::create([
                    'peransable_type' => MasterFungsi::class,
                    'peransable_id' => $fungsionalId,
                    'point' => $item['point']
                ]);
            }
        }

        // Simpan data umum dengan id kategori umum berdasarkan tunjangan
        foreach ($umum as $item) {
            $kategoriUmumId = KategoriJabatan::where('nama', $item['name'])->value('id');

            $umumId = MasterUmum::where('katjab_id', $kategoriUmumId)->value('id');

            if ($umumId) {
                PointPeran::create([
                    'peransable_type' => MasterUmum::class,
                    'peransable_id' => $umumId,
                    'point' => $item['point']
                ]);
            }
        }
    }
}
