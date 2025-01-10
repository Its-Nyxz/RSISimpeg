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
        $this->umums = MasterUmum::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('nominal', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
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
