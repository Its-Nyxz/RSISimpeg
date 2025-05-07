<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Potongan;
use App\Models\GajiBruto;
use App\Models\MasterTrans;
use Illuminate\Support\Str;
use App\Models\MasterPotongan;

class AddPotongan extends Component
{
    public $user;
    public $potonganData;
    public $gajiBruto;
    public $bulan, $tahun;
    public $gapok = 0;
    public $masaKerjaTahun = 0;
    public $nom_makan = 0;
    public $nom_transport = 0;
    public $nom_jabatan = 0;
    public $nom_fungsi = 0;
    public $nom_umum = 0;
    public $notifMessage = '';
    public $showNotif = false;
    public $potonganInputs = []; // [master_potongan_id => nominal]
    public $masterPotongans = [];

    public function mount(User $user, $bulan = null, $tahun = null)
    {
        $this->bulan = $bulan ?? now()->month;
        $this->tahun = $tahun ?? now()->year;

        $this->user = User::with([
            'kategorijabatan.masterjabatan',
            'kategorijabatan.masterfungsi',
            'kategorijabatan.masterumum',
            'kategorifungsional',
            'golongan.gapoks',
        ])->findOrFail($user->id);

        $masterTrans = MasterTrans::first(); // atau where('nama', 'Tetap')->first() jika ada kondisi

        $this->nom_makan = $masterTrans->nom_makan ?? 0;
        $this->nom_transport = $masterTrans->nom_transport ?? 0;

        $masterTrans = MasterTrans::first();
        $this->nom_makan = $masterTrans->nom_makan ?? 0;
        $this->nom_transport = $masterTrans->nom_transport ?? 0;

        $kategori = $this->user->kategorijabatan;
        $fungsional = $this->user->kategorifungsional;

        $this->nom_jabatan = 0;
        $this->nom_fungsi  = 0;
        $this->nom_umum    = 0;

        if ($kategori) {
            // Normalisasi: hilangkan tanda kurung
            $jabatan_nama = strtolower(preg_replace('/\s*\(.*?\)/', '', $kategori->nama)); // "ka. instalasi"
            $tunjangan_type = $kategori->tunjangan;

            $nom_jabatan = optional($kategori)->nominal ?? 0;
            $nom_fungsi  = optional($fungsional)->nominal ?? 0;
            $nom_umum    = $tunjangan_type === 'umum' ? $kategori->nominal : 0;
            // $nom_fungsi    = $tunjangan_type === 'fungsi' ? $kategori->nominal : 0;

            $jabatan_struktural = ['ka. seksi', 'ka. instalasi', 'manajer', 'wadir'];
            $isFungsional = $this->user->fungsi_id !== null;
            $isStruktural = collect($jabatan_struktural)->contains(function ($item) use ($jabatan_nama) {
                return str_contains($jabatan_nama, strtolower($item));
            });

            if ($isFungsional && $isStruktural) {
                if (str_contains($jabatan_nama, 'ka. seksi') || str_contains($jabatan_nama, 'ka. instalasi')) {
                    $this->nom_fungsi = $nom_fungsi;
                    $this->nom_jabatan = $nom_jabatan * 0.5;
                } elseif (str_contains($jabatan_nama, 'manajer') || str_contains($jabatan_nama, 'wadir')) {
                    $this->nom_fungsi = $nom_fungsi;
                    $this->nom_jabatan = $nom_jabatan;
                }
            } else {
                switch ($tunjangan_type) {
                    case 'jabatan':
                        $this->nom_jabatan = $nom_jabatan;
                        break;
                    case 'fungsi':
                        $this->nom_fungsi = $nom_fungsi > 0 ? $nom_fungsi : $nom_jabatan;
                        break;
                    case 'umum':
                        $this->nom_umum = $nom_umum;
                        break;
                }
            }
        }

        // Hitung masa kerja tahun dari TMT
        $this->masaKerjaTahun = $this->user->tmt
            ? floor(Carbon::parse($this->user->tmt)->floatDiffInYears(Carbon::now()))
            : 0;
        // Ambil gaji pokok sesuai masa kerja
        $this->gapok = optional(
            $this->user->golongan?->gapoks
                ->where('masa_kerja', '<=', $this->masaKerjaTahun)
                ->sortByDesc('masa_kerja')
                ->first()
        )->nominal_gapok ?? 0;
        // dd($this->gapok);

        // $nom_jabatan = $this->user->kategorijabatan?->nominal ?? 0;
        $total_bruto = $this->gapok
            + $this->nom_jabatan
            + $this->nom_fungsi
            + $this->nom_umum
            + $this->nom_makan
            + $this->nom_transport;
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
                'nom_khusus' => 0,
                'nom_lainnya' => 0,
                'total_bruto' => $total_bruto,
                'created_at' => now(),
            ]
        );

        $this->masterPotongans = MasterPotongan::all();

        $existing = Potongan::where('bruto_id', $this->gajiBruto->id)
            ->get()
            ->keyBy('master_potongan_id');

        foreach ($this->masterPotongans as $item) {
            $existing = Potongan::where('bruto_id', $this->gajiBruto->id)
                ->where('master_potongan_id', $item->id)
                ->first();
            if ($existing) {
                $this->potonganInputs[$item->id] = $existing->nominal;
            } else {
                $nama = strtolower($item->nama);

                if (Str::contains($nama, ['tenaga kerja', 'bpjs tenaga kerja'])) {
                    // 3% dari semua komponen
                    $this->potonganInputs[$item->id] = round(
                        0.03 * (
                            $this->gapok +
                            $this->nom_jabatan +
                            $this->nom_fungsi +
                            $this->nom_umum +
                            $this->nom_transport +
                            $this->nom_makan
                        )
                    );
                } elseif (Str::contains($nama, ['bpjs kesehatan ortu', 'kesehatan ortu'])) {
                    $this->potonganInputs[$item->id] = $this->user->bpjs_ortu
                        ? round(0.01 * (
                            $this->gapok +
                            $this->nom_jabatan +
                            $this->nom_fungsi +
                            $this->nom_umum
                        ))
                        : 0;
                } elseif (Str::contains($nama, ['bpjs kesehatan', 'kesehatan'])) {
                    $this->potonganInputs[$item->id] = round(
                        0.01 * (
                            $this->gapok +
                            $this->nom_jabatan +
                            $this->nom_fungsi +
                            $this->nom_umum
                        )
                    );
                } else {
                    $this->potonganInputs[$item->id] = 0;
                }
            }
        }
    }

    public function simpan()
    {
        foreach ($this->masterPotongans as $potongan) {
            $nominal = $this->potonganInputs[$potongan->id] ?? null;

            // Validasi potongan wajib
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
        return view('livewire.add-potongan');
    }
}
