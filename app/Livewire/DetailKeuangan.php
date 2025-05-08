<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Potongan;
use App\Models\GajiBruto;

class DetailKeuangan extends Component
{
    public $user;
    public $gajiBruto;
    public $isKaryawanTetap;
    public $bulan, $tahun;
    public $dynamicPotongans = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadData();
    }

    public function updatedBulan()
    {
        $this->loadData();
    }
    public function updatedTahun()
    {
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
            $potonganList = Potongan::with('masterPotongan')
                ->where('bruto_id', $this->gajiBruto->id)
                ->get();

            $this->dynamicPotongans = $potonganList->mapWithKeys(function ($item) {
                return [$item->masterPotongan->nama => $item->nominal];
            })->toArray();
        } else {
            $this->dynamicPotongans = [];
        }

        $this->isKaryawanTetap = strtolower($this->user->jenis?->nama ?? '') === 'tetap';
    }

    public function getTotalPotonganProperty()
    {
        return collect($this->dynamicPotongans)->sum();
    }

    public function render()
    {
        return view('livewire.detail-keuangan', [
            'jenisKaryawan' => strtolower($this->user->jenis?->nama ?? ''),
            'isKaryawanTetap' => $this->isKaryawanTetap,
        ]);
    }
}
