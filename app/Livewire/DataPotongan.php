<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterPotongan;

class DataPotongan extends Component
{
    public $search = '';
    public $potongans = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->potongans = MasterPotongan::when($this->search, function ($query) {
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
        return view('livewire.data-potongan');
    }
}
