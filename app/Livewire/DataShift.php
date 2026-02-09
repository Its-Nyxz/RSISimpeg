<?php

namespace App\Livewire;

use App\Models\Shift;
use App\Models\UnitKerja;
use Livewire\Component;
use Livewire\WithPagination;

class DataShift extends Component
{
    use WithPagination;

    public $search = '';
    public $units;
    public $selectedUnit = null;

    public $perPage = 10;

    public function mount()
    {
        $this->units = UnitKerja::orderBy('nama', 'asc')->get();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function destroy($id)
    {
        $shift = Shift::find($id);

        if ($shift) {
            $shift->delete();
            session()->flash('success', 'Shift berhasil dihapus.');
        } else {
            session()->flash('error', 'Shift tidak ditemukan.');
        }
    }

    public function render()
    {
        $userUnitId = auth()->user()->unit_id;
        $unitIdToFilter = $userUnitId ?? $this->selectedUnit;

        $shifts = Shift::with('unitKerja')
            ->when($unitIdToFilter, fn($q) => $q->where('unit_id', $unitIdToFilter))
            ->when($this->search, function ($q) {
                $q->where('nama_shift', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_keluar', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at')
            ->paginate($this->perPage);

        return view('livewire.data-shift', [
            'shifts' => $shifts
        ]);
    }
}
