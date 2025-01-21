<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DataKaryawan extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $roles;

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->render();
    }

    public function render()
    {
        $users = User::with(['jabatan', 'unitKerja', 'roles'])->where('id', '>', '1') // Eager load jabatan dan unitKerja
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('no_ktp', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
            })->paginate(15);

        return view('livewire.data-karyawan', [
            'users' => $users,
        ]);
    }
}
