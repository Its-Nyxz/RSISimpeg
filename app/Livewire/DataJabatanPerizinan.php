<?php

namespace App\Livewire;

use App\Models\MasterJabatan;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class DataJabatanPerizinan extends Component
{
    public $jabatanId;
    public $jabatanNama;
    public $jabatanperizinan;
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->jabatanperizinan = Role::where('id', '>', '1')->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->get()
            ->toArray();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function editJabatan($id)
    {
        $jabatan = Role::findOrFail($id);

        $this->jabatanId = $jabatan->id;
        $this->jabatanNama = $jabatan->name;

        $this->dispatch('open-modal', 'edit-modal');
    }

    public function updateJabatan()
    {
        $this->validate([
            'jabatanNama' => 'required|string|max:255',
        ]);

        $jabatan = Role::findOrFail($this->jabatanId);
        $jabatan->name = $this->jabatanNama;
        $jabatan->save();

        $this->loadData(); // Refresh data
        $this->dispatch('close-modal', 'edit-modal');
        return redirect()->route('jabatanperizinan.index')->with('success', 'Nama Jabatan berhasil diupdate.');
    }


    public function render()
    {
        return view('livewire.data-jabatan-perizinan');
    }
}
