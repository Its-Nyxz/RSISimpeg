<?php

namespace App\Livewire;

use App\Models\JadwalAbsensi;
use App\Models\OpsiAbsen;
use App\Models\Shift;
use Livewire\Component;

class DataJadwal extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $jadwals = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->jadwals = JadwalAbsensi::with('user', 'shift', 'opsi') // Eager load relationships
            ->when($this->search, function ($query) {
                $query->where('tanggal_jadwal', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan_absen', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('shift', function ($q) {
                        $q->where('nama_shift', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('opsi', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->get()
            ->toArray();
    }
    
    
    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.data-jadwal');
    }
}