<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Potongan;
use App\Models\GajiBruto;
use App\Models\TaxBracket;
use App\Models\MasterTrans;
use Illuminate\Support\Str;
use App\Models\GapokKontrak;
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

/**
 * CLASS UTAMA: Mengatur pembagian Sheet
 */
class PotonganTemplateExport implements WithMultipleSheets
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
            new PotonganSheet($this->bulan, $this->tahun, $this->unitId, 1, $this->keyword, 'Karyawan Tetap'),
            // Sheet 2: Karyawan Kontrak (jenis_id = 3)
            new PotonganSheet($this->bulan, $this->tahun, $this->unitId, 3, $this->keyword, 'Karyawan Kontrak'),
        ];
    }
}

/**
 * CLASS CHILD: Menangani Logika Data per Sheet
 */
class PotonganSheet implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting
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
        $idrFormat = '_-Rp* #,##0_-;-Rp* #,##0_-;_-Rp* "-"_-;_-@_-';

        $formats = [
            // TUNJANGAN TETAP & TIDAK TETAP (E sampai L)
            'E' => $idrFormat,
            'F' => $idrFormat,
            'G' => $idrFormat,
            'H' => $idrFormat,
            'I' => $idrFormat,
            'J' => $idrFormat,
            'K' => $idrFormat,

            // Pendapatan RS
            'M' => $idrFormat,
            // Persentase dengan titik desimal (6 digit)
            'N' => '0.0000"%"',
            // KPI dengan titik desimal (1 digit)
            'O' => '0.0"%"',

            'P' => $idrFormat,
            'Q' => $idrFormat,


        ];
        $jumlahPotongan = MasterPotongan::orderBy('id')->count();
        if ($jumlahPotongan > 0) {
            // Kolom R adalah kolom ke-18
            $startColumnIndex = 18;
            $endColumnIndex = $startColumnIndex + $jumlahPotongan - 1;

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


                $sheet->freezePane('D6');
                // --- EFEK PADDING VERTIKAL (Padding Y) ---
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

        // Ambil data user berdasarkan jenis_id yang dikirim dari parent

        $users = User::where('id', '!=', 1)
            ->with([
                'unitKerja',
                'jenis',
                'golongan.gapoks',
                'kategorijabatan.masterjabatan',
                'kategorijabatan.masterfungsi',
                'kategorijabatan.masterumum',
                'riwayatJabatan.kategori',
                'khusus',
                'urutanKeuangan' // Tambahkan relasi urutan jika diperlukan
            ])
            ->where('status_karyawan', '1') // Pastikan status karyawan aktif jika diperlukan
            ->when($this->unitId, fn($q) => $q->where('unit_id', $this->unitId))
            ->where('jenis_id', $this->jenisId)
            ->when($this->keyword, fn($q) => $q->where('name', 'like', "%{$this->keyword}%"))

            // Logika Urutan: Menggunakan subquery untuk mengurutkan berdasarkan tabel urutan_keuangan_user
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

        // Daftar slug sesuai urutan gambar (pastikan slug sesuai dengan isi DB Anda)
        $urutanManual = [
            'simpanan-wajib',
            'simpanan-pokok',
            'ibi',
            'idi',
            'ppni',
            'pinjaman-koperasi',
            'obat',
            'angsuran-bank',
            'angsuran-perum',
            'dansos-karyawan',
            'dplk',
            'bpjs-tenaga-kerja',
            'bpjs-kesehatan',
            'rekonsiliasi-bpjs-kesehatan',
            'bpjs-kesehatan-ortutambahan',
            'pph21',
            'kurangan-pph-21-tahun-2024',
            'amaliah-romadhon',
            'rawat-inap',
            'potongan-selisih',
            'iuran-pekarsi',
            'lain-lain'
        ];

        // Ambil data dan urutkan berdasarkan posisi index di array di atas
        $masterPotongans = MasterPotongan::all()->sortBy(function ($item) use ($urutanManual) {
            $posisi = array_search($item->slug, $urutanManual);

            // Jika tidak ketemu, lempar ke urutan 999
            // Jika ketemu, gunakan index aslinya (0, 1, 2, dst)
            return ($posisi === false) ? 999 : $posisi;
        })->values();

        // dd($masterPotongans->toArray());
        $masterTrans = MasterTrans::first();
        $periodeMulai = Carbon::create($this->tahun, $this->bulan, 21)->subMonth()->startOfDay();
        $periodeSelesai = Carbon::create($this->tahun, $this->bulan, 20)->endOfDay();

        foreach ($users as $user) {
            $gajiBruto = GajiBruto::where('user_id', $user->id)
                ->where('bulan_penggajian', $this->bulan)
                ->where('tahun_penggajian', $this->tahun)
                ->first();

            $existingPotongan = collect();
            if ($gajiBruto) {
                $existingPotongan = Potongan::where('bruto_id', $gajiBruto->id)
                    ->with('masterPotongan')
                    ->get();
            }

            // Jika data sudah ada di DB (History), ambil dari DB
            if ($gajiBruto && $existingPotongan->count() > 0) {
                $user->setAttribute('nom_gapok', $gajiBruto->nom_gapok);
                $user->setAttribute('nom_fungsi', $gajiBruto->nom_fungsi);
                $user->setAttribute('nom_jabatan', $gajiBruto->nom_jabatan);
                $user->setAttribute('nom_umum', $gajiBruto->nom_umum);
                $user->setAttribute('nom_poskes', $gajiBruto->nom_poskes);
                $user->setAttribute('nom_lainnya', $gajiBruto->nom_lainnya);
                $user->setAttribute('nom_lembur', $gajiBruto->nom_lembur);
                $user->setAttribute('level_jabatan', $gajiBruto->level_jabatan);
                $user->setAttribute('nom_pendapatan_rs', $gajiBruto->nom_pendapatan_rs);
                $user->setAttribute('prosentase_tukin', $gajiBruto->prosentase_tukin);
                $user->setAttribute('KPI', $gajiBruto->KPI);
                $user->setAttribute('nom_tukin_diterima', $gajiBruto->nom_tukin_diterima);
                $user->setAttribute('total_bruto', $gajiBruto->total_bruto);

                $potonganData = $existingPotongan->mapWithKeys(fn($p) => [
                    $p->masterPotongan->nama => $p->nominal,
                ]);
                $user->setAttribute('potonganOtomasis', $potonganData->toArray());
                // dd($user->toArray());
                continue;
            }

            // Logika Kalkulasi Otomatis (Jika belum ada di DB)
            $jenis = strtolower($user->jenis?->nama ?? '');
            $masaKerja = $user->masa_kerja ?? ($user->tmt ? floor(Carbon::parse($user->tmt)->floatDiffInYears(now())) : 0);

            $gapok = 0;
            $nom_jabatan = 0;
            $nom_fungsi = 0;
            $nom_umum = 0;
            $nom_khusus = $user->khusus?->nominal ?? 0;

            $jadwalUser = $user->jadwalabsensi()
                ->whereBetween('tanggal_jadwal', [$periodeMulai->toDateString(), $periodeSelesai->toDateString()])
                ->get();

            $totalHariJadwal = $jadwalUser->count();
            $semuaLibur = $jadwalUser->every(fn($j) => in_array(strtolower(optional($j->shift)->nama_shift), ['l', 'libur']));
            $semuaC = $jadwalUser->every(fn($j) => in_array(strtolower(optional($j->shift)->nama_shift), ['c', 'cuti']));

            $liburTotalSebulan = $semuaLibur && !$semuaC && $totalHariJadwal > 0;

            if ($liburTotalSebulan) {
                $total_bruto = 0;
                $gapok = 0;
                $nom_jabatan = 0;
                $nom_fungsi = 0;
                $nom_umum = 0;
                $nom_khusus = 0;
                $nom_makan = 0;
                $nom_transport = 0;
            } else {
                // Kalkulasi Gapok
                if ($jenis === 'tetap') {
                    $gapok = optional($user->golongan?->gapoks->where('masa_kerja', '<=', $masaKerja)->sortByDesc('masa_kerja')->first())->nominal_gapok ?? 0;
                } elseif ($jenis === 'kontrak') {
                    $kategoriJabatanId = $user->jabatan_id ?? $user->fungsi_id ?? $user->umum_id;
                    $gapokKontrak = GapokKontrak::where('kategori_jabatan_id', $kategoriJabatanId)
                        ->where('pendidikan_id', $user->kategori_pendidikan)
                        ->where('min_masa_kerja', '<=', $masaKerja)
                        ->where('max_masa_kerja', '>=', $masaKerja)
                        ->first();
                    $gapok = $gapokKontrak?->nominal_aktif ?? $gapokKontrak?->nominal ?? 0;
                }

                // Kalkulasi Tunjangan via Riwayat Jabatan
                $riwayats = $user->riwayatJabatan->filter(function ($r) use ($periodeMulai, $periodeSelesai) {
                    $start = Carbon::parse($r->tanggal_mulai);
                    $end = $r->tanggal_selesai ? Carbon::parse($r->tanggal_selesai) : $periodeSelesai;
                    return $start <= $periodeSelesai && $end >= $periodeMulai;
                });

                foreach ($riwayats as $riwayat) {
                    $kategori = $riwayat->kategori;
                    if (!$kategori) continue;
                    $start = Carbon::parse(max($riwayat->tanggal_mulai, $periodeMulai));
                    $end = Carbon::parse(min($riwayat->tanggal_selesai ?? $periodeSelesai, $periodeSelesai));
                    $hariJadwalAktif = $jadwalUser->filter(fn($j) => Carbon::parse($j->tanggal_jadwal)->between($start, $end))->count();
                    $proporsi = $hariJadwalAktif / max($totalHariJadwal, 1);
                    $nominal = max(0, $kategori->nominal);

                    if ($riwayat->tunjangan === 'jabatan') $nom_jabatan += $nominal * $proporsi;
                    elseif ($riwayat->tunjangan === 'fungsi') $nom_fungsi += $nominal * $proporsi;
                    elseif ($riwayat->tunjangan === 'umum') $nom_umum += $nominal * $proporsi;
                }

                $nom_makan = $masterTrans?->nom_makan ?? 0;
                $nom_transport = $masterTrans?->nom_transport ?? 0;
                $total_bruto = $gapok + $nom_jabatan + $nom_fungsi + $nom_umum + $nom_khusus + $nom_makan + $nom_transport;
            }

            $user->setAttribute('nom_gapok', $gapok);
            $user->setAttribute('nom_jabatan', $nom_jabatan);
            $user->setAttribute('nom_fungsi', $nom_fungsi);
            $user->setAttribute('nom_umum', $nom_umum);
            $user->setAttribute('nom_makan', round($nom_makan));
            $user->setAttribute('nom_transport', round($nom_transport));
            $user->setAttribute('nom_khusus', $nom_khusus);
            $user->setAttribute('nom_lainnya', 0);
            $user->setAttribute('total_bruto', round($total_bruto));

            // Logika Potongan Otomatis
            $potonganOtomasis = [];
            $bruto = $user->total_bruto;
            $tunjanganTotal = $nom_jabatan + $nom_fungsi + $nom_umum;
            $makanTransport = $nom_makan + $nom_transport;

            foreach ($masterPotongans as $item) {
                $slug = $item->slug;
                $nominalPotongan = 0;

                if (Str::contains($slug, 'pph')) {
                    $kategoriInduk = $user->kategoriPphInduk();
                    if ($kategoriInduk) {
                        $tax = TaxBracket::where('kategoripph_id', $kategoriInduk->id)->where('upper_limit', '>=', $bruto)->orderBy('upper_limit')->first();
                        $nominalPotongan = round($bruto * ($tax?->persentase ?? 0));
                    }
                } elseif (Str::contains($slug, 'bpjs-tenaga-kerja')) {
                    $nominalPotongan = round(0.03 * ($gapok + $tunjanganTotal));
                } elseif (Str::contains($slug, 'bpjs-kesehatan')) {
                    $nominalPotongan = round(0.01 * ($gapok + $tunjanganTotal + $makanTransport));
                }

                $potonganOtomasis[$item->nama] = $nominalPotongan;
            }
            $user->setAttribute('potonganOtomasis', $potonganOtomasis);
        }

        return view('exports.template-potongan', [
            'users' => $users,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'masterPotongans' => $masterPotongans,
        ]);
    }
}
