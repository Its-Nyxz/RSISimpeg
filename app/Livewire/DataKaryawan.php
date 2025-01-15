<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DataKaryawan extends Component
{
    use WithPagination;

    public $search = ''; // Properti untuk menyimpan nilai input pencarian

    public function updatingSearch()
    {
        // Reset halaman ke halaman pertama jika pencarian diperbarui
        $this->resetPage();
    }

    public function render()
    {
        $users = User::with(['jabatan', 'unitKerja']) // Eager load jabatan dan unitKerja
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
