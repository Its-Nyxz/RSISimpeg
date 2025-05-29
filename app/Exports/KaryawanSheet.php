<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class KaryawanSheet implements FromArray, WithHeadings, WithTitle, WithEvents
{
    public function array(): array
    {
        // 1. Baris contoh (baris 2 di Excel)
        $data = [[
            'Contoh Nama',
            'email@example.com',
            '123456789',
            '317xxxxxxxxxxxxx',
            '08123456789',
            '1234567890',
            'L',
            'Jakarta',
            Date::PHPToExcel(Carbon::create('1990-01-01')),
            'Jl. Contoh No. 1',
            '1 - SD',
            'SD',
            'SD 6 JAKARTA',
            '1 - IMP',
            '1 - Direktur',
            '6 - Dokter Spesialis',
            '1 - Tetap',
            '1 - Shift',
            Date::PHPToExcel(Carbon::create('2000-01-01')),
            '1 - Dokter Spesialis ...',
            '2 - TK0'
        ]];

        // 2. Data dari model User
        $users = User::with([
            'pendidikanUser',
            'unitKerja',
            'kategorijabatan',
            'kategorifungsional',
            'jenis',
            'khusus',
            'kategoriPPH',
        ])->where('id', '!=', 1)->get();

        foreach ($users as $user) {
            $data[] = [
                $user->name,
                $user->email,
                $user->nip,
                $user->no_ktp,
                $user->no_hp,
                $user->no_rek,
                $user->jk,
                $user->tempat,
                $user->tanggal_lahir ? Date::PHPToExcel(Carbon::parse($user->tanggal_lahir)) : null,
                $user->alamat,
                $user->kategori_pendidikan ? "{$user->kategori_pendidikan} - " . optional($user->pendidikanUser)->nama : null,
                $user->pendidikan,
                $user->institusi,
                optional($user->unitKerja)->id . ' - ' . optional($user->unitKerja)->nama,
                optional($user->kategorijabatan)->id . ' - ' . optional($user->kategorijabatan)->nama,
                optional($user->kategorifungsional)->id . ' - ' . optional($user->kategorifungsional)->nama,
                optional($user->jenis)->id . ' - ' . optional($user->jenis)->nama,
                $user->type_shift,
                $user->tmt ? Date::PHPToExcel(Carbon::parse($user->tmt)) : null,
                optional($user->khusus)->id . ' - ' . optional($user->khusus)->nama,
                optional($user->kategoriPPH)->id . ' - ' . optional($user->kategoriPPH)->nama,
            ];
        }

        return $data;
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
            'Pendidikan',
            'Nama Pendidikan',
            'Institusi',
            'Unit Kerja',
            'Jabatan Struktural',
            'Jabatan Fungsional',
            'Jenis Karyawan',
            'Shift',
            'TMT',
            'Tunjangan Khusus',
            'Kategori PPH'
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

                // Dropdown dari sheet REFERENSI
                $refs = [
                    'K' => ['A', KaryawanReferenceSheet::$pendidikanCount],
                    'N' => ['B', KaryawanReferenceSheet::$unitKerjaCount],
                    'O' => ['C', KaryawanReferenceSheet::$jabStrukturalCount],
                    'P' => ['D', KaryawanReferenceSheet::$jabFungsionalCount],
                    'Q' => ['E', KaryawanReferenceSheet::$jenisKarCount],
                    'T' => ['F', KaryawanReferenceSheet::$khususCount],
                    'U' => ['G', KaryawanReferenceSheet::$pphCount],
                ];

                foreach ($refs as $col => [$refCol, $count]) {
                    if ($count <= 0) continue;
                    $range = "REFERENSI!\${$refCol}\$2:\${$refCol}\$" . (1 + $count);

                    for ($row = 2; $row <= 1000; $row++) {
                        $cell = $col . $row;
                        $validation = new DataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                        $validation->setFormula1("={$range}");
                        $validation->setSqref($cell);
                        $sheet->setDataValidation($cell, $validation);
                    }
                }

                // Dropdown Jenis Kelamin (L/P) langsung di kolom G
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = "G{$row}";
                    $validation = new DataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('"L,P"'); // dropdown langsung
                    $validation->setSqref($cell);
                    $sheet->setDataValidation($cell, $validation);
                }
                // Dropdown Shift langsung di kolom R
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = "R{$row}";
                    $validation = new DataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('"Shift,Non Shift"'); // dropdown langsung
                    $validation->setSqref($cell);
                    $sheet->setDataValidation($cell, $validation);
                }
                // Format kolom Tanggal Lahir (I) sebagai tanggal
                for ($row = 2; $row <= 1000; $row++) {
                    $sheet->getStyle("I{$row}")
                        ->getNumberFormat()
                        ->setFormatCode('yyyy-mm-dd');
                }
                // Format kolom TMT (S) sebagai tanggal
                for ($row = 2; $row <= 1000; $row++) {
                    $sheet->getStyle("S{$row}")
                        ->getNumberFormat()
                        ->setFormatCode('yyyy-mm-dd');
                }

                // Validasi TMT >= Tanggal Lahir
                for ($row = 2; $row <= 1000; $row++) {
                    $cell = "S{$row}"; // kolom TMT

                    $validation = new DataValidation();
                    $validation->setType(DataValidation::TYPE_CUSTOM);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Tanggal Tidak Valid');
                    $validation->setError('TMT tidak boleh lebih awal dari Tanggal Lahir.');
                    $validation->setFormula1("=IF(AND(ISNUMBER(S{$row}),ISNUMBER(I{$row})),S{$row}>=I{$row},TRUE)");
                    $validation->setSqref($cell);

                    $sheet->setDataValidation($cell, $validation);
                }

                $customWidths = [
                    'A' => 20, // Nama
                    'B' => 25, // Email
                    'C' => 15, // NIP
                    'D' => 20, // No KTP
                    'E' => 15, // No HP
                    'F' => 18, // No Rekening
                    'G' => 10, // Jenis Kelamin
                    'H' => 15, // Tempat Lahir
                    'I' => 15, // Tanggal Lahir
                    'J' => 25, // Alamat
                    'K' => 25, // Pendidikan
                    'L' => 20,
                    'M' => 20,
                    'N' => 25, // Unit Kerja
                    'O' => 25, // Jabatan Struktural
                    'P' => 25, // Jabatan Fungsional
                    'Q' => 20,
                    'R' => 15,
                    'S' => 15,
                    'T' => 20,
                    'U' => 20,
                ];

                foreach ($customWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                $kolomTeks = ['C', 'D', 'E', 'F']; // NIP, No KTP, No HP, No Rekening
                foreach ($kolomTeks as $col) {
                    $sheet->getStyle("{$col}2:{$col}1000")
                        ->getNumberFormat()
                        ->setFormatCode('@'); // Format teks
                }

                // 1. Lock semua sel baris contoh (row 2)
                $sheet->getStyle('A2:U2')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

                // 2. Unlock semua sel lainnya (data input)
                $sheet->getStyle('A3:U1000')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

                // 3. Aktifkan proteksi sheet
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('templatekaryawan'); // Optional: tambahkan password
                $sheet->getStyle('A2:U2')->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'F0F0F0'],
                    ],
                    'font' => [
                        'color' => ['rgb' => '555555'],
                    ],
                ]);
                $sheet->getStyle('A1:U1')->getAlignment()->setWrapText(true);

                $sheet->getStyle('A1:U1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '4CAF50'], // hijau tua
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle('A2:U1000')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->freezePane('A3');
            },
        ];
    }
}
