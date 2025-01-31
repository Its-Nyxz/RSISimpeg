<?php

namespace App\Livewire;

use Livewire\Component;

class AktivitasAbsensi extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $items = [];
    public function mount()
    {
        $this->loadData();
    }

    public function loadData(){
        
    }

    public function render()
    {
        return view('livewire.aktivitas-absensi');
    }
}
