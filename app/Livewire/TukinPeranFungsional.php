<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PointPeran;

class TukinPeranFungsional extends Component
{
    public $items = [];
    public $search = '';

    public function mount()
    {
        $this->fetchData();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->fetchData();
    }

    public function fetchData()
    {
        $query = PointPeran::with('peransable')
            ->when($this->search, function ($query) {
                $query->whereHasMorph('peransable', ['App\Models\MasterFungsi', 'App\Models\MasterUmum'], function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->get();
    
        $this->items = $query->map(function ($pointperan) {
            return [
                'id' => $pointperan->id,
                'nama' => $pointperan->peransable->nama ?? '-',
                'poin' => $pointperan->point,
            ];
        });
    }

    public function render()
    {
        return view('livewire.tukin-peran-fungsional', [
            'items' => $this->items,
        ]);
    }
}
