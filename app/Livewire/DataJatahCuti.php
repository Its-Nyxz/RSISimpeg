<?php

namespace App\Livewire;

use App\Models\MasterJatahCuti;
use Livewire\Component;

class DataJatahCuti extends Component
{

    public $year;
    public $cutis = [];

    public function mount()
    {
        // $this->year = now()->year; // Set tahun default ke tahun sekarang
        $this->loadData();
    }

    public function loadData()
    {
        $this->cutis = MasterJatahCuti::query()
            // ->when($this->year, function ($query) {
            //     $query->where('tahun', $this->year); // Perbaiki, bukan whereYear
            // })
            ->orderBy('tahun', 'desc')
            ->get()
            ->toArray();
    }
    public function updateYear($value)
    {
        $this->year = $value;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.data-jatah-cuti');
    }
}
