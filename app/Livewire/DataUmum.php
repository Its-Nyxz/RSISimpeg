<?php

namespace App\Livewire;

use App\Models\MasterUmum;
use Livewire\Component;

class DataUmum extends Component
{
    public $search = '';
    public $umums = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Query data dengan relasi ke KategoriJabatan
        $this->umums = MasterUmum::with('kategorijabatan')
            ->when($this->search, function ($query) {
                $query->whereHas('kategorijabatan', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
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
        return view('livewire.data-umum');
    }
}
