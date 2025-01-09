<?php

namespace App\Livewire;

use App\Models\MasterFungsi;
use App\Models\MasterJabatan;
use App\Models\MasterKhusus;
use App\Models\MasterTrans;
use App\Models\MasterUmum;
use Livewire\Component;

class DataTunjangan extends Component
{
    public $type; // Type: jabatan, fungsional, umum, tidak tetap
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $items = []; // Data yang akan ditampilkan

    public function mount($type)
    {
        $this->type = $type;

        $this->loadData();
    }

    public function loadData()
    {
        // Load data sesuai dengan tipe dan tambahkan pencarian
        $this->items = match ($this->type) {
            'jabatan' => MasterJabatan::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('kualifikasi', 'like', '%' . $this->search . '%')
                    ->orWhere('nominal', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'fungsional' => MasterFungsi::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nominal', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'umum' => MasterUmum::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nominal', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'khusus' => MasterKhusus::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nominal', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'tidaktetap' => MasterTrans::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nominal', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            default => collect()->toArray(),
        };
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.data-tunjangan', [
            'items' => $this->items,
        ]);
    }
}
