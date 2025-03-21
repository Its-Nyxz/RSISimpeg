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
            session()->flash('error', 'Shift tidak ditemukan.');
            return;
        }

        try {
            $shift->delete();

            // Refresh data setelah penghapusan
            $this->loadData();

            session()->flash('success', 'Shift berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus shift.');
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
