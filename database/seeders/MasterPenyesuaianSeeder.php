<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPenyesuaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterPenyesuaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penyesuaians = [
            ['pendidikan_awal' => 1, 'pendidikan_penyesuaian' => 2, 'masa_kerja' => 2], // SD -> SLTP
            ['pendidikan_awal' => 2, 'pendidikan_penyesuaian' => 3, 'masa_kerja' => 2], // SLTP -> SLTA
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 5, 'masa_kerja' => 3], // SLTA -> DIII
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 6, 'masa_kerja' => 4], // SLTA -> DIV
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 7, 'masa_kerja' => 5], // SLTA -> S1 - Umum
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 8, 'masa_kerja' => 5], // SLTA -> S1 - Apoteker
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 9, 'masa_kerja' => 5], // SLTA -> S1 - Nurse
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 10, 'masa_kerja' => 5], // SLTA -> S1 - Dokter
            ['pendidikan_awal' => 3, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 5], // SLTA -> S1 - Informatika
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 5, 'masa_kerja' => 3], // Kejuruan -> DIII
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 6, 'masa_kerja' => 4], // Kejuruan -> DIV
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 7, 'masa_kerja' => 5], // Kejuruan -> S1 - Umum
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 8, 'masa_kerja' => 5], // Kejuruan -> S1 - Apoteker
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 9, 'masa_kerja' => 5], // Kejuruan -> S1 - Nurse
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 10, 'masa_kerja' => 5], // Kejuruan -> S1 - Dokter
            ['pendidikan_awal' => 4, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 5], // Kejuruan -> S1 - Informatika
            ['pendidikan_awal' => 5, 'pendidikan_penyesuaian' => 6, 'masa_kerja' => 1], // DIII -> DIV
            ['pendidikan_awal' => 5, 'pendidikan_penyesuaian' => 7, 'masa_kerja' => 2], // DIII -> S1 - Umum
            ['pendidikan_awal' => 5, 'pendidikan_penyesuaian' => 8, 'masa_kerja' => 3], // DIII -> S1 - Apoteker
            ['pendidikan_awal' => 5, 'pendidikan_penyesuaian' => 9, 'masa_kerja' => 3], // DIII -> S1 - Nurse
            ['pendidikan_awal' => 5, 'pendidikan_penyesuaian' => 10, 'masa_kerja' => 3], // DIII -> S1 - Dokter
            ['pendidikan_awal' => 5, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 3], // DIII -> S1 - Dokter
            ['pendidikan_awal' => 6, 'pendidikan_penyesuaian' => 7, 'masa_kerja' => 1], // DIV -> S1 - Umum
            ['pendidikan_awal' => 6, 'pendidikan_penyesuaian' => 8, 'masa_kerja' => 1], // DIV -> S1 - Apoteker
            ['pendidikan_awal' => 6, 'pendidikan_penyesuaian' => 9, 'masa_kerja' => 1], // DIV -> S1 - Nurse
            ['pendidikan_awal' => 6, 'pendidikan_penyesuaian' => 10, 'masa_kerja' => 1], // DIV -> S1 - Dokter
            ['pendidikan_awal' => 6, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 1], // DIV -> S1 - Informatika
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 8, 'masa_kerja' => 1], // S1 - Umum -> S1 - Apoteker
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 9, 'masa_kerja' => 1], // S1 - Umum -> S1 - Nurse
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 10, 'masa_kerja' => 1], // S1 - Umum -> S1 - Dokter
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 1], // S1 - Umum -> S2 - Umum
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 12, 'masa_kerja' => 2], // S1 - Umum -> S2 - Spesialis
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 13, 'masa_kerja' => 2], // S1 - Umum -> S2 - Direktur
            ['pendidikan_awal' => 7, 'pendidikan_penyesuaian' => 14, 'masa_kerja' => 2], // S1 - Umum -> S2 - Direktur
            ['pendidikan_awal' => 8, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 2], // S1 - Apoteker -> S2 - Umum
            ['pendidikan_awal' => 8, 'pendidikan_penyesuaian' => 12, 'masa_kerja' => 2], // S1 - Apoteker -> S2 - Spesialis
            ['pendidikan_awal' => 8, 'pendidikan_penyesuaian' => 13, 'masa_kerja' => 2], // S1 - Apoteker -> S2 - Direktur
            ['pendidikan_awal' => 8, 'pendidikan_penyesuaian' => 14, 'masa_kerja' => 2], // S1 - Apoteker -> S2 - Direktur
            ['pendidikan_awal' => 9, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 2], // S1 - Nurse -> S2 - Umum
            ['pendidikan_awal' => 9, 'pendidikan_penyesuaian' => 12, 'masa_kerja' => 2], // S1 - Nurse -> S2 - Spesialis
            ['pendidikan_awal' => 9, 'pendidikan_penyesuaian' => 13, 'masa_kerja' => 2], // S1 - Nurse -> S2 - Direktur
            ['pendidikan_awal' => 9, 'pendidikan_penyesuaian' => 14, 'masa_kerja' => 2], // S1 - Nurse -> S2 - Direktur
            ['pendidikan_awal' => 10, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 2], // S1 - Dokter -> S2 - Umum
            ['pendidikan_awal' => 10, 'pendidikan_penyesuaian' => 12, 'masa_kerja' => 2], // S1 - Dokter -> S2 - Spesialis
            ['pendidikan_awal' => 10, 'pendidikan_penyesuaian' => 13, 'masa_kerja' => 2], // S1 - Dokter -> S2 - Direktur
            ['pendidikan_awal' => 10, 'pendidikan_penyesuaian' => 14, 'masa_kerja' => 2], // S1 - Dokter -> S2 - Direktur
            ['pendidikan_awal' => 11, 'pendidikan_penyesuaian' => 11, 'masa_kerja' => 2], // S1 - Informatika -> S2 - Umum
            ['pendidikan_awal' => 11, 'pendidikan_penyesuaian' => 12, 'masa_kerja' => 2], // S1 - Informatika -> S2 - Spesialis
            ['pendidikan_awal' => 11, 'pendidikan_penyesuaian' => 13, 'masa_kerja' => 2], // S1 - Informatika -> S2 - Direktur
            ['pendidikan_awal' => 11, 'pendidikan_penyesuaian' => 14, 'masa_kerja' => 2], // S1 - Informatika -> S2 - Direktur
        ];

        foreach ($penyesuaians as $penyesuaian) {
            MasterPenyesuaian::create($penyesuaian);
        }
    }
}
