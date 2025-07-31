<?php

namespace Database\Seeders;

use App\Models\MasterFungsi;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterFungsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Data fungsi untuk katjab_id 42–94, lengkap dengan nominal
        // $masterFungsi = [
        //     // Rp 1.500.000
        //     ['katjab_id' => 42, 'deskripsi' => 'Tunjangan untuk Dokter Umum Fungsional', 'nominal' => 1500000],
        //     ['katjab_id' => 43, 'deskripsi' => 'Tunjangan untuk Dokter Umum Fungsional + PJ Klaim', 'nominal' => 1500000],
        //     ['katjab_id' => 44, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Farmasi', 'nominal' => 1500000],
        //     ['katjab_id' => 45, 'deskripsi' => 'Tunjangan untuk Apoteker', 'nominal' => 1500000],

        //     // Rp 1.300.000
        //     ['katjab_id' => 46, 'deskripsi' => 'Tunjangan untuk Dokter Gigi', 'nominal' => 1300000],

        //     // Rp 1.000.000
        //     ['katjab_id' => 47, 'deskripsi' => 'Tunjangan untuk Karu IBS', 'nominal' => 1000000],
        //     ['katjab_id' => 48, 'deskripsi' => 'Tunjangan untuk Karu IGD', 'nominal' => 1000000],
        //     ['katjab_id' => 49, 'deskripsi' => 'Tunjangan untuk Karu Instalasi Dialisis', 'nominal' => 1000000],
        //     ['katjab_id' => 50, 'deskripsi' => 'Tunjangan untuk Karu ICU', 'nominal' => 1000000],
        //     ['katjab_id' => 51, 'deskripsi' => 'Tunjangan untuk Ka. IMP', 'nominal' => 1000000],
        //     ['katjab_id' => 52, 'deskripsi' => 'Tunjangan untuk Karu At Taqwa', 'nominal' => 1000000],
        //     ['katjab_id' => 53, 'deskripsi' => 'Tunjangan untuk Karu Al Amin', 'nominal' => 1000000],
        //     ['katjab_id' => 54, 'deskripsi' => 'Tunjangan untuk Karu Haji', 'nominal' => 1000000],
        //     ['katjab_id' => 55, 'deskripsi' => 'Tunjangan untuk Karu Assyfa', 'nominal' => 1000000],
        //     ['katjab_id' => 56, 'deskripsi' => 'Tunjangan untuk Karu Al Munawaroh', 'nominal' => 1000000],
        //     ['katjab_id' => 57, 'deskripsi' => 'Tunjangan untuk Koordinator MPP', 'nominal' => 1000000],
        //     ['katjab_id' => 58, 'deskripsi' => 'Tunjangan untuk MPP', 'nominal' => 1000000],
        //     ['katjab_id' => 59, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi CSSD', 'nominal' => 1000000],
        //     ['katjab_id' => 60, 'deskripsi' => 'Tunjangan untuk Ka. Unit MCU dan Poskes', 'nominal' => 1000000],
        //     ['katjab_id' => 61, 'deskripsi' => 'Tunjangan untuk IPCN', 'nominal' => 1000000],

        //     // Rp 900.000
        //     ['katjab_id' => 62, 'deskripsi' => 'Tunjangan untuk Perawat Anastesi', 'nominal' => 900000],

        //     // Rp 700.000
        //     ['katjab_id' => 63, 'deskripsi' => 'Tunjangan untuk Perawat Pelaksana', 'nominal' => 700000],
        //     ['katjab_id' => 64, 'deskripsi' => 'Tunjangan untuk Karu Instalasi Rawat Jalan', 'nominal' => 700000],
        //     ['katjab_id' => 65, 'deskripsi' => 'Tunjangan untuk Perawat Gigi Pelaksana', 'nominal' => 700000],
        //     ['katjab_id' => 66, 'deskripsi' => 'Tunjangan untuk Bidan Pelaksana', 'nominal' => 700000],
        //     ['katjab_id' => 67, 'deskripsi' => 'Tunjangan untuk Karu Al Zaitun', 'nominal' => 700000],
        //     ['katjab_id' => 68, 'deskripsi' => 'Tunjangan untuk Karu VK', 'nominal' => 700000],
        //     ['katjab_id' => 69, 'deskripsi' => 'Tunjangan untuk Karu Perinatologi', 'nominal' => 700000],
        //     ['katjab_id' => 70, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Rawat Inap', 'nominal' => 700000],
        //     ['katjab_id' => 71, 'deskripsi' => 'Tunjangan untuk Karu Assalam', 'nominal' => 700000],
        //     ['katjab_id' => 72, 'deskripsi' => 'Tunjangan untuk Karu Firdaus', 'nominal' => 700000],
        //     ['katjab_id' => 73, 'deskripsi' => 'Tunjangan untuk Karu Aziziah', 'nominal' => 700000],
        //     ['katjab_id' => 74, 'deskripsi' => 'Tunjangan untuk Karu Instalasi Rehabilitasi Medik', 'nominal' => 700000],
        //     ['katjab_id' => 75, 'deskripsi' => 'Tunjangan untuk Fisioterapis', 'nominal' => 700000],
        //     ['katjab_id' => 76, 'deskripsi' => 'Tunjangan untuk Okupasi Terapi', 'nominal' => 700000],
        //     ['katjab_id' => 77, 'deskripsi' => 'Tunjangan untuk Pelaksana Masak', 'nominal' => 700000],
        //     ['katjab_id' => 78, 'deskripsi' => 'Tunjangan untuk Karu Instalasi Radiologi', 'nominal' => 700000],
        //     ['katjab_id' => 79, 'deskripsi' => 'Tunjangan untuk Radiografer', 'nominal' => 700000],
        //     ['katjab_id' => 80, 'deskripsi' => 'Tunjangan untuk Karu Instalasi Labroatorium', 'nominal' => 700000],
        //     ['katjab_id' => 81, 'deskripsi' => 'Tunjangan untuk Analis Kesehatan', 'nominal' => 700000],
        //     ['katjab_id' => 82, 'deskripsi' => 'Tunjangan untuk Teknisi Bank Darah', 'nominal' => 700000],
        //     ['katjab_id' => 83, 'deskripsi' => 'Tunjangan untuk Perawat Pelaksana Poskes dr. Nono', 'nominal' => 700000],

        //     // Rp 600.000
        //     ['katjab_id' => 84, 'deskripsi' => 'Tunjangan untuk Pelaksana Teknis Kefarmasian', 'nominal' => 600000],
        //     ['katjab_id' => 85, 'deskripsi' => 'Tunjangan untuk Tenaga Teknis Kefarmasian', 'nominal' => 600000],
        //     ['katjab_id' => 86, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Sanitasi', 'nominal' => 600000],
        //     ['katjab_id' => 87, 'deskripsi' => 'Tunjangan untuk Elektromedis', 'nominal' => 600000],
        //     ['katjab_id' => 88, 'deskripsi' => 'Tunjangan untuk Staf Unit Pemasaran', 'nominal' => 600000],

        //     // Rp 500.000
        //     ['katjab_id' => 89, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Rekam Medik', 'nominal' => 500000],
        //     ['katjab_id' => 90, 'deskripsi' => 'Tunjangan untuk Staf Instalasi Rekam Medik', 'nominal' => 500000],
        //     ['katjab_id' => 91, 'deskripsi' => 'Tunjangan untuk Asembling', 'nominal' => 500000],
        //     ['katjab_id' => 92, 'deskripsi' => 'Tunjangan untuk Koding', 'nominal' => 500000],
        //     ['katjab_id' => 93, 'deskripsi' => 'Tunjangan untuk Ka. Instalasi Gizi', 'nominal' => 500000],
        //     ['katjab_id' => 94, 'deskripsi' => 'Tunjangan untuk Ahli Gizi', 'nominal' => 500000],
        // ];

        // // Masukkan data ke tabel `master_fungsi`
        // foreach ($masterFungsi as $fungsi) {
        //     MasterFungsi::create($fungsi);
        // }
        $fungsiData = [
            // Rp 2.000.000
            ['nama' => 'Dokter Spesialis', 'nominal' => 2000000],
            // Rp 1.500.000
            ['nama' => 'Dokter Umum', 'nominal' => 1500000],
            ['nama' => 'Apoteker', 'nominal' => 1500000],

            // Rp 1.300.000
            ['nama' => 'Dokter Gigi', 'nominal' => 1300000],

            // Rp 1.000.000
            ['nama' => 'Ners', 'nominal' => 1000000],
            ['nama' => 'Psikologi Klinik', 'nominal' => 1000000],

            // Rp 900.000
            ['nama' => 'Penata Anastesi', 'nominal' => 900000],

            // Rp 700.000
            ['nama' => 'Perawat Diploma', 'nominal' => 700000],
            ['nama' => 'Bidan', 'nominal' => 700000],
            ['nama' => 'Fisioterapis', 'nominal' => 700000],
            ['nama' => 'Okupasi Terapi', 'nominal' => 700000],
            ['nama' => 'Terapi Wicara', 'nominal' => 700000],
            ['nama' => 'Radiografer', 'nominal' => 700000],
            ['nama' => 'Analis Kesehatan', 'nominal' => 700000],

            // Rp 600.000
            ['nama' => 'Tenaga Teknis Kefarmasian (TTK)', 'nominal' => 600000],
            ['nama' => 'Kesehatan Lingkungan / Sanitarian', 'nominal' => 600000],
            ['nama' => 'Elektromedik', 'nominal' => 600000],

            // Rp 500.000
            ['nama' => 'Ahli Gizi / Dietisien', 'nominal' => 500000],
            ['nama' => 'Perekam Medik', 'nominal' => 500000],
        ];

        foreach ($fungsiData as $item) {
            $katjab = KategoriJabatan::whereRaw('LOWER(nama) = ?', [strtolower($item['nama'])])->first();

            if ($katjab) {
                MasterFungsi::create([
                    'katjab_id' => $katjab->id,
                    'deskripsi' => 'Tunjangan untuk ' . $item['nama'],
                    'nominal' => $item['nominal'],
                ]);
            } else {
                // Optional: log jika kategori jabatan tidak ditemukan
                echo "Kategori jabatan tidak ditemukan untuk: {$item['nama']}\n";
            }
        }
    }
}
