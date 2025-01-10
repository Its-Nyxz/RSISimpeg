<?php

namespace App\Livewire;

use App\Models\MasterKhusus;
use Livewire\Component;

class DataKhusus extends Component
{
    public $search = '';
    public $khususs = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->khususs = MasterKhusus::when( $this->search, function($query){
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
        return view('livewire.data-khusus');
    }
}
