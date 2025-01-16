<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\MasterFungsi;
use App\Models\PointJabatan;
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
            ['name' => 'Ners', 'point' => 8],
            ['name' => 'Psikologi Klinik', 'point' => 8],
            ['name' => 'Penata Anestesi', 'point' => 8],
            ['name' => 'Perawat Diploma', 'point' => 8],
            ['name' => 'Bidan', 'point' => 8],
            ['name' => 'Analisis Kesehatan', 'point' => 8],
            ['name' => 'Radiografer', 'point' => 8],
            ['name' => 'Fisioterapi', 'point' => 8],
            ['name' => 'Tenaga Informasi Teknologi/IT', 'point' => 8],
            ['name' => 'Ahli Gizi/Dietisen', 'point' => 8],
            ['name' => 'Tenaga Teknis Kefarmasian (TTK)', 'point' => 8],
            ['name' => 'Kesehatan Lingkungan/Sanitari', 'point' => 8],
            ['name' => 'Perawat Medik', 'point' => 8],
            ['name' => 'Elektron Medik', 'point' => 8],
            ['name' => 'Terapi Wicara, Okupasi Terapi', 'point' => 8],
        ];

        // Data untuk tabel Umum
        $umum = [
            ['name' => 'Ka. Unit Ambulance', 'point' => 21],
            ['name' => 'Ka. Unit PJBR', 'point' => 21],
            ['name' => 'Ka. Unit Pengelolaan Linen', 'point' => 21],
            ['name' => 'Ka. Unit Gudang', 'point' => 21],
            ['name' => 'Ka. Unit Pengamanan', 'point' => 21],
            ['name' => 'Ka. Unit Transportasi', 'point' => 21],
            ['name' => 'Ka. Unit Pemasaran', 'point' => 21],
            ['name' => 'Ka. Instalasi Teknologi Informasi', 'point' => 22],
            ['name' => 'Komite Full Time', 'point' => 2],
            ['name' => 'SPI', 'point' => 22],
            ['name' => 'Supervisor', 'point' => 22],
            ['name' => 'Staf Humas dan Program RS', 'point' => 2],
            ['name' => 'Staf SDM', 'point' => 2],
            ['name' => 'Staf Akuntansi', 'point' => 2],
            ['name' => 'Staf Keuangan', 'point' => 2],
            ['name' => 'Staf Asuransi', 'point' => 2],
            ['name' => 'Staf Aset dan Logistik', 'point' => 2],
            ['name' => 'Staf Pelayanan Medik', 'point' => 2],
            ['name' => 'Staf Keperawatan', 'point' => 2],
            ['name' => 'Staf Penunjang', 'point' => 2],
            ['name' => 'Staf Unit Pemasaran', 'point' => 2],
            ['name' => 'Staf Anggota SPI', 'point' => 16],
            ['name' => 'Staf Administrasi IBS', 'point' => 3],
            ['name' => 'Staf Administrasi IRJ', 'point' => 3],
            ['name' => 'Pekarya Kesehatan', 'point' => 3],
            ['name' => 'Staf Instalasi Rekam Medik', 'point' => 3],
            ['name' => 'Tenaga Non Kefarmasian', 'point' => 5],
            ['name' => 'Staf Administrasi Inst Laboratorium', 'point' => 3],
            ['name' => 'Pelaksana IPAL', 'point' => 5],
            ['name' => 'Cleaning Service', 'point' => 2],
            ['name' => 'Staf Instalasi Gas Medik', 'point' => 2],
            ['name' => 'Staf Unit Ambulance', 'point' => 5],
            ['name' => 'Staf Instalasi Gizi', 'point' => 2.5],
            ['name' => 'Staf Unit Pengamanan', 'point' => 2],
            ['name' => 'Staf Unit Transportasi', 'point' => 5],
            ['name' => 'Staf Instalasi Teknologi Informasi', 'point' => 2],
        ];

        // Data untuk tabel Unit
        $unit = [
            ['name' => 'CASE MANAGER', 'point' => 15],
        ];

        // Menyimpan data fungsional
        foreach ($fungsional as $item) {
            $jabatan = MasterFungsi::where('nama', $item['name'])->first();
            if ($jabatan) {
                PointJabatan::create([
                    'pointable_type' => MasterFungsi::class,
                    'pointable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }

        // Menyimpan data umum
        foreach ($umum as $item) {
            $jabatan = MasterUmum::where('nama', $item['name'])->first();
            if ($jabatan) {
                PointJabatan::create([
                    'pointable_type' => MasterUmum::class,
                    'pointable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }

        // Menyimpan data unit
        foreach ($unit as $item) {
            $jabatan = UnitKerja::where('nama', $item['name'])->first();
            if ($jabatan) {
                PointJabatan::create([
                    'pointable_type' => UnitKerja::class,
                    'pointable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }
    }
}
