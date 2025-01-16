<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use Livewire\Component;

class DataLevelUnit extends Component
{
    public $data;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = LevelUnit::with(['unitkerja', 'levelpoint'])
            ->get()
            ->map(function ($levelunit) {
                return [
                    'nama_unit' => $levelunit->unitkerja->nama ?? 'Belum ada data',
                    'nama_level' => $levelunit->levelpoint->nama ?? 'Belum ada data',
                    'poin' => $levelunit->levelpoint->point ?? 'Belum ada data',
                ];
            });
        
        // dd($this->data);
    }

    // public function loadData()
    // {
    //     $this->data = LevelUnit::with(['unitKerja', 'levelPoint'])->get();
        
    //     foreach ($this->data as $item) {
    //         dump([
    //             'unitKerja' => $item->unitKerja,
    //             'levelPoint' => $item->levelPoint,
    //         ]);
    //     }
    // }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    // public $data;

    // public function mount()
    // {
    //     $this->loadData();
    // }

    // public function loadData()
    // {
    //     $this->data = LevelUnit::with(['unitkerja', 'levelpoint'])->get();
    //     dd($this->data);
    // }

    public function render()
    {
        return view('livewire.data-level-unit');
    }
}
