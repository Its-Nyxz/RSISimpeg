<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\Auth;

class DataPeringatan extends Component
{
    public $tahun;
    public $peringatans = [];
    public $selectedPeringatan = null;

    public function mount()
    {
        $this->tahun = now()->year;
        $this->loadPeringatan();
    }

    public function updatedTahun()
    {
        $this->loadPeringatan();
    }

    protected function loadPeringatan()
    {
        $user = Auth::user();

        $this->peringatans = PeringatanKaryawan::where('user_id', $user->id)
            ->whereYear('tanggal_sp', $this->tahun)
            ->orderByDesc('tanggal_sp')
            ->get();
    }

    public function showDetail($id)
    {
        $this->selectedPeringatan = PeringatanKaryawan::with('user')->find($id);
    }

    public function closeDetail()
    {
        $this->selectedPeringatan = null;
    }

    public function render()
    {
        return view('livewire.data-peringatan');
    }
}
