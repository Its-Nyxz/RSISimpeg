<?php

namespace App\Livewire;

use App\Models\MasterTrans;
use Livewire\Component;

class DataTrans extends Component
{
    public $search = '';
    public $tidaktetaps = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->tidaktetaps = MasterTrans::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('nom_makan', 'like', '%' . $this->search . '%')
                ->orWhere('nom_transport', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-trans');
    }
}