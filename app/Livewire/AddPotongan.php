<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Potongan;
use App\Models\GajiBruto;
use App\Models\MasterTrans;

class AddPotongan extends Component
{
    public $user;
    public $potonganData;
    public $gajiBruto;
    public $bulan, $tahun;
    public $simpanan_wajib, $simpanan_pokok, $ibi, $idi, $ppni, $pinjam_kop,
        $obat, $a_b, $a_p, $dansos, $dplk, $bpjs_tk, $bpjs_kes,
        $rek_bpjs_kes, $bpjs_tambahan, $pph_21, $pph_kurang,
        $angsuran_kurban, $amaliah, $ranap, $potongan_selisih, $perkasi, $lain_lain;

    public $gapok = 0;
    public $masaKerjaTahun = 0;
    public $nom_makan = 0;
    public $nom_transport = 0;
    public $nom_jabatan = 0;
    public $nom_fungsi = 0;
    public $nom_umum = 0;

    public function mount(User $user, $bulan = null, $tahun = null)
    {
        $this->bulan = $bulan ?? now()->month;
        $this->tahun = $tahun ?? now()->year;

        $this->user = User::with([
            'kategorijabatan.masterjabatan',
            'kategorijabatan.masterfungsi',
            'kategorijabatan.masterumum',
            'golongan.gapoks',
        ])->findOrFail($user->id);

        $masterTrans = MasterTrans::first(); // atau where('nama', 'Tetap')->first() jika ada kondisi

        $this->nom_makan = $masterTrans->nom_makan ?? 0;
        $this->nom_transport = $masterTrans->nom_transport ?? 0;

        $kategori = $this->user->kategorijabatan;

        $this->nom_jabatan = 0;
        $this->nom_fungsi = 0;
        $this->nom_umum   = 0;
        if ($kategori) {
            switch ($kategori->tunjangan) {
                case 'jabatan':
                    // Ambil dari kategori langsung
                    $this->nom_jabatan = $kategori->nominal ?? 0;
                    break;

                case 'fungsi':
                    $this->nom_fungsi = optional($kategori->masterfungsi->first())->nominal ?? 0;
                    break;

                case 'umum':
                    $this->nom_umum = optional($kategori->masterumum->first())->nominal ?? 0;
                    break;
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

        $gajiBruto = GajiBruto::with('potongan')
            ->where('user_id', $user->id)
            ->where('tahun_penggajian', $this->tahun)
            ->where('bulan_penggajian', $this->bulan)
            ->first();

        $this->potonganData = $gajiBruto?->potongan;
        if ($this->potonganData) {
            $this->simpanan_wajib = $this->potonganData->simpanan_wajib;
            $this->simpanan_pokok = $this->potonganData->simpanan_pokok;
            $this->ibi = $this->potonganData->ibi;
            $this->idi = $this->potonganData->idi;
            $this->ppni = $this->potonganData->ppni;
            $this->pinjam_kop = $this->potonganData->pinjam_kop;
            $this->obat = $this->potonganData->obat;
            $this->a_b = $this->potonganData->a_b;
            $this->a_p = $this->potonganData->a_p;
            $this->dansos = $this->potonganData->dansos;
            $this->dplk = $this->potonganData->dplk;
            $this->bpjs_tk = $this->potonganData->bpjs_tk;
            $this->bpjs_kes = $this->potonganData->bpjs_kes;
            $this->rek_bpjs_kes = $this->potonganData->rek_bpjs_kes;
            $this->bpjs_tambahan = $this->potonganData->bpjs_tambahan;
            $this->pph_21 = $this->potonganData->pph_21;
            $this->pph_kurang = $this->potonganData->pph_kurang;
            $this->angsuran_kurban = $this->potonganData->angsuran_kurban;
            $this->amaliah = $this->potonganData->amaliah;
            $this->ranap = $this->potonganData->ranap;
            $this->potongan_selisih = $this->potonganData->potongan_selisih;
            $this->perkasi = $this->potonganData->perkasi;
            $this->lain_lain = $this->potonganData->lain_lain;
        }
    }
    public function getTotalPotonganProperty()
    {
        return collect([
            $this->simpanan_wajib,
            $this->simpanan_pokok,
            $this->ibi,
            $this->idi,
            $this->ppni,
            $this->pinjam_kop,
            $this->obat,
            $this->a_b,
            $this->a_p,
            $this->dansos,
            $this->dplk,
            $this->bpjs_tk,
            $this->bpjs_kes,
            $this->rek_bpjs_kes,
            $this->bpjs_tambahan,
            $this->pph_21,
            $this->pph_kurang,
            $this->angsuran_kurban,
            $this->amaliah,
            $this->ranap,
            $this->potongan_selisih,
            $this->perkasi,
            $this->lain_lain,
        ])->sum();
    }

    public function simpan()
    {
        // Simpan atau update data potongan
        Potongan::updateOrCreate(
            ['bruto_id' => $this->gajiBruto->id],
            [
                'simpanan_wajib' => $this->simpanan_wajib,
                'simpanan_pokok' => $this->simpanan_pokok,
                'ibi' => $this->ibi,
                'idi' => $this->idi,
                'ppni' => $this->ppni,
                'pinjam_kop' => $this->pinjam_kop,
                'obat' => $this->obat,
                'a_b' => $this->a_b,
                'a_p' => $this->a_p,
                'dansos' => $this->dansos,
                'dplk' => $this->dplk,
                'bpjs_tk' => $this->bpjs_tk,
                'bpjs_kes' => $this->bpjs_kes,
                'rek_bpjs_kes' => $this->rek_bpjs_kes,
                'bpjs_tambahan' => $this->bpjs_tambahan,
                'pph_21' => $this->pph_21,
                'pph_kurang' => $this->pph_kurang,
                'angsuran_kurban' => $this->angsuran_kurban,
                'amaliah' => $this->amaliah,
                'ranap' => $this->ranap,
                'potongan_selisih' => $this->potongan_selisih,
                'perkasi' => $this->perkasi,
                'lain_lain' => $this->lain_lain,
                'created_at' => now(),
            ]
        );

        return redirect()->route('detailkeuangan.show', $this->user->id)->with('success', 'Data potongan berhasil disimpan');
    }

    public function render()
    {
        return view('livewire.add-potongan');
    }
}
