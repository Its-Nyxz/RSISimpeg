<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class KaryawanSheet implements FromArray, WithHeadings, WithTitle, WithEvents
{
    public function array(): array
    {
        return [[
            'Contoh Nama',
            'email@example.com',
            '123456789',
            '317xxxxxxxxxxxxx',
            '08123456789',
            '1234567890',
            'L',
            'Jakarta',
            '1990-01-01',
            'Jl. Contoh No. 1',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'NIP',
            'No KTP',
            'No HP',
            'No Rekening',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Alamat',
            'Pendidikan (ID - Nama)',
            'Nama Pendidikan',
            'Institusi',
            'Unit Kerja (ID - Nama)',
            'Jabatan Struktural (ID - Nama)',
            'Jabatan Fungsional (ID - Nama)',
            'Jenis Karyawan (ID - Nama)',
            'TMT',
            'Golongan (ID)',
            'Tunjangan Khusus (ID - Nama)',
            'Kategori PPH (ID - Nama)'
        ];
    }

    public function title(): string
    {
        return 'KARYAWAN';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Mapping kolom → baris awal pada sheet REFERENSI
                $dropdownConfig = [
                    'K' => 'REFERENSI!$A$2:$A$100', // Pendidikan
                    'N' => 'REFERENSI!$B$2:$B$100', // Unit Kerja
                    'O' => 'REFERENSI!$C$2:$C$100', // Jabatan Struktural
                    'P' => 'REFERENSI!$D$2:$D$100', // Jabatan Fungsional
                    'Q' => 'REFERENSI!$E$2:$E$100', // Jenis Karyawan
                    'T' => 'REFERENSI!$F$2:$F$100', // Tunjangan Khusus
                    'U' => 'REFERENSI!$G$2:$G$100', // Kategori PPH
                ];

                foreach ($dropdownConfig as $col => $range) {
                    for ($row = 2; $row <= 100; $row++) {
                        $cell = $col . $row;
                        $validation = $sheet->getCell($cell)->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                        $validation->setFormula1($range);

                        $sheet->getCell($cell)->setDataValidation($validation);
                    }
                }
            },
        ];
    }
}
