<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Livewire\WithPagination;
use App\Models\JenisKaryawan;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DataKaryawan extends Component
{
    use WithPagination;

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $roles;
    public $units;
    public $selectedUserAktif = 1; // Default aktif
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

    public function updatedSelectedUserAktif()
    {
        $this->resetPage();
    }

    public function updatedSelectedUnit()
    {
        $this->resetPage();
    }

    public function loadData()
    {
      
        $roles = Role::whereIn('name', ['Super Admin', 'Administrator'])
            ->orWhere('name', 'like', '%Kepegawaian%')
            ->pluck('id')
            ->toArray();
        $unit_id = Auth::user()->unit_id;


        return User::with(['kategorijabatan', 'unitKerja', 'roles', 'jenis'])
            ->where('id', '>', '1') // Eager load jabatan dan unitKerja
            ->when(!Auth::user()->hasAnyRole($roles), function ($query) use ($unit_id) {
                $unitIds = UnitKerja::where('id', $unit_id)
                    ->orWhere('parent_id', $unit_id)
                    ->pluck('id')
                    ->toArray();

                $query->whereIn('unit_id', $unitIds);
            })
            ->when($this->search, function ($query) use ($roles, $unit_id) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('no_ktp', 'like', '%' . $this->search . '%')
                        ->orWhere('alamat', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kategorijabatan', function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('unitKerja', function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        });
                });

                if (!Auth::user()->hasAnyRole($roles)) {
                    $unitIds = UnitKerja::where('id', $unit_id)
                        ->orWhere('parent_id', $unit_id)
                        ->pluck('id')
                        ->toArray();

                    $query->whereIn('unit_id', $unitIds);
                }
            })
            ->when(isset($this->selectedUserAktif), function ($query) {
                $query->where('status_karyawan', $this->selectedUserAktif);
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
            })
            ->paginate(15);
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-karyawan', [
            'users' => $users,
        ]);
    }
}
