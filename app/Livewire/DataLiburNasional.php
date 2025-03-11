<?php

namespace App\Livewire;

use App\Models\Holidays;
use Livewire\Component;

class DataLiburNasional extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $holidays = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->holidays = Holidays::when($this->search, function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-libur-nasional');
    }
}
