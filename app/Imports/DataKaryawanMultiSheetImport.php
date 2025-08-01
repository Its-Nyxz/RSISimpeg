<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataKaryawanMultiSheetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'KARYAWAN' => new DataKaryawanImport(), // sesuaikan kapitalisasi persis
        ];
    }
}
