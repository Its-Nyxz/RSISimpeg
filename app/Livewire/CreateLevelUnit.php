<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LevelUnit;
use App\Models\UnitKerja;
use App\Models\LevelPoint;

class CreateLevelUnit extends Component
{
    public $unit_nama;    // Menyimpan nama unit yang dipilih (hanya untuk tampilan)
    public $unit_id;      // Menyimpan ID unit untuk database
    public $level_nama;   // Menyimpan nama level yang dipilih (hanya untuk tampilan)
    public $level_id;     // Menyimpan ID level untuk database

    public $unitkerja = [];
    public $levelpoint = [];

    protected $rules = [
        'unit_id' => 'required|exists:unit_kerjas,id',
        'level_id' => 'required|exists:level_points,id',
    ];

    public function mount()
    {
        $this->unitkerja = UnitKerja::all();
        $this->levelpoint = LevelPoint::all();
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

    // Method untuk memilih unit kerja
    public function selectUnit($id, $name)
    {
        $this->unit_id = $id;
        $this->unit_nama = $name;
    }

    // Method untuk memilih level point
    public function selectLevel($id, $name)
    {
        $this->level_id = $id;
        $this->level_nama = $name;
    }

    public function render()
    {
        return view('livewire.create-level-unit', [
            'unitkerja' => $this->unitkerja,
            'levelpoint' => $this->levelpoint,
        ]);
    }
}