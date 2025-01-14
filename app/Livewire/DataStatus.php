<?php

namespace App\Livewire;

use App\Models\StatusAbsen;
use Livewire\Component;

class DataStatus extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $statuss = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->statuss = StatusAbsen::when($this->search, function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%')
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
        return view('livewire.data-status');
    }
}
