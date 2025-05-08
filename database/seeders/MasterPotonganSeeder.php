<?php

namespace Database\Seeders;

use App\Models\MasterGolongan;
use App\Models\MasterPotongan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterPotonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'pinjaman koperasi', 'jenis' => 'nominal'],
            ['nama' => 'ibi', 'jenis' => 'nominal'],
            ['nama' => 'idi', 'jenis' => 'nominal'],
            ['nama' => 'bpjs tenaga kerja', 'jenis' => 'persentase', 'is_wajib' => true],
            ['nama' => 'obat', 'jenis' => 'nominal'],
            ['nama' => 'bpjs kesehatan', 'jenis' => 'persentase', 'is_wajib' => true],
            ['nama' => 'rekonsiliasi bpjs kesehatan', 'jenis' => 'persentase'],
            ['nama' => 'bpjs kesehatan ortu/tambahan', 'jenis' => 'persentase'],
            ['nama' => 'pph21', 'jenis' => 'nominal'],
            ['nama' => 'kurban', 'jenis' => 'nominal'],
            ['nama' => 'rawat inap', 'jenis' => 'nominal'],
            ['nama' => 'amaliah romadhon', 'jenis' => 'nominal'],
            ['nama' => 'dansos karyawan', 'jenis' => 'nominal', 'is_wajib' => true],
            ['nama' => 'iuran perkasi', 'jenis' => 'nominal', 'is_wajib' => true],
            ['nama' => 'lain-lain', 'jenis' => 'nominal'],
        ];

        foreach ($data as $item) {
            MasterPotongan::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
