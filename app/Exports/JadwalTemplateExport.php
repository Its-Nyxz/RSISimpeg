<?php

namespace App\Exports;

use App\Models\JadwalAbsensi;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalTemplateExport implements FromArray, WithHeadings, WithEvents
{
    protected $unitId;
    protected $month;
    protected $year;


    public function __construct($month, $year, $unitId = null)
    {
        $this->unitId = $unitId ?? Auth::user()->unit_id; // jika tidak dikirim, pakai unit login
        $this->month = $month;
        $this->year = $year;
    }


    // Data yang akan di-export
    public function array(): array
    {
        $data = [];

        // Ambil semua user berdasarkan unit_id
        $users = User::where('unit_id', $this->unitId)
            ->orderBy('name', 'asc') // Mengurutkan berdasarkan nama (ascending)
            ->get();

        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        foreach ($users as $index => $user) {
            $row = [
                $index + 1,
                $user->name ?? '-',
                $user->pendidikan ?? '-',
                $user->tmt ? (new DateTime($user->tmt))->format('d/m/Y') : '-',
                $user->masa_kerja ?? 0,
            ];

            // Kosongkan kolom shift untuk setiap hari dalam satu bulan
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $tanggal = Carbon::create($this->year, $this->month, $day)->format('Y-m-d');

                $jadwal = JadwalAbsensi::with('shift')
                    ->where('user_id', $user->id)
                    ->whereDate('tanggal_jadwal', $tanggal)
                    ->first();

                $row[] = $jadwal && $jadwal->shift ? $jadwal->shift->nama_shift : null;
            }


            $data[] = $row;
        }

        // Tambahkan pemisah sebelum daftar shift
        $data[] = [''];
        $data[] = ['KETERANGAN SHIFT:'];

        // Ambil daftar shift untuk ditampilkan di bagian bawah
        $shifts = Shift::where('unit_id', $this->unitId)->get();

        foreach ($shifts as $shift) {
            $data[] = [
                'Nama Shift'   => $shift->nama_shift ?? '-',
                'Jam Masuk'    => $shift->jam_masuk ?? '-',
                'Jam Keluar'   => $shift->jam_keluar ?? '-',
                'Keterangan'   => $shift->keterangan ?? '-',
            ];
        }

        // Tambahkan shift "L" (Libur) jika belum ada di database
        $existingL = Shift::where('unit_id', $this->unitId)
            ->where('nama_shift', 'L')
            ->whereNull('jam_masuk')
            ->whereNull('jam_keluar')
            ->first();

        if (!$existingL) {
            Shift::create([
                'unit_id' => $this->unitId,
                'nama_shift' => 'L',
                'jam_masuk' => null,
                'jam_keluar' => null,
                'keterangan' => 'Libur',
            ]);
        }

        return $data;
    }

    // Header kolom di Excel
    public function headings(): array
    {
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        return array_merge(
            ['NO', 'NAMA', 'PENDIDIKAN', 'TGL MASUK', 'LAMA KERJA'],
            range(1, $daysInMonth)
        );
    }

    // Format tampilan (warna, style, dll.)
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ======= Pastikan shift 'L' ada di database =======
                Shift::firstOrCreate([
                    'unit_id' => $this->unitId,
                    'nama_shift' => 'L',
                    'jam_masuk' => null,
                    'jam_keluar' => null
                ], [
                    'keterangan' => 'Libur'
                ]);

                // ======= Ambil semua shift dan format untuk dropdown =======
                $shifts = Shift::where('unit_id', $this->unitId)->get()->map(function ($shift) {
                    $nama = $shift->nama_shift;
                    $masuk = $shift->jam_masuk ?? '-';
                    $keluar = $shift->jam_keluar ?? '-';

                    // Khusus shift L
                    if ($nama === 'L' && is_null($shift->jam_masuk) && is_null($shift->jam_keluar)) {
                        return 'L (-)';
                    }

                    return "{$nama} ({$masuk}-{$keluar})";
                })->unique()->values();

                // Tambahkan satu opsi kosong di dropdown (opsional)
                $shifts->push('');

                if (count($shifts) > 0) {
                    // Buat sheet baru untuk daftar shift (JANGAN DISEMBUNYIKAN)
                    $hiddenSheet = $sheet->getParent()->createSheet();
                    $hiddenSheet->setTitle('Shifts'); // Buat tab baru dengan judul "Shifts"

                    foreach ($shifts as $index => $shift) {
                        $hiddenSheet->setCellValue('A' . ($index + 1), $shift);
                    }

                    // === Terapkan dropdown langsung ke sheet ===
                    $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;
                    $startRow = 2;
                    $endRow = $sheet->getHighestRow();

                    for ($row = $startRow; $row <= $endRow; $row++) {
                        for ($col = 6; $col <= (5 + $daysInMonth); $col++) {
                            $cell = $sheet->getCellByColumnAndRow($col, $row);

                            $validation = $cell->getDataValidation();
                            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                                ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                                ->setAllowBlank(true)
                                ->setShowDropDown(true)
                                // === Gunakan referensi langsung ke sheet hidden ===
                                ->setFormula1('\'Shifts\'!$A$1:$A$' . count($shifts));
                            // Atur lebar kolom agar dropdown tidak terpotong
                            $sheet->getColumnDimensionByColumn($col)->setWidth(15);
                        }
                    }
                }

                // ======= Style Header Utama =======
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'FFA07A']
                    ]
                ]);
                // ======= Tandai Hari Minggu dengan Warna Merah =======
                $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($this->year, $this->month, $day);

                    // Jika hari Minggu
                    if ($date->format('l') === 'Sunday') {
                        $cell = $sheet->getCellByColumnAndRow($day + 5, 1); // Kolom untuk tanggal di header

                        // Warna merah untuk header Minggu
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        $cell->getStyle()->getFill()->getStartColor()->setARGB('FFFF0000'); // Warna merah
                        $cell->getStyle()->getFont()->getColor()->setARGB('FFFFFFFF'); // Warna font putih
                    }
                }
            },
        ];
    }
}
