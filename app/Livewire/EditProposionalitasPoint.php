<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProposionalitasPoint;
use App\Models\MasterFungsi;
use App\Models\MasterUmum;
use App\Models\UnitKerja;

class EditProposionalitasPoint extends Component
{
    public $proposionalitas_id;
    public $proposable_id;
    public $unit_id;
    public $point;
    public $proposables;
    public $unitkerjas;

    public $proposable_name;
    public $unitkerja_name;

    public function mount($proposionalitasId)
    {
        $proposionalitas = ProposionalitasPoint::findOrFail($proposionalitasId);
        $this->proposionalitas_id = $proposionalitas->id;
        $this->proposable_id = $proposionalitas->proposable_id;
        $this->unit_id = $proposionalitas->unit_id;
        $this->point = $proposionalitas->point;

        $this->loadData();

        // Set nama master dan unit kerja berdasarkan ID yang sudah tersimpan
        $proposable = MasterFungsi::find($this->proposable_id) ?? MasterUmum::find($this->proposable_id);
        $this->proposable_name = $proposable ? ($proposable->kategorijabatan->nama ?? $proposable->name) : '';

        $unitkerja = UnitKerja::find($this->unit_id);
        $this->unitkerja_name = $unitkerja ? $unitkerja->nama : '';
    }

    public function loadData()
    {
        $this->proposables = MasterFungsi::with('kategorijabatan')->get()->merge(
            MasterUmum::with('kategorijabatan')->get()
        );
        $this->unitkerjas = UnitKerja::all();
    }

    public function updateProposionalitas()
    {
        $this->validate([
            'proposable_id' => 'required',
            'unit_id' => 'nullable|exists:unit_kerjas,id',
            'point' => 'required|numeric',
        ]);

        $proposionalitas = ProposionalitasPoint::findOrFail($this->proposionalitas_id);
        $proposionalitas->update([
            'proposable_id' => $this->proposable_id,
            'unit_id' => $this->unit_id,
            'point' => $this->point,
        ]);

        session()->flash('success', 'Proposionalitas berhasil diperbarui!');
        return redirect()->route('proposionalitas.index');
    }

    public function render()
    {
        return view('livewire.edit-proposionalitas-point');
    }
}
