<?php

namespace App\Livewire;

use App\Models\OpsiAbsen;
use App\Models\Shift;
use App\Models\JadwalAbsensi;
use App\Models\StatusAbsen;
use Livewire\Component;

class DataAbsensi extends Component
{
    public $type; // Type: jabatan, fungsional, umum, tidak tetap
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $items = [];

    public function mount($type)
    {
        $this->type = $type;

        $this->loadData();
    }

    public function loadData()
    {
        // Load data sesuai dengan tipe dan tambahkan pencarian
        $this->items = match ($this->type) {
            'jadwalabsen' => JadwalAbsensi::when($this->search, function ($query) {
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
            })->get()->toArray(),
            'shift' => Shift::when($this->search, function ($query) {
                $query->where('nama_shift', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'opsi' => OpsiAbsen::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'status' => StatusAbsen::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
        };        
    }
    public function render()
    {
        return view('livewire.data-absensi',[
            'items' => $this->items,
        ]);
    }

}

