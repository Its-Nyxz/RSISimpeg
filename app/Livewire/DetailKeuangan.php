<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\GajiBruto;

class DetailKeuangan extends Component
{
    public $user;
    public $potonganData;
    public $gajiBruto;
    public $bulan, $tahun;
    public $gapok = 0;
    public $nom_jabatan = 0;
    public $nom_fungsi = 0;
    public $nom_umum = 0;
    public $nom_transport = 0;
    public $nom_makan = 0;

    public $dynamicPotongans = []; // [nama => nominal]

    public function updatedBulan()
    {
        $this->loadData();
    }

    public function updatedTahun()
    {
        $this->loadData();
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadData();
    }


    public function loadData()
    {
        $this->gajiBruto = GajiBruto::with('potongan')
            ->where('user_id', $this->user->id)
            ->where('tahun_penggajian', $this->tahun)
            ->where('bulan_penggajian', $this->bulan)
            ->first();

        if ($this->gajiBruto) {
            $this->gapok         = $this->gajiBruto->nom_gapok;
            $this->nom_jabatan   = $this->gajiBruto->nom_jabatan;
            $this->nom_fungsi    = $this->gajiBruto->nom_fungsi;
            $this->nom_umum      = $this->gajiBruto->nom_umum;
            $this->nom_transport = $this->gajiBruto->nom_transport;
            $this->nom_makan     = $this->gajiBruto->nom_makan;

            // Ambil potongan dinamis dari tabel `potongan`
            $potonganList = \App\Models\Potongan::with('masterPotongan')
                ->where('bruto_id', $this->gajiBruto->id)
                ->get();

            $this->dynamicPotongans = $potonganList->mapWithKeys(function ($item) {
                return [$item->masterPotongan->nama => $item->nominal];
            })->toArray();
        } else {
            $this->dynamicPotongans = [];
        }
    }

    public function getTotalPotonganProperty()
    {
        return collect($this->dynamicPotongans)->sum();
    }

    public function render()
    {
        return view('livewire.detail-keuangan');
    }
}
