<?php

namespace App\Livewire;

use App\Models\MasterJabatan;
use Livewire\Component;

class DataJabatan extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $jabatans = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->jabatans = MasterJabatan::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('kualifikasi', 'like', '%' . $this->search . '%')
                ->orWhere('nominal', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-jabatan');
    }
}
