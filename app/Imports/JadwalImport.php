<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Str;
use App\Models\JadwalAbsensi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class JadwalImport implements WithMultipleSheets
{
    /**
     * @param Collection $rows
     */

    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function sheets(): array
    {
        return [
            'Worksheet' => new JadwalSheetImport($this->bulan, $this->tahun),
        ];
    }
}
