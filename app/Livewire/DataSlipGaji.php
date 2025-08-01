<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GajiNetto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataSlipGaji extends Component
{
    public $tahun;
    public $slips = [];
    public $selectedSlip = null;

    public function mount()
    {
        $this->tahun = now()->year;
        $this->loadSlips();
    }

    public function updatedTahun()
    {
        $this->loadSlips();
    }

    protected function loadSlips()
    {
        $user = Auth::user();

        $this->slips = GajiNetto::whereHas('bruto', function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->where('tahun_penggajian', $this->tahun);
        })
            ->with(['bruto.potongan.masterPotongan'])
            ->latest('tanggal_transfer')
            ->get();
    }

    public function showDetail($id)
    {
        $this->selectedSlip = GajiNetto::with(['bruto.potongan.masterPotongan', 'bruto.user.jenis'])
            ->find($id);
    }

    public function closeDetail()
    {
        $this->selectedSlip = null;
    }

    public function render()
    {
        return view('livewire.data-slip-gaji');
    }
}
