<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\MasterFungsi;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use App\Models\ProposionalitasPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProposionalitasPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk tabel Fungsional
        $fungsional = [
            ['name' => 'Apoteker', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu IBS', 'point' => 2.6, 'min_limit' => null, 'unit' => 'IBS'],
            ['name' => 'Karu IGD', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Instalasi Dialisis', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu ICU', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. IMP', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu At Taqwa', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Al Amin', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Haji', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Assyfa', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Al Munawaroh', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Koordinator MPP', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'MPP', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Instalasi CSSD', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Unit MCU dan Poskes', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'IPCN', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Perawat Pelaksana', 'point' => 2.9, 'min_limit' => null, 'unit' => 'IBS'],
            ['name' => 'Perawat Pelaksana', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Perawat Pelaksana', 'point' => 1, 'min_limit' => null, 'unit' => 'INST DIALISIS'],
            ['name' => 'Perawat Pelaksana', 'point' => 1, 'min_limit' => null, 'unit' => 'IRJ'],
            ['name' => 'Perawat Pelaksana', 'point' => 1, 'min_limit' => null, 'unit' => 'ALZAITUN'],
            ['name' => 'Karu Instalasi Rawat Jalan', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Perawat Gigi Pelaksana', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Bidan Pelaksana', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Al Zaitun', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu VK', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Perinatologi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Instalasi Rawat Inap', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Assalam', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Firdaus', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Aziziah', 'point' => 1.15, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Instalasi Rehabilitasi Medik', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Fisioterapis', 'point' => 1.25, 'min_limit' => null, 'unit' => null],
            ['name' => 'Okupasi Terapi', 'point' => 1.25, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pelaksana Masak', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Instalasi Radiologi', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Radiografer', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Karu Instalasi Labroatorium', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Analis Kesehatan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Teknisi Bank Darah', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Perawat Pelaksana Poskes dr. Nono', 'point' => 1, 'min_limit' => null, 'unit' => 'MCU & POSKES'],
            ['name' => 'Pelaksana Teknis Kefarmasian', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Tenaga Teknis Kefarmasian', 'point' => 1.1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Instalasi Sanitasi', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Elektromedis', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Unit Pemasaran', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Instalasi Rekam Medik', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Rekam Medik', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Asembling', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Koding', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Instalasi Gizi', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ahli Gizi', 'point' => 1, 'min_limit' => null, 'unit' => null],
        ];

        // Data untuk tabel Umum
        $umum = [
            ['name' => 'Ketua SPI', 'point' => 2, 'min_limit' => 22, 'unit' => null],
            ['name' => 'Ketua SPI', 'point' => 1.9, 'min_limit' => 24, 'unit' => null],
            ['name' => 'Ketua SPI', 'point' => 1.8, 'min_limit' => 26, 'unit' => null],
            ['name' => 'Ketua SPI', 'point' => 1.7, 'min_limit' => 28, 'unit' => null],
            ['name' => 'Ketua SPI', 'point' => 1.6, 'min_limit' => 30, 'unit' => null],
            ['name' => 'Anggota SPI', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Manajer Pelayanan Medik', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Manajer Keperawatan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Manajer Penunjang', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Supervisor', 'point' => 2, 'min_limit' => 22, 'unit' => null],
            ['name' => 'Supervisor', 'point' => 1.9, 'min_limit' => 24, 'unit' => null],
            ['name' => 'Supervisor', 'point' => 1.8, 'min_limit' => 26, 'unit' => null],
            ['name' => 'Supervisor', 'point' => 1.7, 'min_limit' => 28, 'unit' => null],
            ['name' => 'Supervisor', 'point' => 1.6, 'min_limit' => 30, 'unit' => null],
            ['name' => 'Administrasi Instalasi Dialisis', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 2, 'min_limit' => 22, 'unit' => null],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 1.9, 'min_limit' => 24, 'unit' => null],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 1.8, 'min_limit' => 26, 'unit' => null],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 1.7, 'min_limit' => 28, 'unit' => null],
            ['name' => 'Ka. Instalasi Peml. Sarpras', 'point' => 1.6, 'min_limit' => 30, 'unit' => null],
            ['name' => 'Ka. Unit Ambulance', 'point' => 2, 'min_limit' => 22, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 2, 'min_limit' => 22, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Ambulance', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'TRANSPORTASI'],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 2, 'min_limit' => 22, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pemulasaran Jenazah dan Binroh', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 2, 'min_limit' => 22, 'unit' => 'PENGELOLAAN LINEN'],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'PBJR'],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'PBJR'],
            ['name' => 'Staf Unit Transportasi dan Ambulance + Anggota Komite K3RS', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Perencanaan dan Pengembangan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Hukum dan Kerjasama', 'point' => 0, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Manajer SDM', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Kepegawaian', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Pendidikan, Pelatihan dan Pengembangan SDM', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Kajian dan Budaya Islam', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Akuntansi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Keuangan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Keuangan (Kasir)', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Seksi Asuransi', 'point' => 1.7, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Asuransi', 'point' => 1.25, 'min_limit' => null, 'unit' => null],
            ['name' => 'Ka. Unit Gudang', 'point' => 2, 'min_limit' => 22, 'unit' => 'GUDANG'],
            ['name' => 'Ka. Unit Gudang', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'GUDANG'],
            ['name' => 'Ka. Unit Gudang', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'GUDANG'],
            ['name' => 'Ka. Unit Gudang', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'GUDANG'],
            ['name' => 'Ka. Unit Gudang', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'GUDANG'],
            ['name' => 'Staf Seksi Aset dan Logistik', 'point' => 1, 'min_limit' => null, 'unit' => 'GUDANG'],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 2, 'min_limit' => 22, 'unit' => 'PENGAMANAN'],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'PENGAMANAN'],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'PENGAMANAN'],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'PENGAMANAN'],
            ['name' => 'Ka. Unit Pengamanan dan Peduli Lingkungan', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'PENGAMANAN'],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 2, 'min_limit' => 22, 'unit' => 'PEMASARAN'],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'PEMASARAN'],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'PEMASARAN'],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'PEMASARAN'],
            ['name' => 'Plt. Ka. Unit Pemasaran', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'PEMASARAN'],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 2, 'min_limit' => 22, 'unit' => 'ITI'],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 1.9, 'min_limit' => 24, 'unit' => 'ITI'],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 1.8, 'min_limit' => 26, 'unit' => 'ITI'],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 1.7, 'min_limit' => 28, 'unit' => 'ITI'],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 1.6, 'min_limit' => 30, 'unit' => 'ITI'],
            ['name' => 'Ketua Komite Keperawatan', 'point' => 0.25, 'min_limit' => null, 'unit' => null],
            ['name' => 'Sekretaris Komite Mutu', 'point' => 0.25, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pekarya Kesehatan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Administrasi IBS', 'point' => 2, 'min_limit' => null, 'unit' => 'IBS'],
            ['name' => 'Terapis Komplementer', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Rawat Jalan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pekarya Kesehatan ALZAITUN DAN AT TAQWA', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pekarya Kesehatan Ruang VK dan Perinatologi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pekarya Kesehatan HAJI DAN AL AMIN', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pekarya Kesehatan Ruang Assyfa dan Aziziah', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pendaftaran', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Informasi + Filling', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Petugas Entry SKU', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pelaksana Teknis Non Kefarmasian', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Farmasi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Koordinator Gudang', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Koordinator Distribusi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Pramusaji', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Administrasi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Sanitarian', 'point' => 3, 'min_limit' => null, 'unit' => null],
            ['name' => 'IPAL', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Cleaning Service', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Sanitasi', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf CSSD', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Peml. Sarpras', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Gas Medik dan Alkes', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Administrasi Unit MCU dan Poskes', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Unit Transportasi dan Ambulance', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Unit Pemulasaran Jenazah dan Binroh', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Unit Pengelolaan Linen', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Seksi Manajemen Informasi dan Pelaporan', 'point' => 2, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Unit Gudang', 'point' => 2, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Unit Pengamanan dan Peduli Lingkungan', 'point' => 1, 'min_limit' => null, 'unit' => null],
            ['name' => 'Staf Instalasi Teknologi Informasi', 'point' => 1, 'min_limit' => null, 'unit' => null],
        ];

        // Simpan data fungsional dengan id kategori fungsional berdasarkan tunjangan
        foreach ($fungsional as $item) {
            $kategoriFungsionalId = KategoriJabatan::where('nama', $item['name'])->value('id');
            $fungsionalUnitId = UnitKerja::where('nama', $item['unit'])->value('id');

            $fungsionalId = MasterFungsi::where('katjab_id', $kategoriFungsionalId)->value('id');
            if ($fungsionalId) {
                ProposionalitasPoint::create([
                    'unit_id' => $fungsionalUnitId,
                    'proposable_type' => MasterFungsi::class,
                    'proposable_id' => $fungsionalId,
                    'point' => $item['point'],
                    'min_limit' => $item['min_limit']
                ]);
            }
        }

        // Simpan data umum dengan id kategori umum berdasarkan tunjangan
        foreach ($umum as $item) {
            $kategoriUmumId = KategoriJabatan::where('nama', $item['name'])->value('id');
            $umumUnitId = UnitKerja::where('nama', $item['unit'])->value('id');

            $umumId = MasterUmum::where('katjab_id', $kategoriUmumId)->value('id');

            if ($umumId) {
                ProposionalitasPoint::create([
                    'unit_id' => $umumUnitId,
                    'proposable_type' => MasterUmum::class,
                    'proposable_id' => $umumId,
                    'point' => $item['point'],
                    'min_limit' => $item['min_limit']
                ]);
            }
        }
    }
}
