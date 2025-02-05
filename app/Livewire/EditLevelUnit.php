<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LevelUnit;
use App\Models\LevelPoint;
use App\Models\UnitKerja;

class EditLevelUnit extends Component
{
    public $unit_id;
    public $level_id;
    public $levelunit_id;

    public $unitkerja;
    public $levelpoint;

    public function mount($levelunitId)
    {
        $levelunit = LevelUnit::findOrFail($levelunitId);

        $this->levelunit_id = $levelunit->id;
        $this->unit_id = $levelunit->unit_id;
        $this->level_id = $levelunit->level_id;

        $this->unitkerja = UnitKerja::all();
        $this->levelpoint = LevelPoint::all();
    }

    public function updateLevelUnit()
    {
        $this->validate([
            'unit_id' => 'required|exists:unit_kerjas,id',
            'level_id' => 'required|exists:level_points,id',
        ]);

        $levelunit = LevelUnit::findOrFail($this->levelunit_id);
        $levelunit->update([
            'unit_id' => $this->unit_id,
            'level_id' => $this->level_id,
        ]);

        session()->flash('success', 'Level Unit berhasil diperbarui!');
        return redirect()->route('levelunit.index');
    }

    public function render()
    {
        return view('livewire.edit-level-unit');
    }
}
