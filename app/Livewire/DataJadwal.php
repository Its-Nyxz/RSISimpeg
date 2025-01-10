<?php

namespace App\Livewire;

use App\Models\JadwalAbsensi;
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
        $this->jadwals = JadwalAbsensi::when( $this->search, function($query){
            $query->where('tanggal_jadwal', 'like', '%' . $this->search . '%') // Mencari berdasarkan tanggal jadwal
                    ->orWhere('keterangan_absen', 'like', '%' . $this->search . '%') // Mencari berdasarkan keterangan absen
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%'); // Mencari berdasarkan nama user
                    })
                    ->orWhereHas('shift', function ($q) {
                        $q->where('nama_shift', 'like', '%' . $this->search . '%'); // Mencari berdasarkan nama shift
                    })
                    ->orWhereHas('opsi_absens', function ($q) {
                        $q->where('nama_opsi', 'like', '%' . $this->search . '%'); // Mencari berdasarkan nama opsi absensi
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
