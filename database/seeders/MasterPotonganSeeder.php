<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\MasterGolongan;
use App\Models\MasterPotongan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterPotonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'pinjaman koperasi'],
            ['nama' => 'ibi', 'nominal' => '30000'],
            ['nama' => 'idi', 'nominal' => '50000'],
            ['nama' => 'bpjs tenaga kerja', 'is_wajib' => true],
            ['nama' => 'obat'],
            ['nama' => 'ppni', 'nominal' => '30000'],
            ['nama' => 'bpjs kesehatan', 'is_wajib' => true],
            ['nama' => 'rekonsiliasi bpjs kesehatan'],
            ['nama' => 'bpjs kesehatan ortu/tambahan'],
            ['nama' => 'pph21'],
            ['nama' => 'kurban'],
            ['nama' => 'rawat inap'],
            ['nama' => 'amaliah romadhon'],
            ['nama' => 'dansos karyawan'],
            ['nama' => 'iuran perkasi'],
            ['nama' => 'lain-lain'],
        ];

        foreach ($data as $item) {
            $item['slug'] = Str::slug($item['nama']);
            MasterPotongan::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
