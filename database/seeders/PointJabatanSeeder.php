<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\MasterFungsi;
use App\Models\PointJabatan;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PointJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk tabel Fungsional
        $fungsional = [
            ['name' => 'Apoteker', 'point' => 12],
            ['name' => 'Karu IBS', 'point' => 21],
            ['name' => 'Karu IGD', 'point' => 21],
            ['name' => 'Karu Instalasi Dialisis', 'point' => 21],
            ['name' => 'Karu ICU', 'point' => 21],
            ['name' => 'Ka. IMP', 'point' => 22],
            ['name' => 'Karu At Taqwa', 'point' => 21],
            ['name' => 'Karu Al Amin', 'point' => 21],
            ['name' => 'Karu Haji', 'point' => 21],
            ['name' => 'Karu Assyfa', 'point' => 21],
            ['name' => 'Karu Al Munawaroh', 'point' => 21],
            ['name' => 'Koordinator MPP', 'point' => 22],
            ['name' => 'MPP', 'point' => 15],
            ['name' => 'Ka. Instalasi CSSD', 'point' => 22],
            ['name' => 'Ka. Unit MCU dan Poskes', 'point' => 21],
            ['name' => 'IPCN', 'point' => 21],
            ['name' => 'Perawat Pelaksana', 'point' => 10],
            ['name' => 'Karu Instalasi Rawat Jalan', 'point' => 21],
            ['name' => 'Perawat Gigi Pelaksana', 'point' => 10],
            ['name' => 'Bidan Pelaksana', 'point' => 8],
            ['name' => 'Karu Al Zaitun', 'point' => 21],
            ['name' => 'Karu VK', 'point' => 21],
            ['name' => 'Karu Perinatologi', 'point' => 21],
            ['name' => 'Ka. Instalasi Rawat Inap', 'point' => 22],
            ['name' => 'Karu Assalam', 'point' => 21],
            ['name' => 'Karu Firdaus', 'point' => 21],
            ['name' => 'Karu Aziziah', 'point' => 21],
            ['name' => 'Karu Instalasi Rehabilitasi Medik', 'point' => 21],
            ['name' => 'Fisioterapis', 'point' => 8],
            ['name' => 'Okupasi Terapi', 'point' => 8],
            ['name' => 'Pelaksana Masak', 'point' => 2.5],
            ['name' => 'Karu Instalasi Radiologi', 'point' => 21],
            ['name' => 'Radiografer', 'point' => 8],
            ['name' => 'Karu Instalasi Labroatorium', 'point' => 21],
            ['name' => 'Analis Kesehatan', 'point' => 8],
            ['name' => 'Teknisi Bank Darah', 'point' => 8],
            ['name' => 'Perawat Pelaksana Poskes dr. Nono', 'point' => 2],
            ['name' => 'Pelaksana Teknis Kefarmasian', 'point' => 8],
            ['name' => 'Tenaga Teknis Kefarmasian', 'point' => 8],
            ['name' => 'Ka. Instalasi Sanitasi', 'point' => 22],
            ['name' => 'Elektromedis', 'point' => 8],
            ['name' => 'Staf Unit Pemasaran', 'point' => 2],
            ['name' => 'Ka. Instalasi Rekam Medik', 'point' => 22],
            ['name' => 'Staf Instalasi Rekam Medik', 'point' => 3],
            ['name' => 'Asembling', 'point' => 3],
            ['name' => 'Koding', 'point' => 3],
            ['name' => 'Ka. Instalasi Gizi', 'point' => 22],
            ['name' => 'Ahli Gizi', 'point' => 8],
        ];

        // Data untuk tabel Umum
        $umum = [
            ['name' => 'Ketua SPI', 'point' => 22],
            ['name' => 'Anggota SPI', 'point' => 16],
            ['name' => 'Staf Manajer Pelayanan Medik', 'point' => 2],
            ['name' => 'Staf Manajer Keperawatan', 'point' => 2],
            ['name' => 'Staf Manajer Penunjang', 'point' => 2],
            ['name' => 'Supervisor', 'point' => 22],
            ['name' => 'Administrasi Instalasi Dialisis', 'point' => 3],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 22],
            ['name' => 'Ka. Unit Ambulance', 'point' => 21],
            ['name' => 'Ka. Unit Transportasi', 'point' => 21],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 21],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 21],
            ['name' => 'Staf Unit Transportasi dan Ambulance + Anggota Komite K3RS', 'point' => 5],
            ['name' => 'Staf Seksi Perencanaan dan Pengembangan', 'point' => 2],
            ['name' => 'Staf Seksi Hukum dan Kerjasama', 'point' => 0],
            ['name' => 'Staf Manajer SDM', 'point' => 2],
            ['name' => 'Staf Seksi Kepegawaian', 'point' => 2],
            ['name' => 'Staf Seksi Pendidikan, Pelatihan dan Pengembangan SDM', 'point' => 2],
            ['name' => 'Staf Seksi Kajian dan Budaya Islam', 'point' => 2],
            ['name' => 'Staf Seksi Akuntansi', 'point' => 2],
            ['name' => 'Staf Seksi Keuangan', 'point' => 2],
            ['name' => 'Staf Seksi Keuangan (Kasir)', 'point' => 2],
            ['name' => 'Ka. Seksi Asuransi', 'point' => 21],
            ['name' => 'Staf Seksi Asuransi', 'point' => 2],
            ['name' => 'Ka. Unit Gudang', 'point' => 21],
            ['name' => 'Staf Seksi Aset dan Logistik', 'point' => 2],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 21],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 21],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 21],
            ['name' => 'Ketua Komite Keperawatan', 'point' => 2],
            ['name' => 'Sekretaris Komite Mutu', 'point' => 2],
            ['name' => 'Pekarya Kesehatan', 'point' => 3],
            ['name' => 'Staf Administrasi IBS', 'point' => 3],
            ['name' => 'Terapis Komplementer', 'point' => 3],
            ['name' => 'Staf Instalasi Rawat Jalan', 'point' => 3],
            ['name' => 'Pekarya Kesehatan ALZAITUN DAN AT TAQWA', 'point' => 3],
            ['name' => 'Pekarya Kesehatan Ruang VK dan Perinatologi', 'point' => 3],
            ['name' => 'Pekarya Kesehatan HAJI DAN AL AMIN', 'point' => 3],
            ['name' => 'Pekarya Kesehatan Ruang Assyfa dan Aziziah', 'point' => 3],
            ['name' => 'Pendaftaran', 'point' => 2],
            ['name' => 'Informasi + Filling', 'point' => 2],
            ['name' => 'Petugas Entry SKU', 'point' => 2],
            ['name' => 'Pelaksana Teknis Non Kefarmasian', 'point' => 5],
            ['name' => 'Staf Instalasi Farmasi', 'point' => 5],
            ['name' => 'Koordinator Gudang', 'point' => 2.5],
            ['name' => 'Koordinator Distribusi', 'point' => 2.5],
            ['name' => 'Pramusaji', 'point' => 2.5],
            ['name' => 'Staf Administrasi', 'point' => 3],
            ['name' => 'Sanitarian', 'point' => 2],
            ['name' => 'IPAL', 'point' => 2],
            ['name' => 'Cleaning Service', 'point' => 2],
            ['name' => 'Staf Instalasi Sanitasi', 'point' => 2],
            ['name' => 'Staf CSSD', 'point' => 2],
            ['name' => 'Staf Instalasi Peml. Sarpras', 'point' => 2],
            ['name' => 'Staf Instalasi Gas Medik dan Alkes', 'point' => 2],
            ['name' => 'Staf Administrasi Unit MCU dan Poskes', 'point' => 2],
            ['name' => 'Staf Unit Transportasi dan Ambulance', 'point' => 5],
            ['name' => 'Staf Unit Pemulasaran Jenazah dan Binroh', 'point' => 5],
            ['name' => 'Staf Unit Pengelolaan Linen', 'point' => 2],
            ['name' => 'Staf Seksi Manajemen Informasi dan Pelaporan', 'point' => 2],
            ['name' => 'Staf Unit Gudang', 'point' => 2],
            ['name' => 'Staf Unit Pengamanan dan Peduli Lingkungan', 'point' => 2],
            ['name' => 'Staf Instalasi Teknologi Informasi', 'point' => 2],
        ];


        // Simpan data fungsional dengan id kategori fungsional berdasarkan tunjangan
        foreach ($fungsional as $item) {
            $kategoriFungsionalId = KategoriJabatan::where('nama', $item['name'])->value('id');

            $fungsionalId = MasterFungsi::where('katjab_id', $kategoriFungsionalId)->value('id');
            if ($fungsionalId) {
                PointJabatan::create([
                    'pointable_type' => MasterFungsi::class,
                    'pointable_id' => $fungsionalId,
                    'point' => $item['point']
                ]);
            }
        }

        // Simpan data umum dengan id kategori umum berdasarkan tunjangan
        foreach ($umum as $item) {
            $kategoriUmumId = KategoriJabatan::where('nama', $item['name'])->value('id');

            $umumId = MasterUmum::where('katjab_id', $kategoriUmumId)->value('id');

            if ($umumId) {
                PointJabatan::create([
                    'pointable_type' => MasterUmum::class,
                    'pointable_id' => $umumId,
                    'point' => $item['point']
                ]);
            }
        }
    }
}
