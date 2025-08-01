<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\MasterPotongan;

class DataPotongan extends Component
{
    public $search = '';
    public $potongans = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->potongans = MasterPotongan::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        })
            ->get()
            ->toArray();
    }


    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function destroy($id)
    {
        $potongan = MasterPotongan::find($id);
        if (!$potongan) {
            return redirect()->route('potongan.index')->with('error', 'Potongan Tidak Ditemukan');
        }

        try {
            $potongan->delete();

            // Refresh data setelah penghapusan
            $this->loadData();

            return redirect()->route('potongan.index')->with('success', 'Potongan berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('potongan.index')->with('error', 'Terjadi kesalahan saat potongan dihapus');
        }
    }
    public function render()
    {
        return view('livewire.data-potongan');
    }
}
