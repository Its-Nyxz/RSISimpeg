<?php

namespace App\Exports;

use App\Models\User;
use App\Models\CutiKaryawan;
use App\Models\UnitKerja;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportRiwayat implements WithMultipleSheets {
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected $unit = null,
        protected $unitId = null,
        protected $jenisId = null,
        protected $keyword = null,
        protected string $mode,
        protected string $selected
    )
    {}

    public function sheets(): array
    {
        if ($this->selected !== 'none') {
            // Jika mode user dan ada selected user, export hanya untuk user tersebut
            return [
                new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, $this->jenisId, $this->keyword, $this->mode, $this->selected, $this->selected),
            ];
        }

        if ($this->unitId) {
            // Jika ada filter unit, export hanya untuk unit tersebut
            return [
                new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 1, $this->keyword, $this->mode, $this->selected, "Unit {$this->unit} Tetap"),
                new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 2, $this->keyword, $this->mode, $this->selected, "Unit {$this->unit} Part-Time"),
                new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 3, $this->keyword, $this->mode, $this->selected, "Unit {$this->unit} Kontrak"),
                new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 4, $this->keyword, $this->mode, $this->selected, "Unit {$this->unit} Magang"),
            ];
        }

        return [
            // Sheet 1: Karyawan Tetap (jenis_id = 1)
            new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 1, $this->keyword, $this->mode, $this->selected, 'Karyawan Tetap'),
            // Sheet 3: Karyawan Part-Time (jenis_id = 2)
            new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 2, $this->keyword, $this->mode, $this->selected, 'Karyawan Part-Time'),
            // Sheet 2: Karyawan Kontrak (jenis_id = 3)
            new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 3, $this->keyword, $this->mode, $this->selected, 'Karyawan Kontrak'),
            // Sheet 4: Karyawan Magang (jenis_id = 4)
            new ExportRiwayatCuti($this->bulan, $this->tahun, $this->unit, $this->unitId, 4, $this->keyword, $this->mode, $this->selected, 'Karyawan Magang'),
        ];
    }
}

class ExportRiwayatCuti implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting {
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected $unit = null,
        protected $unitId = null,
        protected $jenisId = null,
        protected $keyword = null,
        protected string $mode,
        protected string $selected,
        protected string $title
    )
    {}

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'N'; // Fixed 14 columns (A to N)

                // Mengatur tinggi baris untuk semua baris (Header + Data)
                for ($i = 1; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }

                // Freeze pane to keep headers (row 1-3) visible
                $sheet->freezePane('A4');

                // --- EFEK PERATAAN TENGAH ---
                // Memastikan teks berada di tengah secara vertikal agar padding terlihat seimbang
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // Opsional: Tambahkan sedikit indentasi kiri untuk teks (Padding Left)
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()
                    ->setIndent(1);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function view(): View
    {
        // Build query for leave history (cuti) data
        $query = CutiKaryawan::with(['user.unitKerja', 'jenisCuti', 'statusCuti']);

        // Filter by month and year biar sesuai periode bulan / tahun
        if ($this->bulan && $this->tahun) {
            $query->whereMonth('created_at', $this->bulan)
                    ->whereYear('created_at', $this->tahun);
        }

        if ($this->mode === 'all') {
            // Filter by employee type (jenis_id)
            if ($this->jenisId) {
                $query->whereHas('user', function ($q) {
                    $q->where('jenis_id', $this->jenisId);
                });
            }

            // Filter by unit
            if ($this->unitId) {
                $query->whereHas('user', function ($q) {
                    $q->where('unit_id', $this->unitId);
                });
            }

            // filter bulan selain now
            if ($this->bulan !== now()->month) {
                $query->whereHas('user', function ($q) {
                    $q->whereMonth('created_at', $this->bulan);
                });
            }

            // filter tahun selain now
            if ($this->tahun !== now()->year) {
                $query->whereHas('user', function ($q) {
                    $q->whereYear('created_at', $this->tahun);
                });
            }

            $cutiKaryawans = $query->orderBy('created_at', 'asc')->get();
        }

        if ($this->mode === 'user') {
            // $this->selected contains the slugified name or ID passed from the component
            // We find the user first to ensure we have the correct user_id
            $user = User::where('name', str_replace('-', ' ', $this->selected))->first();
            $query->where('user_id', $user?->id);
            $cutiKaryawans = $query->orderBy('created_at', 'asc')->get();
        }

        // dd($cutiKaryawans);

        return view('exports.riwayat-cuti', [
            'cutiKaryawans' => $cutiKaryawans,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'unitId' => $this->unitId,
            'jenisId' => $this->jenisId,
            'keyword' => $this->keyword,
            'title' => $this->title,
            'selected' => $this->selected,
        ]);
    }

}
