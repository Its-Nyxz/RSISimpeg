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
        $query = MasterPotongan::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%');

                    if (is_numeric($this->search)) {
                        $sub->orWhere('no_urut', (int) $this->search);
                    }
                });
            })
            ->orderByRaw('CASE WHEN no_urut IS NULL THEN 1 ELSE 0 END')
            ->orderBy('no_urut', 'asc')
            ->orderBy('id', 'asc');

        $this->potongans = $query->get()->toArray();
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
