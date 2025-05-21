<?php

namespace Database\Seeders;

use App\Models\GapokKontrak;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GapokKontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Contoh format: ['kategori_jabatan_nama', min_bulan, max_bulan, nominal]
            ['Dokter Umum', 0, 6, 7500000],
            ['Dokter Umum', 7, 12, 7500000],
            ['Dokter Gigi', 0, 6, 6000000],
            ['Dokter Gigi', 7, 12, 6000000],
            ['Apoteker', 0, 12, 2150000],
            ['Apoteker', 13, 24, 2350000],
            ['Apoteker', 25, 36, 2550000],
            ['Apoteker', 37, 48, 2750000],
            ['Apoteker', 49, 60, 2950000],
            ['Ners', 0, 12, 2150000],
            ['Ners', 13, 24, 2350000],
            ['Ners', 25, 36, 2550000],
            ['Ners', 37, 48, 2750000],
            ['Ners', 49, 60, 2950000],
            ['Bidan', 0, 12, 2150000],
            ['Bidan', 13, 24, 2350000],
            ['Bidan', 25, 36, 2550000],
            ['Bidan', 37, 48, 2750000],
            ['Bidan', 49, 60, 2950000],
            ['Psikologi Klinik', 0, 12, 2150000],
            ['Psikologi Klinik', 13, 24, 2350000],
            ['Psikologi Klinik', 25, 36, 2550000],
            ['Psikologi Klinik', 37, 48, 2750000],
            ['Psikologi Klinik', 49, 60, 2950000],
            ['Okupasi Terapi', 0, 12, 2150000],
            ['Okupasi Terapi', 13, 24, 2350000],
            ['Okupasi Terapi', 25, 36, 2550000],
            ['Okupasi Terapi', 37, 48, 2750000],
            ['Okupasi Terapi', 49, 60, 2950000],
            ['Terapi Wicara', 0, 12, 2150000],
            ['Terapi Wicara', 13, 24, 2350000],
            ['Terapi Wicara', 25, 36, 2550000],
            ['Terapi Wicara', 37, 48, 2750000],
            ['Terapi Wicara', 49, 60, 2950000],
            ['Perawat Diploma', 0, 12, 2100000],
            ['Perawat Diploma', 13, 24, 2200000],
            ['Perawat Diploma', 25, 36, 2300000],
            ['Perawat Diploma', 37, 48, 2400000],
            ['Perawat Diploma', 49, 60, 2500000],
            ['Radiografer', 0, 12, 2080000],
            ['Radiografer', 13, 24, 2180000],
            ['Radiografer', 25, 36, 2280000],
            ['Radiografer', 37, 48, 2380000],
            ['Radiografer', 49, 60, 2480000],
            ['Analis Kesehatan', 0, 12, 2080000],
            ['Analis Kesehatan', 13, 24, 2180000],
            ['Analis Kesehatan', 25, 36, 2280000],
            ['Analis Kesehatan', 37, 48, 2380000],
            ['Analis Kesehatan', 49, 60, 2480000],
            ['Kesehatan Lingkungan / Sanitarian', 0, 12, 2080000],
            ['Kesehatan Lingkungan / Sanitarian', 13, 24, 2180000],
            ['Kesehatan Lingkungan / Sanitarian', 25, 36, 2280000],
            ['Kesehatan Lingkungan / Sanitarian', 37, 48, 2380000],
            ['Kesehatan Lingkungan / Sanitarian', 49, 60, 2480000],
            ['Fisioterapis', 0, 12, 2080000],
            ['Fisioterapis', 13, 24, 2180000],
            ['Fisioterapis', 25, 36, 2280000],
            ['Fisioterapis', 37, 48, 2380000],
            ['Fisioterapis', 49, 60, 2480000],
            ['Perekam Medik', 0, 12, 2080000],
            ['Perekam Medik', 13, 24, 2180000],
            ['Perekam Medik', 25, 36, 2280000],
            ['Perekam Medik', 37, 48, 2380000],
            ['Perekam Medik', 49, 60, 2480000],
            ['Elektromedik', 0, 12, 2080000],
            ['Elektromedik', 13, 24, 2180000],
            ['Elektromedik', 25, 36, 2280000],
            ['Elektromedik', 37, 48, 2380000],
            ['Elektromedik', 49, 60, 2480000],
            ['Tenaga Teknis Kefarmasian (TTK)', 0, 12, 2080000],
            ['Tenaga Teknis Kefarmasian (TTK)', 13, 24, 2180000],
            ['Tenaga Teknis Kefarmasian (TTK)', 25, 36, 2280000],
            ['Tenaga Teknis Kefarmasian (TTK)', 37, 48, 2380000],
            ['Tenaga Teknis Kefarmasian (TTK)', 49, 60, 2480000],
            ['Ahli Gizi / Dietisien', 0, 12, 2080000],
            ['Ahli Gizi / Dietisien', 13, 24, 2180000],
            ['Ahli Gizi / Dietisien', 25, 36, 2280000],
            ['Ahli Gizi / Dietisien', 37, 48, 2380000],
            ['Ahli Gizi / Dietisien', 49, 60, 2480000],
            ['Staf Administrasi IRJ', 0, 12, 2060000],
            ['Staf Administrasi IRJ', 13, 24, 2160000],
            ['Staf Administrasi IRJ', 25, 36, 2260000],
            ['Staf Administrasi IRJ', 37, 48, 2360000],
            ['Staf Administrasi IRJ', 49, 60, 2460000],
            ['Cleaning Service', 0, 12, 2040000],
            ['Cleaning Service', 13, 24, 2090000],
            ['Cleaning Service', 25, 36, 2140000],
            ['Cleaning Service', 37, 48, 2190000],
            ['Cleaning Service', 49, 60, 2240000],
            ['Cleaning Service', 0, 12, 2040000],
            ['Cleaning Service', 13, 24, 2090000],
            ['Cleaning Service', 25, 36, 2140000],
            ['Cleaning Service', 37, 48, 2190000],
            ['Cleaning Service', 49, 60, 2240000],

            // Bagian Umum D3
            ['Staf SDM', 0, 12, 2060000],
            ['Staf SDM', 13, 24, 2160000],
            ['Staf SDM', 25, 36, 2260000],
            ['Staf SDM', 37, 48, 2360000],
            ['Staf SDM', 49, 60, 2460000],

            ['Staf Keuangan', 0, 12, 2060000],
            ['Staf Keuangan', 13, 24, 2160000],
            ['Staf Keuangan', 25, 36, 2260000],
            ['Staf Keuangan', 37, 48, 2360000],
            ['Staf Keuangan', 49, 60, 2460000],

            ['Staf Asuransi', 0, 12, 2060000],
            ['Staf Asuransi', 13, 24, 2160000],
            ['Staf Asuransi', 25, 36, 2260000],
            ['Staf Asuransi', 37, 48, 2360000],
            ['Staf Asuransi', 49, 60, 2460000],

            ['Staf Aset dan Logistik', 0, 12, 2060000],
            ['Staf Aset dan Logistik', 13, 24, 2160000],
            ['Staf Aset dan Logistik', 25, 36, 2260000],
            ['Staf Aset dan Logistik', 37, 48, 2360000],
            ['Staf Aset dan Logistik', 49, 60, 2460000],

            // Bagian Umum SLTA
            ['Cleaning Service', 0, 12, 2040000],
            ['Cleaning Service', 13, 24, 2090000],
            ['Cleaning Service', 25, 36, 2140000],
            ['Cleaning Service', 37, 48, 2190000],
            ['Cleaning Service', 49, 60, 2240000],

            ['Pelaksana IPAL', 0, 12, 2040000],
            ['Pelaksana IPAL', 13, 24, 2090000],
            ['Pelaksana IPAL', 25, 36, 2140000],
            ['Pelaksana IPAL', 37, 48, 2190000],
            ['Pelaksana IPAL', 49, 60, 2240000],

            ['Staf Unit Pengamanan', 0, 12, 2040000],
            ['Staf Unit Pengamanan', 13, 24, 2090000],
            ['Staf Unit Pengamanan', 25, 36, 2140000],
            ['Staf Unit Pengamanan', 37, 48, 2190000],
            ['Staf Unit Pengamanan', 49, 60, 2240000],

            ['Staf Instalasi Gizi', 0, 12, 2040000],
            ['Staf Instalasi Gizi', 13, 24, 2090000],
            ['Staf Instalasi Gizi', 25, 36, 2140000],
            ['Staf Instalasi Gizi', 37, 48, 2190000],
            ['Staf Instalasi Gizi', 49, 60, 2240000],

            ['Staf Unit Transportasi', 0, 12, 2040000],
            ['Staf Unit Transportasi', 13, 24, 2090000],
            ['Staf Unit Transportasi', 25, 36, 2140000],
            ['Staf Unit Transportasi', 37, 48, 2190000],
            ['Staf Unit Transportasi', 49, 60, 2240000],

            // Profesi Nakes Lain
            ['Psikologi Klinik', 0, 12, 2150000],
            ['Psikologi Klinik', 13, 24, 2350000],
            ['Psikologi Klinik', 25, 36, 2550000],
            ['Psikologi Klinik', 37, 48, 2750000],
            ['Psikologi Klinik', 49, 60, 2950000],

            ['Penata Anastesi', 0, 12, 2150000],
            ['Penata Anastesi', 13, 24, 2350000],
            ['Penata Anastesi', 25, 36, 2550000],
            ['Penata Anastesi', 37, 48, 2750000],
            ['Penata Anastesi', 49, 60, 2950000],

            ['Elektromedik', 0, 12, 2080000],
            ['Elektromedik', 13, 24, 2180000],
            ['Elektromedik', 25, 36, 2280000],
            ['Elektromedik', 37, 48, 2380000],
            ['Elektromedik', 49, 60, 2480000],
        ];

        foreach ($data as [$nama, $min, $max, $nominal]) {
            $katjab = KategoriJabatan::where('nama', $nama)->first();
            if (!$katjab) {
                $this->command->warn("⚠ Kategori '$nama' tidak ditemukan.");
                continue;
            }

            GapokKontrak::create([
                'kategori_jabatan_id' => $katjab->id,
                'min_masa_kerja' => $min,
                'max_masa_kerja' => $max,
                'nominal' => $nominal,
            ]);
        }

        $this->command->info("✔ Gapok kontrak berhasil disisipkan.");
    }
}
