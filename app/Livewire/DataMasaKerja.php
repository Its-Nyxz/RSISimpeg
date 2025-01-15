<?php

namespace App\Livewire;

use App\Models\MasaKerja;
use Livewire\Component;

class DataMasaKerja extends Component
{
    public $search = '';
    public $masakerja = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->masakerja = MasaKerja::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('point', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-masa-kerja');
    }
}
