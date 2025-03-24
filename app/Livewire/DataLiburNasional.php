<?php

namespace App\Livewire;

use App\Models\Holidays;
use Livewire\Component;

class DataLiburNasional extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $year;
    public $holidays = [];

    public function mount()
    {
        $this->year = now()->year; // Set tahun default ke tahun sekarang
        $this->loadData();
    }

    public function loadData()
    {
        $this->holidays = Holidays::when($this->search, function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%');
        })
            ->when($this->year, function ($query) {
                $query->whereYear('date', $this->year);
            })
            ->get()
            ->toArray();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function updateYear($value)
    {
        $this->year = $value;
        $this->loadData();
    }


    public function destroy($id)
    {
        $holidays = Holidays::find($id);

        if (!$holidays) {
            return redirect()->route('liburnasional.index')->with('error', 'Hari Libur Nasional Tidak Ditemukan');
        }

        try {
            $holidays->delete();

            // Refresh data setelah penghapusan
            $this->loadData();

            return redirect()->route('liburnasional.index')->with('success', 'Hari Libur Nasional berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('liburnasional.index')->with('error', 'Terjadi kesalahan saat Hari Libur Nasional dihapus');
        }
    }


    public function render()
    {
        return view('livewire.data-libur-nasional');
    }
}
