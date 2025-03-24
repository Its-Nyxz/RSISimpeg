<?php

namespace App\Livewire;

use App\Models\Shift;
use App\Models\UnitKerja;
use Livewire\Component;

class DataShift extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $shifts = [];

    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $userUnitId = auth()->user()->unit_id; // Ambil unit_id dari user yang login

        $this->shifts = Shift::with('unitKerja')
            ->when($userUnitId, function ($query) use ($userUnitId) {
                // Filter berdasarkan unit kerja dari user yang login
                $query->where('unit_id', $userUnitId);
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
