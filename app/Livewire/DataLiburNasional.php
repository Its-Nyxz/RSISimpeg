<?php

namespace App\Livewire;

use App\Models\Holidays;
use Livewire\Component;

class DataLiburNasional extends Component
{
    public $search = ''; 
    public $tahun;

    public function mount()
    {
        $this->tahun = request()->query('tahun', now()->year);
    }

    public function updatedTahun()
    {
        return redirect()->route('liburnasional.index', ['tahun' => $this->tahun]);
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
