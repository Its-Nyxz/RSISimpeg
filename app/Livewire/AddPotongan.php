<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Potongan;
use App\Models\GajiBruto;
use App\Models\TaxBracket;
use App\Models\MasterTrans;
use Illuminate\Support\Str;
use App\Models\GapokKontrak;
use App\Models\MasterPotongan;

class AddPotongan extends Component
{
    public $user;
    public $potonganData;
    public $gajiBruto;
    public $isKaryawanTetap;
    public $bulan, $tahun;
    public $gapok = 0;
    public $masaKerjaTahun = 0;
    public $nom_makan = 0;
    public $nom_transport = 0;
    public $nom_jabatan = 0;
    public $nom_fungsi = 0;
    public $nom_umum = 0;
    public $nom_khusus = 0;
    public $notifMessage = '';
    public $showNotif = false;
    public $potonganInputs = []; // [master_potongan_id => nominal]
    public $masterPotongans = [];

    public function mount(User $user, $bulan = null, $tahun = null)
    {
        $this->bulan = $bulan ?? now()->month;
        $this->tahun = $tahun ?? now()->year;

        $this->user = User::with([
            'jenis',
            'kategorijabatan.masterjabatan',
            'kategorijabatan.masterfungsi',
            'kategorijabatan.masterumum',
            'kategorifungsional',
            'golongan.gapoks',
        ])->findOrFail($user->id);

        $masterTrans = MasterTrans::first(); // atau where('nama', 'Tetap')->first() jika ada kondisi
        $this->masaKerjaTahun = !is_null($this->user->masa_kerja)
            ? $this->user->masa_kerja
            : ($this->user->tmt
                ? floor(Carbon::parse($this->user->tmt)->floatDiffInYears(Carbon::now()))
                : 0);
        $base_makan = $masterTrans->nom_makan ?? 0;
        $base_transport = $masterTrans->nom_transport ?? 0;
        $this->nom_khusus = $this->user->khusus?->nominal ?? 0;

        // Reset nilai awal
        $this->nom_jabatan = 0;
        $this->nom_fungsi = 0;
        $this->nom_umum = 0;

        // Periode pembayaran: misalnya jika periode = 21 (bulan sebelumnya) s/d 20 (bulan ini)
        $periodeMulai = Carbon::create($this->tahun, $this->bulan, 21)->subMonth()->startOfDay();
        $periodeSelesai = Carbon::create($this->tahun, $this->bulan, 20)->endOfDay();

        // Ambil semua jadwal dalam periode
        $jadwalUser = $this->user->jadwalabsensi()
            ->whereBetween('tanggal_jadwal', [$periodeMulai->toDateString(), $periodeSelesai->toDateString()])
            ->get();

        // Hitung total hari dijadwalkan
        $totalHariJadwal = $jadwalUser->count();

        $absensiValid = $this->user->absen()
            ->whereIn('jadwal_id', $jadwalUser->pluck('id'))
            ->where('present', 1)
            ->distinct('jadwal_id') // pastikan hanya unik jadwal_id
            ->count('jadwal_id');

        // Proporsi kehadiran aktual
        $proporsiHybrid = $absensiValid / max($totalHariJadwal, 1);
        // Terapkan hanya ke makan dan transport
        $this->nom_makan = $base_makan * $proporsiHybrid;
        $this->nom_transport = $base_transport * $proporsiHybrid;

        // Ambil semua riwayat jabatan yang aktif selama bulan ini
        $riwayatJabatanAktif = $this->user->riwayatJabatan()
            ->where(function ($q) use ($periodeMulai, $periodeSelesai) {
                $q->whereDate('tanggal_mulai', '<=', $periodeSelesai)
                    ->where(function ($q2) use ($periodeMulai) {
                        $q2->whereNull('tanggal_selesai')
                            ->orWhere('tanggal_selesai', '>=', $periodeMulai);
                    });
            })
            ->with('kategori')
            ->get();

        foreach ($riwayatJabatanAktif as $riwayat) {
            $kategori = $riwayat->kategori;
            if (!$kategori) continue;

            $start = Carbon::parse(max($riwayat->tanggal_mulai, $periodeMulai));
            $end = Carbon::parse(min($riwayat->tanggal_selesai ?? $periodeSelesai, $periodeSelesai));

            // Hitung jadwal yang aktif selama masa jabatan ini
            $hariJadwalAktif = $jadwalUser->filter(function ($jadwal) use ($start, $end) {
                return Carbon::parse($jadwal->tanggal_jadwal)->between($start, $end);
            })->count();

            $proporsi = $hariJadwalAktif / max($totalHariJadwal, 1);
            $nominal = max(0, $kategori->nominal);
            $nama_jabatan = strtolower(preg_replace('/\s*\(.*?\)/', '', $kategori->nama));

            $isKaSeksiOrInstalasi = Str::contains($nama_jabatan, ['ka. seksi', 'ka. instalasi']);
            $isManajerOrWadir     = Str::contains($nama_jabatan, ['manajer', 'wadir']);

            switch ($riwayat->tunjangan) {
                case 'jabatan':
                    if ($isKaSeksiOrInstalasi) {
                        $this->nom_jabatan += $nominal * 0.5 * $proporsi;
                    } else {
                        $this->nom_jabatan += $nominal * $proporsi;
                    }
                    break;
                case 'fungsi':
                    $this->nom_fungsi += $nominal * $proporsi;
                    break;
                case 'umum':
                    $this->nom_umum += $nominal * $proporsi;
                    break;
            }
        }
        // dd($this->user->jenis);
        $this->isKaryawanTetap = strtolower($this->user->jenis?->nama ?? '') === 'tetap';
        $jenisKaryawan = strtolower($this->user->jenis?->nama ?? ''); // <- e.g. "part time", "kontrak", "magang"

        if (!$this->isKaryawanTetap) {
            if ($jenisKaryawan === 'kontrak' && $this->user->jabatan_id) {
                $gapokKontrak = GapokKontrak::where('kategori_jabatan_id', $this->user->jabatan_id)
                    ->where('min_masa_kerja', '<=', $this->masaKerjaTahun)
                    ->where('max_masa_kerja', '>=', $this->masaKerjaTahun)
                    ->first();

                $this->gapok = $gapokKontrak?->nominal ?? 0;
            } else {
                $this->gapok = GajiBruto::where('user_id', $this->user->id)
                    ->where('bulan_penggajian', $this->bulan)
                    ->where('tahun_penggajian', $this->tahun)
                    ->value('nom_gapok') ?? 0;
            }

            $total_bruto = $this->gapok + $this->nom_makan + $this->nom_transport;
            if ($jenisKaryawan === 'part time') {
                $total_bruto += $this->nom_jabatan + $this->nom_fungsi + $this->nom_umum;
            } else {
                $this->nom_jabatan = $this->nom_fungsi = $this->nom_umum = 0;
            }
        } else {
            $this->gapok = optional(
                $this->user->golongan?->gapoks
                    ->where('masa_kerja', '<=', $this->masaKerjaTahun)
                    ->sortByDesc('masa_kerja')
                    ->first()
            )->nominal_gapok ?? 0;

            $total_bruto = $this->gapok + $this->nom_jabatan + $this->nom_fungsi + $this->nom_umum
                + $this->nom_makan + $this->nom_transport + $this->nom_khusus;
        }
        $this->gajiBruto = GajiBruto::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'bulan_penggajian' => $this->bulan,
                'tahun_penggajian' => $this->tahun,
            ],
            [
                'nom_gapok'     => $this->gapok,
                'nom_jabatan'   => $this->nom_jabatan,
                'nom_fungsi'    => $this->nom_fungsi,
                'nom_umum'      => $this->nom_umum,
                'nom_makan'     => $this->nom_makan,
                'nom_transport' => $this->nom_transport,
                'nom_khusus' => $this->nom_khusus,
                'nom_lainnya' => 0,
                'total_bruto' => $total_bruto,
                'created_at' => now(),
            ]
        );

        $this->masterPotongans = MasterPotongan::all();
        $this->updatePotonganInputs();
    }

    public function updatedGapok()
    {
        $this->updateGajiBruto();
        $this->updatePotonganInputs();
    }

    protected function updateGajiBruto()
    {
        $jenisKaryawan = strtolower($this->user->jenis->nama);

        if ($this->isKaryawanTetap) {
            $total_bruto = $this->gapok
                + $this->nom_jabatan
                + $this->nom_fungsi
                + $this->nom_umum
                + $this->nom_makan
                + $this->nom_transport
                + $this->nom_khusus;
        } else {
            $total_bruto = $this->gapok
                + $this->nom_makan
                + $this->nom_transport;

            if ($jenisKaryawan === 'part time') {
                $total_bruto += $this->nom_jabatan + $this->nom_fungsi;
            }
        }

        $this->gajiBruto->update([
            'total_bruto' => $total_bruto,
            'nom_gapok'   => $this->gapok,
        ]);
    }

    protected function updatePotonganInputs()
    {
        $this->potonganInputs = [];

        if (!$this->isKaryawanTetap) return;

        $bruto = $this->gajiBruto->total_bruto;
        $gapok = $this->gapok;
        $tunjangan = $this->nom_jabatan + $this->nom_fungsi + $this->nom_umum;
        $makanTransport = $this->nom_makan + $this->nom_transport;

        foreach ($this->masterPotongans as $item) {
            $slug = $item->slug;
            $nominal = 0;

            if (Str::contains($slug, 'pph')) {
                $kategoriInduk = $this->user->kategoriPphInduk();
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
                $nominal = $this->user->bpjs_ortu
                    ? round(0.01 * ($gapok + $tunjangan + $makanTransport))
                    : 0;
            } elseif (
                Str::contains($slug, 'bpjs-kesehatan') &&
                !Str::contains($slug, 'ortu') &&
                !Str::contains($slug, 'rekonsiliasi')
            ) {
                $nominal = round(0.01 * ($gapok + $tunjangan + $makanTransport));
            }

            if ($nominal > 0) {
                $this->potonganInputs[$item->id] = $nominal;
            }
        }
    }



    public function simpan()
    {
        if (!$this->isKaryawanTetap && (!is_numeric($this->gapok) || $this->gapok <= 0)) {
            $this->notifMessage = 'Gaji pokok harus diisi untuk karyawan non-tetap.';
            $this->showNotif = true;
            return;
        }

        foreach ($this->masterPotongans as $potongan) {
            $nominal = $this->potonganInputs[$potongan->id] ?? null;

            if ($potongan->is_wajib && (!is_numeric($nominal) || $nominal <= 0)) {
                $this->notifMessage = 'Potongan wajib "' . strtoupper(str_replace('_', ' ', $potongan->nama))  . '" tidak boleh kosong atau nol.';
                $this->showNotif = true;
                return;
            }

            Potongan::updateOrCreate(
                [
                    'bruto_id' => $this->gajiBruto->id,
                    'master_potongan_id' => $potongan->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ],
                ['nominal' => (int) $nominal]
            );
        }

        return redirect()->route('detailkeuangan.show', $this->user->id)->with('success', 'Data potongan berhasil disimpan');
    }

    public function getTotalPotonganProperty()
    {
        return collect($this->potonganInputs)->sum();
    }

    public function render()
    {
        return view('livewire.add-potongan', [
            'isKaryawanTetap' => $this->isKaryawanTetap,
            'jenisKaryawan' => strtolower($this->user->jenis?->nama ?? ''),
        ]);
    }
}
