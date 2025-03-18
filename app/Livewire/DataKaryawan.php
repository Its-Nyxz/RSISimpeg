<?php

namespace App\Livewire;

use App\Models\UnitKerja;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DataKaryawan extends Component
{
    use WithPagination;

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $roles;
    public $units;
    public $selectedUserAktif = 1; // Default aktif
    public $selectedUnit = null;

    public function mount()
    {
        $this->units = UnitKerja::all();
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
        $roles = ['Super Admin', 'Kepala Seksi Kepegawaian', 'Staf Kepegawaian', 'Kepegawaian', 'Administrator'];
        $unit_id = Auth::user()->unit_id;

        return User::with(['kategorijabatan', 'unitKerja', 'roles'])
            ->where('id', '>', '1') // Eager load jabatan dan unitKerja
            ->when(!Auth::user()->hasAnyRole($roles), function ($query) use ($unit_id) {
                // Jika bukan Super Admin, Kepegawaian, atau Administrator, filter berdasarkan unit_id
                $query->where('unit_id', $unit_id);
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
                    $query->where('unit_id', $unit_id);
                }
            })
            ->when(isset($this->selectedUserAktif), function ($query) {
                $query->where('status_karyawan', $this->selectedUserAktif);
            })
            ->when($this->selectedUnit, function ($query) {
                $query->where('unit_id', $this->selectedUnit);
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
