<?php

namespace App\Livewire;

use App\Models\MasterJabatan;
use Livewire\Component;

class DataJabatanPerizinan extends Component
{
    
    public $jabatanperizinan;
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->jabatanperizinan = MasterJabatan::when( $this->search, function($query){
            $query->where('nama', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-jabatan-perizinan');
    }
}
