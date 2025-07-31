<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitKerja;
use Livewire\WithPagination;

class DataUnitKerja extends Component
{
    use WithPagination;
    public $search = '';
    // public $unitkerja = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // $this->unitkerja = 
        return UnitKerja::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('kode', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%');
        })->paginate(15);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        // $this->loadData();
        $this->resetPage();
    }

    public function render()
    {
        $unitkerja = $this->loadData();
        return view('livewire.data-unit-kerja', [
            'unitkerja' => $unitkerja,
        ]);
    }
}
