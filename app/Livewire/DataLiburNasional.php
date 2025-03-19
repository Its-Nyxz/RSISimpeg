<?php

namespace App\Livewire;

use App\Models\Holidays;
use Livewire\Component;

class DataLiburNasional extends Component
{
    public $search = ''; 
    public $holidays = [];
    public $holidayIdToDelete = null; // Menyimpan ID yang akan dihapus

    public function mount()
    {
        $this->tahun = request()->query('tahun', now()->year);
    }

    public function updatedTahun()
    {
        $this->holidays = Holidays::when($this->search, function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%');
        })->get()->toArray();
    }

    // Menampilkan modal konfirmasi
    public function confirmDelete($id)
    {
        $this->holidayIdToDelete = $id;
    }

    // Hapus data jika user konfirmasi
    public function deleteHoliday()
    {
        if ($this->holidayIdToDelete) {
            $holiday = Holidays::find($this->holidayIdToDelete);
            if ($holiday) {
                $holiday->delete();
                session()->flash('message', 'Hari libur berhasil dihapus.');
            } else {
                session()->flash('error', 'Data tidak ditemukan.');
            }
        }

        // Reset properti ID setelah delete
        $this->holidayIdToDelete = null;
        $this->loadData();
    }

    public function render()
    {
        $holidays = Holidays::when($this->search, function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%');
        })
        ->when($this->tahun, function ($query) {
            $query->whereYear('date', $this->tahun);
        })
        ->orderBy('date', 'asc')
        ->get();

        return view('livewire.data-libur-nasional', compact('holidays'));
    }
}
