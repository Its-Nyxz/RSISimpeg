<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OverrideLokasi;

class DataOverrideLokasi extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $bulan;
    public $tahun;

    public function mount()
    {
        $this->bulan = now()->format('m');
        $this->tahun = now()->format('Y');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $query = OverrideLokasi::with(['user'])
            ->when(
                $this->search,
                fn($q) =>
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                )
            )
            ->when($this->bulan, fn($q) => $q->whereMonth('created_at', $this->bulan))
            ->when($this->tahun, fn($q) => $q->whereYear('created_at', $this->tahun))
            ->latest();

        return view('livewire.data-override-lokasi', [
            'overrides' => $query->paginate($this->perPage),
        ]);
    }
}
