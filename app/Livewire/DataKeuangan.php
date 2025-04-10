<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class DataKeuangan extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
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
            })->orderBy('jabatan_id', 'asc')->paginate(15);
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-keuangan', [
            'users' => $users
        ]);
    }
}
