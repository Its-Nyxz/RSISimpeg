<?php

namespace App\Livewire;

use App\Models\MasterFungsi;
use Livewire\Component;

class DataFungsi extends Component
{
    public $search = '';
    public $fungsionals = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->fungsionals = MasterFungsi::when( $this->search, function($query){
            $query->where('nama', 'like', '%' . $this->search . '%')
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
        return view('livewire.data-fungsi');
    }
}
