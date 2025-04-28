<?php

namespace Database\Seeders;

use App\Models\JenisFile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisFiles = [
            ['name' => 'ID/KTP', 'keterangan' => 'Kartu Tanda Penduduk'],
            ['name' => 'Pas Foto', 'keterangan' => 'Foto untuk keperluan administrasi'],
            ['name' => 'Kartu Keluarga (KK)', 'keterangan' => 'Dokumen Kartu Keluarga'],
            ['name' => 'Ijazah dan Transkip Nilai', 'keterangan' => 'Ijazah dan Transkrip Nilai Pendidikan'],
            ['name' => 'SIP', 'keterangan' => 'Surat Izin Praktik'],
            ['name' => 'STR', 'keterangan' => 'Surat Tanda Registrasi'],
            ['name' => 'Sertifikat Pelatihan', 'keterangan' => 'Sertifikat Pelatihan terkait'],
        ];

        foreach ($jenisFiles as $jenisFile) {
            JenisFile::create($jenisFile);
        }
    }
}
