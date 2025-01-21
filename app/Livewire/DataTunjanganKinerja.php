<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use App\Models\MasaKerja;
use App\Models\ProposionalitasPoint;
use App\Models\PointPeran;
use Livewire\Component;

class DataTunjanganKinerja extends Component
{
    public $type;
    public $search = '';
    public $items = [];

    public function mount($type)
    {
        $this->type = $type;

        $this->loadData();
    }

    public function loadData()
    {
        $this->items = match ($this->type) {
            'levelunit' => LevelUnit::with(['unitKerja', 'levelPoint'])
                ->when($this->search, function ($query) {
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('nama', 'like', '%' . $this->search . '%');
                    })->orWhereHas('levelPoint', function ($subQuery) {
                        $subQuery->where('point', 'like', '%' . $this->search . '%');
                    })->orWhere('level_unit', 'like', '%' . $this->search . '%');
                })->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_unit' => $item->unitKerja->nama ?? null,
                        'nama_level' => $item->levelpoint->nama ?? null,
                        'poin' => $item->levelPoint->point ?? null,
                    ];
                })->toArray(),
            'masakerja' => MasaKerja::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('point', 'like', '%' . $this->search . '%');
            })->get()->toArray(),
            'proposionalitas' => ProposionalitasPoint::with('proposable')
                ->when($this->search, function ($query) {
                    $query->whereHasMorph(
                        'proposable',
                        ['App\Models\MasterFungsi', 'App\Models\MasterUmum'],
                        function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        }
                    );
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->proposable->nama ?? '-',
                        'poin' => $item->point,
                    ];
                }),
            'pointperan' => PointPeran::with('peransable')
                ->when($this->search, function ($query) {
                    $query->whereHasMorph(
                        'peransable',
                        ['App\Models\MasterFungsi', 'App\Models\MasterUmum'],
                        function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        }
                    );
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->peransable->nama ?? '-',
                        'poin' => $item->point,
                    ];
                }),
            default => collect()->toArray(),
        };
    }
    
    public function render()
    {
        return view('livewire.data-tunjangan-kinerja');
    }

}

