<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use App\Models\MasaKerja;
use App\Models\Jabatan; // Pastikan model Jabatan sudah diimpor
use App\Models\PointJabatan;
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
                        'nama_level' => $item->levelPoint->nama ?? null,
                        'poin' => $item->levelpoint->point ?? 0,
                    ];
                }),

            'masakerja' => MasaKerja::query()
                ->when($this->search, function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                })->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'point' => $item->point,
                    ];
                }),
            'tukinjabatan' => PointJabatan::with('pointable')
                ->when($this->search, function ($query) {
                    $query->whereHasMorph('pointable', ['App\Models\MasterFungsi', 'App\Models\MasterUmum'], function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    });
                })
                ->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->pointable->nama ?? '-',
                        'poin' => $item->point,
                    ];
                }),

            default => [],
        };
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.data-tunjangan-kinerja');
    }
}
