<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LevelUnit;
use App\Models\UnitKerja;
use App\Models\LevelPoint;

class CreateLevelUnit extends Component
{
    public $unit_id;
    public $level_id;
    public $unitkerja = [];
    public $levelpoint = [];

    protected $rules =[
        'unit_id' => 'required|exists:unit_kerjas,id',
        'level_id' => 'required|exists:level_points,id',
    ];

    public function mount()
    {
        $this->unitkerja = UnitKerja::all();
        $this->levelpoint = LevelPoint::all();

        // dd($this->unitkerjas, $this->levelpoints);
    }

    public function store()
    {
        // dd($this->unit_id, $this->level_id);

        $this->validate();

        LevelUnit::create([
            'unit_id' => $this->unit_id,
            'level_id' => $this->level_id,
        ]);

        $this->reset('unit_id');
        $this->reset('level_id');

        session()->flash('success', 'Level Unit berhasil ditambah!');
        return redirect()->route('levelunit.index');
    }

    public function render()
    {
        return view('livewire.create-level-unit', [
            'unitkerja' => $this->unitkerja,
            'levelpoint' => $this->levelpoint,
            // 'levelunits' => $levelunits,
        ]);
    }
}
