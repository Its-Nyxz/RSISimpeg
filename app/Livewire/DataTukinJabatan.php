<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PointJabatan;

class DataTukinJabatan extends Component
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
        $query = PointJabatan::with('pointable') 
            ->when($this->search, function ($query) {
                $query->whereHasMorph('pointable', ['App\Models\MasterFungsi', 'App\Models\MasterUmum'], function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%'); 
                });
            })
            ->get();
    
        $this->items = $query->map(function ($pointJabatan) {
            return [
                'id' => $pointJabatan->id,
                'nama' => $pointJabatan->pointable->nama ?? '-',
                'poin' => $pointJabatan->point,
            ];
        });
    }
    

    public function render()
    {
        return view('livewire.data-tukin-jabatan', [
            'items' => $this->items,
        ]);
    }
}
