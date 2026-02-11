<?php

namespace App\Livewire;

use App\Models\Shift;
use App\Models\UnitKerja;
use Livewire\Component;

class DataShift extends Component
{
    public $search = '';
    public $shifts = [];
    public $units;
    public $selectedUnit = null;

    public $perPage = 10;
    public $currentPage = 1;
    public $totalShifts = 0;

    public function mount()
    {
        $this->units = UnitKerja::orderBy('nama', 'asc')->get();
        $this->loadData();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->currentPage = 1;
        $this->loadData();
    }

    public function updatedSearch()
    {
        $this->currentPage = 1;
        $this->loadData();
    }
    public function nextPage()
    {
        $this->currentPage++;
        $this->loadData();
    }

    public function prevPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadData();
        }
    }

    public function loadData()
    {
        $userUnitId = auth()->user()->unit_id;
        $unitIdToFilter = $userUnitId ?? $this->selectedUnit;

        $query = Shift::with('unitKerja')
            ->when($unitIdToFilter, fn($q) => $q->where('unit_id', $unitIdToFilter))
            ->when($this->search, function ($q) {
                $q->where('nama_shift', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_keluar', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            });

        $this->totalShifts = $query->count();

        $this->shifts = $query->orderBy('created_at')
            ->skip(($this->currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get()
            ->toArray();
    }

    public function destroy($id)
    {
        $shift = Shift::find($id);

        if ($shift) {
            $shift->delete();
            $this->loadData();
            session()->flash('success', 'Shift berhasil dihapus.');
        } else {
            session()->flash('error', 'Shift tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.data-shift');
    }
}
