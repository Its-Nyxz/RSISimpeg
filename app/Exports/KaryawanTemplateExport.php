<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KaryawanTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new KaryawanReferenceSheet(), // <-- Harus dibuat lebih dulu
            new KaryawanSheet(),          // Baru sheet yang pakai referensinya
        ];
    }
}
