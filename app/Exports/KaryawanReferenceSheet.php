<?php

namespace App\Exports;

use App\Models\UnitKerja;
use App\Models\MasterPendidikan;
use App\Models\KategoriJabatan;
use App\Models\JenisKaryawan;
use App\Models\MasterKhusus;
use App\Models\Kategoripph;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class KaryawanReferenceSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        // Tampilkan data secara horizontal agar mudah direferensikan
        $pendidikan     = MasterPendidikan::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $unitKerja      = UnitKerja::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $jabStruktural  = KategoriJabatan::where('tunjangan', 'jabatan')->get()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $jabFungsional  = KategoriJabatan::where('tunjangan', 'fungsi')->get()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $jenisKar       = JenisKaryawan::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $khusus         = MasterKhusus::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $pph            = Kategoripph::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();

        // Gabungkan jadi satu array berbaris
        $max = max(
            count($pendidikan),
            count($unitKerja),
            count($jabStruktural),
            count($jabFungsional),
            count($jenisKar),
            count($khusus),
            count($pph)
        );

        $data = [];
        for ($i = 0; $i < $max; $i++) {
            $data[] = [
                $pendidikan[$i]     ?? null,
                $unitKerja[$i]      ?? null,
                $jabStruktural[$i]  ?? null,
                $jabFungsional[$i]  ?? null,
                $jenisKar[$i]       ?? null,
                $khusus[$i]         ?? null,
                $pph[$i]            ?? null,
            ];
        }

        // Baris 1 = header
        array_unshift($data, [
            'Pendidikan',
            'Unit Kerja',
            'Jabatan Struktural',
            'Jabatan Fungsional',
            'Jenis Karyawan',
            'Tunjangan Khusus',
            'Kategori PPH',
        ]);

        return $data;
    }


    public function title(): string
    {
        return 'REFERENSI';
    }
}
