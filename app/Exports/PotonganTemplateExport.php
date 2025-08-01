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
        $users = User::where('id', '!=', 1)
            ->with([
                'unitKerja',
                'jenis',
                'golongan.gapoks',
                'kategorijabatan.masterjabatan',
                'kategorijabatan.masterfungsi',
                'kategorijabatan.masterumum',
                // 'kategorijabatan',
                // 'kategorifungsional',
                // 'kategoriumum',
                'riwayatJabatan.kategori',
                'khusus',
            ])
            ->when($this->unitId, fn($q) => $q->where('unit_id', $this->unitId))
            ->when($this->jenisId, fn($q) => $q->where('jenis_id', $this->jenisId))
            ->when($this->keyword, fn($q) => $q->where('name', 'like', "%{$this->keyword}%"))
            ->get();

        $masterPotongans = MasterPotongan::orderBy('id')->get();
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

            if ($gajiBruto && $existingPotongan->count() > 0) {
                // Ambil data dari GajiBruto
                $user->setAttribute('nom_gapok', $gajiBruto->nom_gapok);
                $user->setAttribute('nom_jabatan', $gajiBruto->nom_jabatan);
                $user->setAttribute('nom_fungsi', $gajiBruto->nom_fungsi);
                $user->setAttribute('nom_umum', $gajiBruto->nom_umum);
                $user->setAttribute('nom_khusus', $gajiBruto->nom_khusus);
                $user->setAttribute('nom_makan', $gajiBruto->nom_makan);
                $user->setAttribute('nom_transport', $gajiBruto->nom_transport);
                $user->setAttribute('nom_lainnya', $gajiBruto->nom_lainnya);
                $user->setAttribute('total_bruto', $gajiBruto->total_bruto);

                // Ambil potongan yang sudah tersimpan
                $potonganData = $existingPotongan->mapWithKeys(fn($p) => [
                    $p->masterPotongan->nama => $p->nominal,
                ]);
                $user->setAttribute('potonganOtomasis', $potonganData->toArray());

                continue; // skip proses kalkulasi otomatis
            }

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
            // Cek apakah semua shift adalah libur (jam masuk & keluar null)
            $semuaLibur = $jadwalUser->every(function ($jadwal) {
                $namaShift = strtolower(optional($jadwal->shift)->nama_shift);
                return in_array($namaShift, ['l', 'libur']);
            });

            $semuaC = $jadwalUser->every(function ($jadwal) {
                return in_array(strtolower(optional($jadwal->shift)->nama_shift), ['c', 'cuti']);
            });
            // Jika semua shift libur dan ada jadwal
            $liburTotalSebulan = $semuaLibur && !$semuaC && $totalHariJadwal > 0;

            if ($liburTotalSebulan) {
                // Semua shift libur → tidak mendapatkan gaji dan tunjangan
                $gapok = 0;
                $nom_jabatan = 0;
                $nom_fungsi = 0;
                $nom_umum = 0;
                $nom_khusus = 0;
                $nom_makan = 0;
                $nom_transport = 0;
                $tukin = 0;
            } elseif ($semuaC) {
                // Semua cuti → hanya dapat gapok
                if ($jenis === 'tetap') {
                    $gapok = optional(
                        $user->golongan?->gapoks
                            ->where('masa_kerja', '<=', $masaKerja)
                            ->sortByDesc('masa_kerja')
                            ->first()
                    )?->nominal_gapok ?? 0;
                } elseif ($jenis === 'kontrak') {
                    $kategoriJabatanId = $user->jabatan_id
                        ?? $user->fungsi_id
                        ?? $user->umum_id;
                    $pendidikanId = $user->kategori_pendidikan;

                    $gapokKontrak = GapokKontrak::where('kategori_jabatan_id', $kategoriJabatanId)
                        ->where('pendidikan_id', $pendidikanId)
                        ->where('min_masa_kerja', '<=', $masaKerja)
                        ->where('max_masa_kerja', '>=', $masaKerja)
                        ->first();
                    $gapok = $gapokKontrak?->nominal_aktif ?? $gapokKontrak?->nominal ?? 0;
                }

                $nom_jabatan = 0;
                $nom_fungsi = 0;
                $nom_umum = 0;
                $nom_khusus = 0;
                $nom_makan = 0;
                $nom_transport = 0;
                $tukin = 0;

                // Potongan Otomatis
                $potonganOtomasis = [];
                $bruto = $user->total_bruto;
                $tunjangan = $nom_jabatan + $nom_fungsi + $nom_umum;
                $makanTransport = $nom_makan + $nom_transport;

                $total_bruto = $gapok;
                $user->setAttribute('nom_gapok', $gapok);
                $user->setAttribute('nom_jabatan', 0);
                $user->setAttribute('nom_fungsi', 0);
                $user->setAttribute('nom_umum', 0);
                $user->setAttribute('nom_khusus', 0);
                $user->setAttribute('nom_makan', 0);
                $user->setAttribute('nom_transport', 0);
                $user->setAttribute('nom_lainnya', 0);
                $user->setAttribute('total_bruto', $total_bruto);

                // Sekarang bisa pakai
                $bruto = $total_bruto;

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

                    $isDok = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'dokter')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'dokter')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'dokter');
                    $isGigi = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'dokter gigi')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'dokter gigi')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'dokter gigi');
                    $isBidan = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'bidan')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'bidan')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'bidan');
                    $isPerawat = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'perawat')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'perawat')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'perawat');

                    if ($slug === 'idi' && $isDok && !$isGigi) {
                        $nominal = $item->nominal;
                    } elseif ($slug === 'ibi' && $isBidan) {
                        $nominal = $item->nominal;
                    } elseif ($slug === 'ppni' && $isPerawat) {
                        $nominal = $item->nominal;
                    }

                    if ($slug === 'dansos-karyawan') {
                        if ($jenis === 'tetap') {
                            $nominal = round(0.005 * ($gapok + $tunjangan));
                        } elseif ($jenis === 'kontrak') {
                            $nominal = round(0.0025 * $bruto);
                        }
                    }

                    // Tampilkan semua, meskipun 0
                    $potonganOtomasis[$item->nama] = $nominal;
                }

                $user->setAttribute('potonganOtomasis', $potonganOtomasis);
            } else {
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
                // $nom_makan = ($masterTrans?->nom_makan ?? 0) * $proporsiHybrid;
                // $nom_transport = ($masterTrans?->nom_transport ?? 0) * $proporsiHybrid;
                $nom_makan = $masterTrans?->nom_makan ?? 0;
                $nom_transport = $masterTrans?->nom_transport ?? 0;

                if ($jenis === 'tetap') {
                    $gapok = optional(
                        $user->golongan?->gapoks
                            ->where('masa_kerja', '<=', $masaKerja)
                            ->sortByDesc('masa_kerja')
                            ->first()
                    )?->nominal_gapok ?? 0;
                } elseif ($jenis === 'kontrak') {
                    $kategoriJabatanId = $user->jabatan_id
                        ?? $user->fungsi_id
                        ?? $user->umum_id;
                    $pendidikanId = $user->kategori_pendidikan;

                    $gapokKontrak = GapokKontrak::where('kategori_jabatan_id', $kategoriJabatanId)
                        ->where('pendidikan_id', $pendidikanId)
                        ->where('min_masa_kerja', '<=', $masaKerja)
                        ->where('max_masa_kerja', '>=', $masaKerja)
                        ->first();
                    $gapok = $gapokKontrak?->nominal_aktif ?? $gapokKontrak?->nominal ?? 0;
                }

                $riwayats = $user->riwayatJabatan->filter(function ($r) use ($periodeMulai, $periodeSelesai) {
                    $start = Carbon::parse($r->tanggal_mulai);
                    $end = $r->tanggal_selesai ? Carbon::parse($r->tanggal_selesai) : $periodeSelesai;
                    return $start <= $periodeSelesai && $end >= $periodeMulai;
                });
                $isKaryawanTetap = strtolower($user->jenis?->nama ?? '') === 'tetap';
                if ($isKaryawanTetap) {
                    foreach ($riwayats as $riwayat) {
                        $kategori = $riwayat->kategori;
                        if (!$kategori) continue;

                        $start = Carbon::parse(max($riwayat->tanggal_mulai, $periodeMulai));
                        $end = Carbon::parse(min($riwayat->tanggal_selesai ?? $periodeSelesai, $periodeSelesai));

                        $hariJadwalAktif = $jadwalUser->filter(function ($jadwal) use ($start, $end) {
                            return Carbon::parse($jadwal->tanggal_jadwal)->between($start, $end);
                        })->count();

                        // $proporsi = $totalHariJadwal > 0
                        //     ? ($hariJadwalAktif / $totalHariJadwal)
                        //     : 1;
                        $proporsi = $hariJadwalAktif / max($totalHariJadwal, 1);

                        $nominal = max(0, $kategori->nominal);
                        $nama_jabatan = strtolower(preg_replace('/\s*\(.*?\)/', '', $kategori->nama));

                        $isKaSeksiOrInstalasi = Str::contains($nama_jabatan, ['ka. seksi', 'ka. instalasi']);
                        $isManajerOrWadir     = Str::contains($nama_jabatan, ['manajer', 'wadir']);

                        switch ($riwayat->tunjangan) {
                            case 'jabatan':
                                if ($isKaSeksiOrInstalasi) {
                                    $nom_jabatan += $nominal * 0.5 * $proporsi;
                                } else {
                                    $nom_jabatan += $nominal * $proporsi;
                                }
                                break;
                            case 'fungsi':
                                $nom_fungsi += $nominal * $proporsi;
                                break;
                            case 'umum':
                                $nom_umum += $nominal * $proporsi;
                                break;
                        }
                    }
                }


                $user->setAttribute('nom_gapok', $gapok);
                $user->setAttribute('nom_jabatan', $nom_jabatan);
                $user->setAttribute('nom_fungsi', $nom_fungsi);
                $user->setAttribute('nom_umum', $nom_umum);
                $user->setAttribute('nom_makan', round($nom_makan));
                $user->setAttribute('nom_transport', round($nom_transport));
                $user->setAttribute('nom_khusus', $nom_khusus);
                $tukin = 0; // default manual
                $total_bruto = $gapok + $nom_jabatan + $nom_fungsi + $nom_umum + $nom_khusus + $nom_makan + $nom_transport + $tukin;

                $user->setAttribute('nom_lainnya', $tukin);
                $user->setAttribute('total_bruto', round($total_bruto));

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

                    $isDok = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'dokter')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'dokter')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'dokter');
                    $isGigi = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'dokter gigi')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'dokter gigi')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'dokter gigi');
                    $isBidan = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'bidan')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'bidan')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'bidan');
                    $isPerawat = Str::contains(strtolower($user->kategorijabatan->nama ?? ''), 'perawat')
                        || Str::contains(strtolower($user->kategorifungsional->nama ?? ''), 'perawat')
                        || Str::contains(strtolower($user->kategoriumum->nama ?? ''), 'perawat');

                    if ($slug === 'idi' && $isDok && !$isGigi) {
                        $nominal = $item->nominal;
                    } elseif ($slug === 'ibi' && $isBidan) {
                        $nominal = $item->nominal;
                    } elseif ($slug === 'ppni' && $isPerawat) {
                        $nominal = $item->nominal;
                    }

                    if ($slug === 'dansos-karyawan') {
                        if ($jenis === 'tetap') {
                            $nominal = round(0.005 * ($gapok + $tunjangan));
                        } elseif ($jenis === 'kontrak') {
                            $nominal = round(0.0025 * $bruto);
                        }
                    }

                    // Tampilkan semua, meskipun 0
                    $potonganOtomasis[$item->nama] = $nominal;
                }

                $user->setAttribute('potonganOtomasis', $potonganOtomasis);
            }
        }
        logger("Cek GajiBruto user {$user->id}: ", ['data' => $gajiBruto]);
        return view('exports.template-potongan', [
            'users' => $users,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'masterPotongans' => $masterPotongans,
        ]);
    }
}
