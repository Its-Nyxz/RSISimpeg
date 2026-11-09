<?php

namespace App\Exports;

use DateTime;
use App\Models\PJ;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\Holidays;
use App\Models\CutiKaryawan;
use App\Models\JadwalAbsensi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

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
        $users = User::where('unit_id', $this->unitId)
            ->orderBy('name', 'asc')
            ->get();
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        $fmtTime = function ($t) {
            if (empty($t)) return '-';
            return substr($t, 0, 5);
        };

        $userIds = $users->pluck('id')->toArray();
        $startDate = sprintf('%04d-%02d-01', $this->year, $this->month);
        $endDate   = sprintf('%04d-%02d-%02d', $this->year, $this->month, $daysInMonth);

        // Preload jadwal sebulan penuh untuk semua user sekaligus (menghilangkan N+1 query)
        $allJadwals = JadwalAbsensi::with('shift')
            ->whereIn('user_id', $userIds)
            ->whereBetween('tanggal_jadwal', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($j) => $j->user_id . '_' . $j->tanggal_jadwal);

        // Preload semua cuti yang disetujui dalam bulan ini (menghilangkan N+1 query)
        $allCutis = CutiKaryawan::whereIn('user_id', $userIds)
            ->where('status_cuti_id', 1)
            ->where('tanggal_mulai', '<=', $endDate)
            ->where('tanggal_selesai', '>=', $startDate)
            ->get()
            ->groupBy('user_id');

        // Preload data PJ
        $pjUserIds = PJ::whereIn('user_id', $userIds)
            ->whereMonth('assigned_at', $this->month)
            ->whereYear('assigned_at', $this->year)
            ->where('is_pj', true)
            ->pluck('user_id')
            ->flip();

        foreach ($users as $index => $user) {
            $isPJ = isset($pjUserIds[$user->id]);
            $userCutis = $allCutis->get($user->id, collect());

            $row = [
                $index + 1,
                $user->name ?? '-',
                $user->pendidikan ?? '-',
                $isPJ ? 'Iya' : 'Tidak', // Kolom PJ otomatis terisi
                $user->tmt ? (new DateTime($user->tmt))->format('d/m/Y') : '-',
                $user->masa_kerja ?? 0,
            ];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $tanggal = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
                $key = $user->id . '_' . $tanggal;

                // Cek cuti dari memori collection (0 query)
                $hasCuti = $userCutis->contains(function ($c) use ($tanggal) {
                    return $c->tanggal_mulai <= $tanggal && $c->tanggal_selesai >= $tanggal;
                });

                $jadwalsOnDay = $allJadwals->get($key, collect());

                if ($hasCuti) {
                    $row[] = 'C (-)';
                } elseif ($jadwalsOnDay->isNotEmpty()) {
                    $validJadwals = $jadwalsOnDay->reject(fn($j) => ($j->shift->nama_shift ?? '') === 'C');
                    if ($validJadwals->isNotEmpty()) {
                        $shiftTexts = [];
                        foreach ($validJadwals as $jadwal) {
                            if (!$jadwal->shift) continue;
                            $nama   = $jadwal->shift->nama_shift ?? '-';
                            $masuk  = $fmtTime($jadwal->shift->jam_masuk ?? null);
                            $keluar = $fmtTime($jadwal->shift->jam_keluar ?? null);

                            if (is_null($jadwal->shift->jam_masuk) && is_null($jadwal->shift->jam_keluar)) {
                                $shiftTexts[] = "{$nama} (-)";
                            } else {
                                $shiftTexts[] = "{$nama} ({$masuk}-{$keluar})";
                            }
                        }
                        $row[] = !empty($shiftTexts) ? implode(' | ', $shiftTexts) : 'L (-)';
                    } else {
                        $row[] = 'L (-)';
                    }
                } else {
                    $row[] = 'L (-)'; // Default untuk hari kosong / libur
                }
            }
            $data[] = $row;
        }
        // Tambahkan pemisah sebelum daftar shift
        $data[] = [''];
        $data[] = ['KETERANGAN SHIFT:'];

        // Pastikan shift L dan C ada di database untuk unit ini
        Shift::firstOrCreate(
            ['unit_id' => $this->unitId, 'nama_shift' => 'L', 'jam_masuk' => null, 'jam_keluar' => null],
            ['keterangan' => 'Libur']
        );
        Shift::firstOrCreate(
            ['unit_id' => $this->unitId, 'nama_shift' => 'C', 'jam_masuk' => null, 'jam_keluar' => null],
            ['keterangan' => 'Cuti']
        );

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

        return $data;
    }

    // Header kolom di Excel
    public function headings(): array
    {
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;
        return array_merge(
            ['NO', 'NAMA', 'PENDIDIKAN', 'PJ', 'TGL MASUK', 'LAMA KERJA'],
            range(1, $daysInMonth)
        );
    }

    // Format tampilan (warna, style, dll.)
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $wb    = $sheet->getParent();

                // Konstanta lokal
                $FIXED_COLS   = 6;                          // A..F (NO..LAMA KERJA)
                $FIRST_DAYCOL = $FIXED_COLS + 1;            // = 7 (G)
                $daysInMonth  = Carbon::create($this->year, $this->month)->daysInMonth;

                // 1) Pastikan shift L dan C ada
                Shift::firstOrCreate(
                    ['unit_id' => $this->unitId, 'nama_shift' => 'L', 'jam_masuk' => null, 'jam_keluar' => null],
                    ['keterangan' => 'Libur']
                );
                Shift::firstOrCreate(
                    ['unit_id' => $this->unitId, 'nama_shift' => 'C', 'jam_masuk' => null, 'jam_keluar' => null],
                    ['keterangan' => 'Cuti']
                );

                // 2) Siapkan list dropdown shift (Nama (in-out)) + "L (-)" + "C (-)"
                $shiftValues = Shift::where('unit_id', $this->unitId)
                    ->get()
                    ->map(function ($s) {
                        $nama   = $s->nama_shift;
                        $masuk  = $s->jam_masuk ?? '-';
                        $keluar = $s->jam_keluar ?? '-';
                        if (is_null($s->jam_masuk) && is_null($s->jam_keluar)) {
                            return "{$nama} (-)";
                        }
                        return "{$nama} ({$masuk}-{$keluar})";
                    })
                    ->unique()
                    ->values();

                // Tambah opsi kosong (opsional)
                $shiftValues->push('');

                // 3) Sediakan sheet "Shifts" untuk sumber dropdown (idempotent)
                if ($shiftValues->count() > 0) {
                    $hidden = $wb->getSheetByName('Shifts');
                    if (!$hidden) {
                        $hidden = $wb->createSheet();
                        $hidden->setTitle('Shifts');
                    } else {
                        // bersihkan kolom A jika sheet sudah ada
                        $hidden->removeColumn('A', 1);
                        $hidden->insertNewColumnBefore('A', 1);
                    }
                    foreach ($shiftValues as $i => $val) {
                        $hidden->setCellValue('A' . ($i + 1), $val);
                    }

                    // 4) Terapkan dropdown ke semua sel hari (baris data)
                    $startRow = 2;
                    $endRow   = max($sheet->getHighestRow(), $startRow); // guard
                    for ($row = $startRow; $row <= $endRow; $row++) {
                        for ($col = $FIRST_DAYCOL; $col <= ($FIXED_COLS + $daysInMonth); $col++) {
                            $cell = $sheet->getCellByColumnAndRow($col, $row);
                            $dv   = $cell->getDataValidation();
                            $dv->setType(DataValidation::TYPE_LIST)
                                ->setErrorStyle(DataValidation::STYLE_STOP)
                                ->setAllowBlank(true)
                                ->setShowDropDown(true)
                                ->setFormula1('\'Shifts\'!$A$1:$A$' . $shiftValues->count());
                            $sheet->getColumnDimensionByColumn($col)->setWidth(15);
                        }
                    }
                }

                // 5) Gaya header kolom tetap A..F
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font'      => ['bold' => true],
                    'alignment' => ['horizontal' => 'center'],
                    'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFA07A']],
                ]);

                // Helper: set fill merah + font putih
                $paintRedHeader = function (int $col) use ($sheet) {
                    $st = $sheet->getStyleByColumnAndRow($col, 1);
                    $st->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $st->getFill()->getStartColor()->setARGB('FFFF0000');
                    $st->getFont()->getColor()->setARGB('FFFFFFFF');
                };

                // Helper: cek apakah sudah punya fill custom (bukan default)
                $hasCustomFill = function (int $col) use ($sheet): bool {
                    $st   = $sheet->getStyleByColumnAndRow($col, 1);
                    $argb = strtoupper($st->getFill()->getStartColor()->getARGB());
                    return $argb !== '00000000' && $argb !== 'FFFFFFFF' && $argb !== '';
                };

                // 6) Tandai libur nasional dari tabel holidays (jika ada)
                //    Asumsi kolom date bernama 'date'. Ganti jika beda.
                $holidayDays = Holidays::query()
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->pluck('date')
                    ->map(fn($d) => Carbon::parse($d)->day)
                    ->unique()
                    ->values();

                foreach ($holidayDays as $hDay) {
                    $col = $FIXED_COLS + $hDay; // day 1 => col 7 (G)
                    if ($col >= $FIRST_DAYCOL && $col <= ($FIXED_COLS + $daysInMonth)) {
                        $paintRedHeader($col); // libur nasional = prioritas, boleh override
                    }
                }

                // 7) Tandai hari Minggu sesuai kalender, JANGAN timpa libur nasional
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($this->year, $this->month, $day);
                    if ($date->dayOfWeek === Carbon::SUNDAY) {
                        $col = $FIXED_COLS + $day;
                        if ($col >= $FIRST_DAYCOL && $col <= ($FIXED_COLS + $daysInMonth)) {
                            if (!$hasCustomFill($col)) {
                                $paintRedHeader($col);
                            }
                        }
                    }
                }

                // 8) Validasi dropdown untuk kolom PJ (D) pada baris data
                $users    = User::where('unit_id', $this->unitId)->orderBy('name', 'asc')->get();
                $rowCount = $users->count();
                for ($row = 2; $row <= $rowCount + 1; $row++) {
                    $cell = 'D' . $row;
                    $dv   = $event->sheet->getDelegate()->getCell($cell)->getDataValidation();
                    $dv->setType(DataValidation::TYPE_LIST)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setShowInputMessage(true)
                        ->setShowErrorMessage(true)
                        ->setShowDropDown(true)
                        ->setFormula1('"Iya,Tidak"')
                        ->setErrorTitle('Input salah')
                        ->setError('Pilih antara Iya atau Tidak')
                        ->setPromptTitle('Pilih PJ')
                        ->setPrompt('Silakan pilih Iya atau Tidak');
                }
            },
        ];
    }
}
