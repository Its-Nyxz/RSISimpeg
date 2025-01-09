<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Absen;
use App\Models\User;

class DataAbsen extends Component
{
    public $totalHadir = 0;   // Menyimpan jumlah karyawan hadir
    public $totalPulang = 0;   // Menyimpan jumlah karyawan pulang
    public $totalKaryawan = 0; // Menyimpan jumlah total karyawan

    public function mount()
    {
        $this->loadData(); // Memuat data kehadiran saat komponen dimuat
    }

    public function loadData()
    {
        $this->totalHadir = Absen::where('status_absen_id', 1)->count();
        $this->totalPulang = Absen::where('status_absen_id', 2)->count();

        $this->totalKaryawan = User::count();
    }

    public function render()
    {
        return view('livewire.data-absen', [
            'totalHadir' => $this->totalHadir,
            'totalPulang' => $this->totalPulang,
            'totalKaryawan' => $this->totalKaryawan,
        ]);
    }
}