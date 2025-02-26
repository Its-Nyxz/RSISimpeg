<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LevelUnit;
use App\Models\UnitKerja;
use App\Models\LevelPoint;

class CreateLevelUnit extends Component
{
    public $unit_kerja;
    public $unit_id;
    public $level_point;
    public $level_id;
    public $unitKerjaOptions = [];
    public $levelPointOptions = [];

    protected $rules = [
        'unit_id' => 'required|exists:unit_kerjas,id',
        'level_id' => 'required|exists:level_points,id',
    ];

    public function fetchSuggestions($field, $query)
    {
        if ($field === 'unit_kerja') {
            $this->unitKerjaOptions = UnitKerja::where('nama', 'like', "%$query%")
                ->get();
        } elseif ($field === 'level_point') {
            $this->levelPointOptions = LevelPoint::where('nama', 'like', "%$query%")
                ->get();
        }
    }

    public function selectUnitKerja($id, $name)
    {
        $this->unit_id = $id;
        $this->unit_kerja = $name;
        $this->unitKerjaOptions = [];
    }

    public function selectLevelPoint($id, $name)
    {
        $this->level_id = $id;
        $this->level_point = $name;
        $this->levelPointOptions = [];
    }

    public function store()
    {
        $this->validate();
        LevelUnit::create([
            'unit_id' => $this->unit_id,
            'level_id' => $this->level_id,
        ]);

        session()->flash('success', 'Level Unit berhasil ditambahkan!');
        return redirect()->route('levelunit.index');
    }

    public function render()
    {
        return view('livewire.create-level-unit');
    }
}
