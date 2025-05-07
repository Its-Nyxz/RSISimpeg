<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Livewire\WithPagination;
use App\Models\JenisKaryawan;

class DataKeuangan extends Component
{
    use WithPagination;

    public $search = '';
    public $units;
    public $selectedUnit = null;
    public $selectedJenisKaryawan = null;
    public $jenisKaryawans = [];

    public function mount()
    {
        $this->units = UnitKerja::all();
        $this->jenisKaryawans = JenisKaryawan::all();
        $this->loadData();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function loadData()
    {
        return User::with(['kategorijabatan', 'unitKerja', 'roles'])->where('id', '>', '1')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('no_ktp', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedUnit, function ($query) {
                $unitIds = UnitKerja::where('id', $this->selectedUnit)
                    ->orWhere('parent_id', $this->selectedUnit)
                    ->pluck('id')
                    ->toArray();

                $query->whereIn('unit_id', $unitIds);
            })
            ->when($this->selectedJenisKaryawan, function ($query) {
                $query->where('jenis_id', $this->selectedJenisKaryawan);
            })->orderBy('name', 'asc')->paginate(15);
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-keuangan', [
            'users' => $users
        ]);
    }
}
