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
    public $newJabatanNama;

    public $swalData = null;

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

    public function openCreateModal()
    {
        $this->dispatch('open-modal', 'create-modal');
    }

    public function storeJabatan()
    {
        if (!$this->newJabatanNama) {
            $this->swalData = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nama Hak Akses tidak boleh kosong.',
                'timer' => 2000
            ];
            return;
        }

        if (Role::where('name', $this->newJabatanNama)->exists()) {
            $this->swalData = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Hak Akses dengan nama ini sudah ada.',
                'timer' => 2000
            ];
            return;
        }

        Role::create(['name' => $this->newJabatanNama]);
        $this->newJabatanNama = '';
        $this->loadData();
        $this->dispatch('close-modal', 'create-modal');

        $this->swalData = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Hak Akses berhasil dibuat.',
            'timer' => 2000
        ];
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
