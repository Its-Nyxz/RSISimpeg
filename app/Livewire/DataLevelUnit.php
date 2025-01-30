<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use Livewire\Component;

class DataLevelUnit extends Component
{
    public $data;

    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = LevelUnit::with(['unitkerja', 'levelpoint'])
            ->when($this->search, function ($query) {
                $query->whereHas('unitkerja', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })->orWhereHas('levelpoint', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })->orWhereHas('levelpoint', function ($q) {
                    $q->where('point', 'like', '%' . $this->search . '%');
                });
            })
            ->get();
        // dd($this->data);
    }



    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.data-level-unit');
    }
}
