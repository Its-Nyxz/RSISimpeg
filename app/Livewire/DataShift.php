<?php

namespace App\Livewire;

use App\Models\Shift;
use App\Models\UnitKerja;
use Livewire\Component;

class DataShift extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $shifts = [];
    public $units;
    public $selectedUnit = null;

    public function mount()
    {
        $this->units = UnitKerja::orderBy('nama', 'asc')->get();
        $this->loadData();
    }
    public function updatedSelectedUnit()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $userUnitId = auth()->user()->unit_id;
        $selectedUnitId = $this->selectedUnit;

        // Jika user tidak punya unit_id tapi memilih dari dropdown
        $unitIdToFilter = $userUnitId ?? $selectedUnitId; // Ambil unit_id dari user yang login

        $this->shifts = Shift::with('unitKerja')
            ->when($unitIdToFilter, function ($query) use ($unitIdToFilter) {
                $query->where('unit_id', $unitIdToFilter);
            })
            ->when($this->search, function ($query) {
                $query->where('nama_shift', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_keluar', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })
            ->get()
            ->toArray();
    }

    public function destroy($id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return redirect()->route('shift.index')->with('error', 'Shift tidak ditemukan.');
        }

        try {
            $shift->delete();

            // Refresh data setelah penghapusan
            $this->loadData();

            return redirect()->route('shift.index')->with('success', 'Shift berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('shift.index')->with('error', 'Terjadi kesalahan saat Shift dihapus');
        }
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.data-shift');
    }
}
