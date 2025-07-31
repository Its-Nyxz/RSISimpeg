<?php

namespace App\Exports;

use App\Models\UnitKerja;
use App\Models\Kategoripph;
use App\Models\MasterKhusus;
use App\Models\JenisKaryawan;
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class KaryawanReferenceSheet implements FromArray, WithTitle
{
    public static int $pendidikanCount = 0;
    public static int $unitKerjaCount = 0;
    public static int $jabStrukturalCount = 0;
    public static int $jabFungsionalCount = 0;
    public static int $jabUmumCount = 0;
    public static int $jenisKarCount = 0;
    public static int $khususCount = 0;
    public static int $pphCount = 0;

    public function array(): array
    {
        $pendidikan     = MasterPendidikan::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $unitKerja      = UnitKerja::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        // $jabStruktural  = KategoriJabatan::whereIn('tunjangan', ['jabatan', 'umum'])->get()
        //     ->map(fn($i) => "{$i->id} - {$i->nama}")
        //     ->toArray();
        $jabStruktural  = KategoriJabatan::where('tunjangan', 'jabatan')->get()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        // $jabFungsional  = KategoriJabatan::whereIn('tunjangan', ['fungsi', 'umum'])->get()
        //     ->map(fn($i) => "{$i->id} - {$i->nama}")
        //     ->toArray();
        // $jabUmum        = KategoriJabatan::whereIn('tunjangan', ['fungsi', 'umum'])->get()
        //     ->map(fn($i) => "{$i->id} - {$i->nama}")
        //     ->toArray();
        $jabFungsional = KategoriJabatan::where('tunjangan', 'fungsi')->get()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $jabUmum = KategoriJabatan::where('tunjangan', 'umum')->get()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $jenisKar       = JenisKaryawan::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $khusus         = MasterKhusus::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();
        $pph            = Kategoripph::all()->map(fn($i) => "{$i->id} - {$i->nama}")->toArray();

        self::$pendidikanCount     = count($pendidikan);
        self::$unitKerjaCount      = count($unitKerja);
        self::$jabStrukturalCount  = count($jabStruktural);
        self::$jabFungsionalCount  = count($jabFungsional);
        self::$jabUmumCount        = count($jabUmum);
        self::$jenisKarCount       = count($jenisKar);
        self::$khususCount         = count($khusus);
        self::$pphCount            = count($pph);

        $max = max(
            self::$pendidikanCount,
            self::$unitKerjaCount,
            self::$jabStrukturalCount,
            self::$jabFungsionalCount,
            self::$jabUmumCount,
            self::$jenisKarCount,
            self::$khususCount,
            self::$pphCount
        );

        $data = [];
        for ($i = 0; $i < $max; $i++) {
            $data[] = [
                $pendidikan[$i] ?? null,
                $unitKerja[$i] ?? null,
                $jabStruktural[$i] ?? null,
                $jabFungsional[$i] ?? null,
                $jabUmum[$i] ?? null,
                $jenisKar[$i] ?? null,
                $khusus[$i] ?? null,
                $pph[$i] ?? null,
            ];
        }

        array_unshift($data, [
            'Pendidikan',
            'Unit Kerja',
            'Jabatan Struktural',
            'Jabatan Fungsional',
            'Jabatan Umum',
            'Jenis Karyawan',
            'Tunjangan Khusus',
            'Kategori PPH'
        ]);

        return $data;
    }

    public function title(): string
    {
        return 'REFERENSI';
    }
}
