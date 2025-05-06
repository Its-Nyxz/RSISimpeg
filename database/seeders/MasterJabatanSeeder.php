<?php

namespace Database\Seeders;

use App\Models\MasterJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definisikan data umum untuk kategori jabatan dengan ID 1-40
        // $jabatans = [
        //     [
        //         'kualifikasi' => 'Strata 1',
        //         'nominal' => 4000000,
        //         'deskripsi' => 'Tunjangan untuk kategori jabatan direktur dan setara.',
        //     ],
        //     [
        //         'kualifikasi' => 'Strata 1',
        //         'nominal' => 3000000,
        //         'deskripsi' => 'Tunjangan untuk kategori jabatan manajer dan setara.',
        //     ],
        //     [
        //         'kualifikasi' => 'Minimal DIII',
        //         'nominal' => 2500000,
        //         'deskripsi' => 'Tunjangan untuk kategori kepala seksi.',
        //     ],
        //     [
        //         'kualifikasi' => 'Minimal DIII',
        //         'nominal' => 2200000,
        //         'deskripsi' => 'Tunjangan untuk kategori kepala instalasi (dokter).',
        //     ],
        // ];

        // Loop untuk mengisi data dengan `katjab_id` dari 1 hingga 40
        // for ($katjab_id = 1; $katjab_id <= 41; $katjab_id++) {
        //     // Tentukan data berdasarkan kategori jabatan
        //     if ($katjab_id <= 3) {
        //         $data = $jabatans[0]; // Direktur atau setara
        //     } elseif ($katjab_id <= 9) {
        //         $data = $jabatans[1]; // Manajer atau setara
        //     } elseif ($katjab_id <= 22) {
        //         $data = $jabatans[2]; // Kepala seksi
        //     } else {
        //         $data = $jabatans[3]; // Kepala instalasi (dokter)
        //     }

        //     // Buat entri di tabel `master_jabatan`
        //     MasterJabatan::create([
        //         'katjab_id' => $katjab_id,
        //         'kualifikasi' => $data['kualifikasi'],
        //         'nominal' => $data['nominal'],
        //         'deskripsi' => $data['deskripsi'],
        //     ]);
        // }

        $jabatans = [
            [
                'katjab_id' => 1,
                'kualifikasi' => 'Strata 1',
                'nominal' => 4000000,
                'deskripsi' => 'Tunjangan untuk kategori jabatan direktur dan setara.',
            ],
            [
                'katjab_id' => 2,
                'kualifikasi' => 'Strata 1',
                'nominal' => 3000000,
                'deskripsi' => 'Tunjangan untuk kategori jabatan manajer dan setara.',
            ],
            [
                'katjab_id' => 3,
                'kualifikasi' => 'Minimal DIII',
                'nominal' => 2500000,
                'deskripsi' => 'Tunjangan untuk kategori kepala seksi.',
            ],
            [
                'katjab_id' => 4,
                'kualifikasi' => 'Minimal DIII',
                'nominal' => 2200000,
                'deskripsi' => 'Tunjangan untuk kategori kepala instalasi (dokter).',
            ],
        ];

        foreach ($jabatans as $jabatan) {
            MasterJabatan::create($jabatan);
        }
    }
}
