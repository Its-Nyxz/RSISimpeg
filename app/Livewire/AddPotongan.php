<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Potongan;
use App\Models\GajiBruto;

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

    public function mount(User $user, $bulan = null, $tahun = null)
    {
        $this->bulan = $bulan ?? now()->month;
        $this->tahun = $tahun ?? now()->year;

        $this->user = User::with([
            'kategorijabatan.masterjabatan',
            'kategorijabatan.masterfungsi',
            'kategorijabatan.masterumum',
        ])->findOrFail($user->id);

        $nom_jabatan = $this->user->kategorijabatan?->nominal ?? 0;
        $this->gajiBruto = GajiBruto::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'bulan_penggajian' => $this->bulan,
                'tahun_penggajian' => $this->tahun,
            ],
            [
                'nom_jabatan' => $nom_jabatan,
                'nom_khusus' => 0,
                'nom_lainnya' => 0,
                'total_bruto' => $nom_jabatan,
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
