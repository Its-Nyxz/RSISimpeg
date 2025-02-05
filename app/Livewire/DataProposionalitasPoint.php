<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProposionalitasPoint;

class DataProposionalitasPoint extends Component
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
        $query = ProposionalitasPoint::with('proposable', 'unitkerja')
            ->when($this->search, function ($query) {
                $query->whereHasMorph('proposable', ['App\Models\MasterFungsi', 'App\Models\MasterUmum'], function ($q) {
                    $q->whereHas('kategorijabatan', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->get();

        $this->items = $query->map(function ($proposionalitaspoint) {
            return [
                'id' => $proposionalitaspoint->id,
                'nama' => $proposionalitaspoint->proposable->kategorijabatan->nama ?? '-',
                'nama_unit' => $proposionalitaspoint->unitkerja->nama ?? '-',
                'poin' => $proposionalitaspoint->point,
            ];
        });
    }

    public function render()
    {
        return view('livewire.data-proposionalitas-point', [
            'items' => $this->items,
        ]);
    }
}
