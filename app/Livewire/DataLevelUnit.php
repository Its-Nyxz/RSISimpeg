<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use Livewire\Component;

class DataLevelUnit extends Component
{
    public $search = '';
    public $levelunit = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
{
    $this->levelunit = LevelUnit::with(['unitKerja', 'levelPoint'])
        ->when($this->search, function ($query) {
            $query->whereHas('unitKerja', function ($subQuery) {
                $subQuery->where('nama', 'like', '%' . $this->search . '%');
            })->orWhereHas('levelPoint', function ($subQuery) {
                $subQuery->where('point', 'like', '%' . $this->search . '%');
            })->orWhere('level_unit', 'like', '%' . $this->search . '%');
        })
        ->get()
        ->map(function ($levelunit) {
            return [
                'id' => $levelunit->id,
                'nama_unit' => $levelunit->unitKerja->nama ?? 'Belum ada data',
                'nama_level' => $levelunit->levelPoint->nama ?? 'Belum ada data',
                'poin' => $levelunit->levelPoint->point ?? 'Belum ada data',
            ];
        })
        ->toArray();
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
