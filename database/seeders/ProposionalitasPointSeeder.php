<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\MasterFungsi;
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
            ['name' => 'Fisioterapi', 'point' => 1.25],
            ['name' => 'Terapi Wicara, Okupasi Terapi', 'point' => 1.25],
            ['name' => 'Okupasi Terapis', 'point' => 1.25],
            ['name' => 'Perawat IBS', 'point' => 2.9],
            ['name' => 'Perawat ICU', 'point' => 1.15],
            ['name' => 'Perawat Dialisis', 'point' => 1],
        ];

        // Data untuk tabel Umum
        $umum = [
            ['name' => 'Komite Full Time', 'point' => 0.25],
            ['name' => 'Admin IRJ', 'point' => 1],
            ['name' => 'Staf Instalasi Rekam Medik', 'point' => 1],
            ['name' => 'Pekarya Kesehatan IBS', 'point' => 1],
            ['name' => 'Staf Anggota SPI', 'point' => 1],
            ['name' => 'Staf Unit Pemasaran', 'point' => 1],
            ['name' => 'Staf Penunjang', 'point' => 1],
            ['name' => 'Staf Instalasi Rekam Medik', 'point' => 1],
            ['name' => 'Staf Instalasi Teknologi Informasi', 'point' => 1.5],
            ['name' => 'Staf Asuransi', 'point' => 1.25],
        ];

        // Data untuk tabel Unit
        $unit = [
            ['name' => 'CASE MANAGER', 'point' => 1],
            ['name' => 'ASET & LOGISTIK', 'point' => 1],
            ['name' => 'KASIR', 'point' => 1],
            ['name' => 'ASURANSI', 'point' => 1],
            ['name' => 'KEUANGAN', 'point' => 1],
            ['name' => 'AKUNTANSI', 'point' => 1],
            ['name' => 'UNIT MCU & POSKES', 'point' => 1],
        ];

        // Menyimpan data fungsional
        foreach ($fungsional as $item) {
            $jabatan = MasterFungsi::where('nama', $item['name'])->first();
            if ($jabatan) {
                ProposionalitasPoint::create([
                    'proposable_type' => MasterFungsi::class,
                    'proposable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }

        // Menyimpan data umum
        foreach ($umum as $item) {
            $jabatan = MasterUmum::where('nama', $item['name'])->first();
            if ($jabatan) {
                ProposionalitasPoint::create([
                    'proposable_type' => MasterUmum::class,
                    'proposable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }

        // Menyimpan data unit
        foreach ($unit as $item) {
            $jabatan = UnitKerja::where('nama', $item['name'])->first();
            if ($jabatan) {
                ProposionalitasPoint::create([
                    'proposable_type' => UnitKerja::class,
                    'proposable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }
    }
}
