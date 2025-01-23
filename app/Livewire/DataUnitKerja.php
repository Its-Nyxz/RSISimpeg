<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitKerja;

class DataUnitKerja extends Component
{
    public $search = '';
    public $unitkerja = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->unitkerja = UnitKerja::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('kode', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-unit-kerja');
    }
}
