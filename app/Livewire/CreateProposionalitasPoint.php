<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProposionalitasPoint;
use App\Models\MasterFungsi;
use App\Models\MasterUmum;
use App\Models\UnitKerja;

class CreateProposionalitasPoint extends Component
{
    public $proposable_id;
    public $unitkerja_id;
    public $unit_id;
    public $point;
    public $proposables;
    public $unitkerjas;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Mengambil data dari MasterFungsi dan MasterUmum
        $this->proposables = MasterFungsi::with('kategorijabatan')->get()->merge(
            MasterUmum::with('kategorijabatan')->get()
        );
        
        $this->unitkerjas = UnitKerja::all();
    }

    public function store()
{
    $this->validate([
        'proposable_id' => 'required',
        'unit_id' => 'nullable|exists:unit_kerjas,id', // Ganti unitkerja_id → unit_id
        'point' => 'required|numeric',
    ]);

    // Mencari tipe model berdasarkan ID
    $proposable = $this->proposables->firstWhere('id', $this->proposable_id);

    ProposionalitasPoint::create([
        'proposable_id' => $this->proposable_id,
        'proposable_type' => get_class($proposable),
        'unit_id' => $this->unit_id, // Ganti unitkerja_id → unit_id
        'point' => $this->point,
    ]);

    return redirect()->route('proposionalitas.index')->with('success', 'Proposionalitas berhasil ditambahkan!');
}

    

    public function render()
    {
        return view('livewire.create-proposionalitas-point', [
            'proposables' => $this->proposables,
            'unitkerjas' => $this->unitkerjas,
        ]);
    }
}
