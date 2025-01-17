<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\PointPeran;
use App\Models\MasterFungsi;
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
            ['name' => 'Psikologi Klinik', 'point' => 4],
            ['name' => 'Ners', 'point' => 4],
            ['name' => 'Perawat Diploma', 'point' => 3],
            ['name' => 'Bidan', 'point' => 3],
            ['name' => 'Analis Kesehatan', 'point' => 3],
            ['name' => 'Radiografer', 'point' => 3],
            ['name' => 'Fisioterapi', 'point' => 3],
            ['name' => 'Tenaga Informasi Teknologi/ IT', 'point' => 3],
            ['name' => 'Ahli Gizi/ Dietisien', 'point' => 3],
            ['name' => 'Perekam Medik', 'point' => 1],
            ['name' => 'Tenaga Teknis Kefarmasian (TTK)', 'point' => 1],
            ['name' => 'Elektromedik', 'point' => 1],
            ['name' => 'Penata Anestesi', 'point' => 1],
            ['name' => 'Terapi Wicara, Okupasi Terapi', 'point' => 1],
            ['name' => 'Kesehatan Lingkungan/ Sanitarian', 'point' => 1],
        ];

        // Data untuk tabel Umum
        $umum = [
            ['name' => 'Komite Full Time', 'point' => 2],
            ['name' => 'Staf Administrasi IBS', 'point' => 2],
            ['name' => 'Staf Administrasi IRJ', 'point' => 2],
        ];



        // Menyimpan data fungsional
        foreach ($fungsional as $item) {
            $jabatan = MasterFungsi::where('nama', $item['name'])->first();
            if ($jabatan) {
                PointPeran::create([
                    'peransable_type' => MasterFungsi::class,
                    'peransable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }

        // Menyimpan data umum
        foreach ($umum as $item) {
            $jabatan = MasterUmum::where('nama', $item['name'])->first();
            if ($jabatan) {
                PointPeran::create([
                    'peransable_type' => MasterUmum::class,
                    'peransable_id' => $jabatan->id,
                    'point' => $item['point']
                ]);
            }
        }
    }
}
