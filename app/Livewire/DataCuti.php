<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class DataCuti extends Component
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
        return User::with(['kategorijabatan'])->where('id')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('tgl_penyesuaian', 'like', '%' . $this->search . '%')
                    ->orWhere('masa_kerja', 'like', '%' . $this->search . '%');
            })->paginate(15);
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-cuti', [
            'users' => $users,
        ]);
    }
}
