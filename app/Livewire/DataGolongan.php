<?php

namespace App\Livewire;

use App\Models\MasterGolongan;
use Livewire\Component;

class DataGolongan extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $golongans = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->golongans = MasterGolongan::when($this->search, function ($query) {
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
        return view('livewire.data-golongan');
    }
}
