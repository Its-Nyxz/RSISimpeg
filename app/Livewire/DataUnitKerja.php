<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Livewire\WithPagination;

class DataUnitKerja extends Component
{
    use WithPagination;
    public $search = '';
    // public $unitkerja = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // $this->unitkerja = 
        return UnitKerja::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('kode', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%');
        })->paginate(15);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        // $this->loadData();
        $this->resetPage();
    }

    public function destroy($id)
    {
        $unitkerja = UnitKerja::find($id);
        if (!$unitkerja) {
            return redirect()->route('unitkerja.index')->with('error', 'Unit Kerja Tidak Ditemukan');
        }

        // Cek apakah ada user yang memakai unitkerja ini
        $userCount = User::where('unit_id', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('unitkerja.index')->with('error', 'Tidak dapat menghapus. Unit Kerja ini sedang digunakan oleh pengguna.');
        }

        try {
            $unitkerja->delete();

            return redirect()->route('unitkerja.index')->with('success', 'Unit Kerja berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('unitkerja.index')->with('error', 'Terjadi kesalahan saat Unit Kerja dihapus');
        }
    }

    public function render()
    {
        $unitkerja = $this->loadData();
        return view('livewire.data-unit-kerja', [
            'unitkerja' => $unitkerja,
        ]);
    }
}
