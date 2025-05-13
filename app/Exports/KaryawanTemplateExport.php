<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KaryawanTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new KaryawanSheet(),          // Sheet utama isian data
            new KaryawanReferenceSheet(), // Sheet referensi dropdown
        ];
    }
}
