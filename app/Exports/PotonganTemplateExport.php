<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\GajiBruto;
use App\Models\TaxBracket;
use App\Models\MasterTrans;
use Illuminate\Support\Str;
use App\Models\GapokKontrak;
use App\Models\MasterPotongan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PotonganTemplateExport implements FromView
{
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected $unitId = null,
        protected $jenisId = null,
        protected $keyword = null
    ) {}

    public function view(): View
    {
        $users = User::with([
            'unitKerja',
            'jenis',
            'golongan.gapoks',
            'kategorijabatan.masterjabatan',
            'kategorijabatan.masterfungsi',
            'kategorijabatan.masterumum',
            'riwayatJabatan.kategori',
            'khusus',
        ])
            ->when($this->unitId, fn($q) => $q->where('unit_id', $this->unitId))
            ->when($this->jenisId, fn($q) => $q->where('jenis_id', $this->jenisId))
            ->when($this->keyword, fn($q) => $q->where('name', 'like', "%{$this->keyword}%"))
            ->get();

        $masterPotongans = MasterPotongan::all();
        $masterTrans = MasterTrans::first();
        $periodeMulai = Carbon::create($this->tahun, $this->bulan, 21)->subMonth()->startOfDay();
        $periodeSelesai = Carbon::create($this->tahun, $this->bulan, 20)->endOfDay();

        foreach ($users as $user) {
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

            $jadwalIds = $jadwalUser->pluck('id');

            $absensiValid = $user->jadwalabsensi()
                ->whereIn('id', $jadwalIds)
                ->where(function ($query) {
                    $query->whereHas('absensi', function ($q) {
                        $q->where('present', 1);
                    })
                        ->orWhereHas('shift', function ($q) {
                            $q->whereNull('jam_masuk')->whereNull('jam_keluar');
                        });
                })
                ->count();

            $proporsiHybrid = $absensiValid / max($totalHariJadwal, 1);
            $nom_makan = ($masterTrans?->nom_makan ?? 0) * $proporsiHybrid;
            $nom_transport = ($masterTrans?->nom_transport ?? 0) * $proporsiHybrid;

            if ($jenis === 'tetap') {
                $gapok = optional(
                    $user->golongan?->gapoks
                        ->where('masa_kerja', '<=', $masaKerja)
                        ->sortByDesc('masa_kerja')
                        ->first()
                )?->nominal_gapok ?? 0;
            } elseif ($jenis === 'kontrak') {
                $gapokKontrak = GapokKontrak::where('kategori_jabatan_id', $user->jabatan_id)
                    ->where('min_masa_kerja', '<=', $masaKerja)
                    ->where('max_masa_kerja', '>=', $masaKerja)
                    ->first();
                $gapok = $gapokKontrak?->nominal ?? 0;
            }

            $riwayats = $user->riwayatJabatan->filter(function ($r) use ($periodeMulai, $periodeSelesai) {
                $start = Carbon::parse($r->tanggal_mulai);
                $end = $r->tanggal_selesai ? Carbon::parse($r->tanggal_selesai) : $periodeSelesai;
                return $start <= $periodeSelesai && $end >= $periodeMulai;
            });

            foreach ($riwayats as $r) {
                $kategori = $r->kategori;
                if (!$kategori) continue;

                $nom = $kategori->nominal ?? 0;
                $nama_jabatan = strtolower(preg_replace('/\s*\(.*?\)/', '', $kategori->nama));
                $isKaSeksi = Str::contains($nama_jabatan, ['ka. seksi', 'ka. instalasi']);

                $start = Carbon::parse(max($r->tanggal_mulai, $periodeMulai));
                $end = Carbon::parse(min($r->tanggal_selesai ?? $periodeSelesai, $periodeSelesai));
                $hariJadwalAktif = $jadwalUser->filter(fn($j) => Carbon::parse($j->tanggal_jadwal)->between($start, $end))->count();
                $proporsi = $hariJadwalAktif / max($totalHariJadwal, 1);

                switch ($r->tunjangan) {
                    case 'jabatan':
                        $nom_jabatan += ($isKaSeksi ? 0.5 : 1) * $nom * $proporsi;
                        break;
                    case 'fungsi':
                        $nom_fungsi += $nom * $proporsi;
                        break;
                    case 'umum':
                        $nom_umum += $nom * $proporsi;
                        break;
                }
            }

            $user->setAttribute('nom_gapok', $gapok);
            $user->setAttribute('nom_jabatan', $nom_jabatan);
            $user->setAttribute('nom_fungsi', $nom_fungsi);
            $user->setAttribute('nom_umum', $nom_umum);
            $user->setAttribute('nom_khusus', $nom_khusus);
            $user->setAttribute('nom_makan', round($nom_makan));
            $user->setAttribute('nom_transport', round($nom_transport));
            $user->setAttribute('total_bruto', round($gapok + $nom_jabatan + $nom_fungsi + $nom_umum + $nom_khusus + $nom_makan + $nom_transport));

            // Potongan Otomatis
            $potonganOtomasis = [];
            $bruto = $user->total_bruto;
            $tunjangan = $nom_jabatan + $nom_fungsi + $nom_umum;
            $makanTransport = $nom_makan + $nom_transport;

            foreach ($masterPotongans as $item) {
                $slug = $item->slug;
                $nominal = 0;

                if (Str::contains($slug, 'pph')) {
                    $kategoriInduk = $user->kategoriPphInduk();
                    if ($kategoriInduk) {
                        $tax = TaxBracket::where('kategoripph_id', $kategoriInduk->id)
                            ->where('upper_limit', '>=', $bruto)
                            ->orderBy('upper_limit')
                            ->first();
                        $persen = $tax?->persentase ?? 0;
                        $nominal = round($bruto * $persen);
                    }
                } elseif (Str::contains($slug, 'bpjs-tenaga-kerja')) {
                    $nominal = round(0.03 * ($gapok + $tunjangan));
                } elseif (Str::contains($slug, 'bpjs-kesehatan-ortu')) {
                    $nominal = $user->bpjs_ortu ? round(0.01 * ($gapok + $tunjangan + $makanTransport)) : 0;
                } elseif (
                    Str::contains($slug, 'bpjs-kesehatan') &&
                    !Str::contains($slug, 'ortu') &&
                    !Str::contains($slug, 'rekonsiliasi')
                ) {
                    $nominal = round(0.01 * ($gapok + $tunjangan + $makanTransport));
                }

                if ($nominal > 0) {
                    $potonganOtomasis[$item->nama] = $nominal; // output tetap nama untuk tampil di Excel
                }
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
