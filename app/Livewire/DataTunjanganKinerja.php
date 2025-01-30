<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use App\Models\MasaKerja;
use App\Models\ProposionalitasPoint;
use App\Models\PointPeran;
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

    public function updatedSearch()
    {
        $this->loadData();
    }

    public function updateSearch($value = null)
    {
        $this->search = $value ?? $this->search;
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
                })->get()->map(fn($item) => [
                    'id' => $item->id,
                    'nama_unit' => $item->unitKerja->nama ?? null,
                    'nama_level' => $item->levelPoint->nama ?? null,
                    'poin' => $item->levelPoint->point ?? null,
                ])->toArray(),

            'masakerja' => MasaKerja::query()
                ->when(
                    $this->search,
                    fn($query) =>
                    $query->where('nama', 'like', '%Kontrak%')
                )->get()->map(fn($item) => [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'point' => $item->point,
                ])->toArray(),

            'proposionalitas' => ProposionalitasPoint::with('proposable')
                ->when($this->search, function ($query) {
                    $query->whereHasMorph(
                        'proposable',
                        ['App\Models\MasterFungsi', 'App\Models\MasterUmum'],
                        fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')
                    );
                })->get()->map(fn($item) => [
                    'id' => $item->id,
                    'nama' => $item->proposable->nama ?? '-',
                    'poin' => $item->point,
                ])->toArray(),

            'pointperan' => PointPeran::with('peransable')
                ->when($this->search, function ($query) {
                    $query->whereHasMorph(
                        'peransable',
                        ['App\Models\MasterFungsi', 'App\Models\MasterUmum'],
                        fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')
                    );
                })->get()->map(fn($item) => [
                    'id' => $item->id,
                    'nama' => $item->peransable->nama ?? '-',
                    'poin' => $item->point,
                ])->toArray(),

            'tukinjabatan' => PointJabatan::with('pointable')
                ->when($this->search, function ($query) {
                    $query->whereHasMorph(
                        'pointable',
                        ['App\Models\MasterFungsi', 'App\Models\MasterUmum'],
                        fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')
                    );
                })->get()->map(fn($item) => [
                    'id' => $item->id,
                    'nama' => $item->pointable->nama ?? '-',
                    'poin' => $item->point,
                ])->toArray(),

            default => [],
        };
    }
    public function render()
    {
        return view('livewire.data-tunjangan-kinerja');
    }
}
