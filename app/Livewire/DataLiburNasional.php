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
        $this->loadData();
    }

    public function loadData()
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
        return view('livewire.data-libur-nasional');
    }
}
