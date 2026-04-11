<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Potongan;
use App\Models\MasterPotongan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportKeuangan implements WithMultipleSheets
{
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected $unitId = null,
        protected $jenisId = null, // default null karena akan dipecah di sheets()
        protected $keyword = null
    ) {}

    public function sheets(): array
    {
        return [
            // Sheet 1: Karyawan Tetap (jenis_id = 1)
            new ExportKeuanganSheet($this->bulan, $this->tahun, $this->unitId, 1, $this->keyword, 'Karyawan Tetap'),
            // Sheet 2: Karyawan Kontrak (jenis_id = 3)
            new ExportKeuanganSheet($this->bulan, $this->tahun, $this->unitId, 3, $this->keyword, 'Karyawan Kontrak'),
        ];
    }
}

/**
 * CLASS CHILD: Menangani Logika Data per Sheet
 */
class ExportKeuanganSheet implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected $unitId,
        protected $jenisId,
        protected $keyword,
        protected string $title
    ) {}
    public function columnFormats(): array
    {
        $idrFormat = '_-\R\p* #,##0_-;-\R\p* #,##0_-;_-\R\p* "-"_-;_-@_-';

        $formats = [
            // TUNJANGAN TETAP & TIDAK TETAP (E sampai L)
            'E' => $idrFormat,
            'F' => $idrFormat,
            'G' => $idrFormat,
            'H' => $idrFormat,
            'I' => $idrFormat,
            'J' => $idrFormat,
            'K' => $idrFormat,
            'M' => $idrFormat,

            // Persentase dengan titik desimal (6 digit)
            'P' => '0.0000%',
            // Pendapatan RS
            'O' => $idrFormat,
            // KPI dengan titik desimal (1 digit)
            'Q' => '0.0%',

            'R' => $idrFormat,
            'S' => $idrFormat,



        ];
        // $jumlahPotongan = MasterPotongan::orderBy('id')->count();
        $jumlahPotongan = MasterPotongan::aktifTerurut()->count();
        if ($jumlahPotongan > 0) {
            // Kolom T adalah kolom ke-20
            $startColumnIndex = 20;
            $endColumnIndex = $startColumnIndex + $jumlahPotongan - 1 + 2;

            $startLetter = Coordinate::stringFromColumnIndex($startColumnIndex);
            $endLetter = Coordinate::stringFromColumnIndex($endColumnIndex);

            // Tambahkan range format ke array (Contoh: 'R:T' => IDR)
            $formats[$startLetter . ':' . $endLetter] = $idrFormat;
        }

        // dd($formats);
        return $formats;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();



                // Mengatur tinggi baris untuk semua baris (Header + Data)
                for ($i = 1; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(28); // Tinggi 28 memberikan efek padding yang lega
                }

                // --- EFEK PERATAAN TENGAH ---
                // Memastikan teks berada di tengah secara vertikal agar padding terlihat seimbang
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Opsional: Tambahkan sedikit indentasi kiri untuk teks (Padding Left)
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()
                    ->setIndent(1);
            },
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function view(): View
    {
        $users = User::with([
            'unitKerja',
            'jenis',
            'gajiBruto' => fn($q) => $q->where('bulan_penggajian', $this->bulan)->where('tahun_penggajian', $this->tahun),
            'gajiBruto.potongan.masterPotongan'
        ])
            ->where('id', '>', 1)
            ->where('status_karyawan', '1')
            ->when($this->unitId, fn($q) => $q->where('unit_id', $this->unitId))
            ->when($this->jenisId, fn($q) => $q->where('jenis_id', $this->jenisId))
            ->when($this->keyword, fn($q) => $q->where('name', 'like', "%{$this->keyword}%"))
            ->orderBy(
                DB::table('urutan_keuangan_user')
                    ->select('urutan')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1),
                'asc'
            )
            // Urutan kedua berdasarkan nama jika nomor urutan sama atau null
            ->orderBy('name', 'asc')
            ->get();

        // $urutanManual = [
        //     'simpanan-wajib',
        //     'simpanan-pokok',
        //     'ibi',
        //     'idi',
        //     'ppni',
        //     'pinjaman-koperasi',
        //     'obat',
        //     'angsuran-bank',
        //     'angsuran-perum',
        //     'dansos-karyawan',
        //     'dplk',
        //     'bpjs-tenaga-kerja',
        //     'bpjs-kesehatan',
        //     'rekonsiliasi-bpjs-kesehatan',
        //     'bpjs-kesehatan-ortutambahan',
        //     'pph21',
        //     'kurangan-pph-21-tahun-2024',
        //     'amaliah-romadhon',
        //     'rawat-inap',
        //     'potongan-selisih',
        //     'iuran-pekarsi',
        //     'lain-lain'
        // ];

        // // Ambil data dan urutkan berdasarkan posisi index di array di atas
        // $masterPotongans = MasterPotongan::all()->sortBy(function ($item) use ($urutanManual) {
        //     $posisi = array_search($item->slug, $urutanManual);

        //     // Jika tidak ketemu, lempar ke urutan 999
        //     // Jika ketemu, gunakan index aslinya (0, 1, 2, dst)
        //     return ($posisi === false) ? 999 : $posisi;
        // })->values();
        $masterPotongans = MasterPotongan::aktifTerurut()->get();

        $users->each(function ($user) use ($masterPotongans) {
            $bruto = $user->gajiBruto->first();

            // Ambil semua nilai tunjangan dari model langsung
            $user->nom_gapok = $bruto?->nom_gapok ?? 0;
            $user->nom_jabatan = $bruto?->nom_jabatan ?? 0;
            $user->nom_fungsi = $bruto?->nom_fungsi ?? 0;
            $user->nom_umum = $bruto?->nom_umum ?? 0;
            $user->nom_khusus = $bruto?->nom_khusus ?? 0;
            $user->nom_makan = $bruto?->nom_makan ?? 0;
            $user->nom_transport = $bruto?->nom_transport ?? 0;
            $user->nom_lainnya = $bruto?->nom_lainnya ?? 0;

            // Menambahkan field tambahan dari struktur sebelumnya jika masih dibutuhkan
            $user->nom_poskes = $bruto?->nom_poskes ?? 0;
            $user->nom_lembur = $bruto?->nom_lembur ?? 0;
            $user->level_jabatan = $bruto?->level_jabatan ?? '-';
            $user->nom_pendapatan_rs = $bruto?->nom_pendapatan_rs ?? 0;
            $user->prosentase_tukin = $bruto?->prosentase_tukin ?? 0;
            $user->KPI = $bruto?->KPI ?? 0;
            $user->nom_tukin_diterima = $bruto?->nom_tukin_diterima ?? 0;

            $user->total_bruto = $bruto?->total_bruto ?? 0;

            // Kompilasi potongan berdasarkan masterPotongan
            $potongan = [];

            foreach ($masterPotongans as $item) {
                $value = $bruto?->potongan
                    ->where('master_potongan_id', $item->id)
                    ->sum('nominal') ?? 0;
                $potongan[$item->nama] = $value;
            }

            $user->potongan_rinci = $potongan;
            $user->total_potongan = array_sum($potongan);
            $user->netto = $user->total_bruto - $user->total_potongan;
        });

        $bendahara = User::where('status_karyawan', '1')
            ->whereHas('roles', function ($q) {
                $q->where('id', 3);
            })
            ->orderBy('name')
            ->first();

        return view('exports.export-keuangan', [
            'users' => $users,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'masterPotongans' => $masterPotongans,
            'bendahara' => $bendahara,
        ]);
    }
}
