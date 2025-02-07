<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterGapok;
use App\Models\MasterGolongan;

class EditGapok extends Component
{
    public $gapok_id;
    public $golongan_id;
    public $nominal_gapok;
    public $golongans = [];

    public function mount($gapokId)
    {
        $gapok = MasterGapok::findOrFail($gapokId);
        $this->gapok_id = $gapok->id;
        $this->golongan_id = $gapok->gol_id;
        $this->nominal_gapok = $gapok->nominal_gapok;
        $this->golongans = MasterGolongan::all();
    }

    // Menangani pembaruan data Gapok
    public function updateGapok()
    {
        $this->validate([
            'golongan_id' => 'required|exists:master_golongan,id',
            'nominal_gapok' => 'required|numeric|min:0',
        ]);

        $gapok = MasterGapok::findOrFail($this->gapok_id);
        $gapok->update([
            'gol_id' => $this->golongan_id,
            'nominal_gapok' => $this->nominal_gapok,
        ]);

        session()->flash('success', 'Gaji Pokok berhasil diperbarui!');
        return redirect()->route('gapok.index');
    }

    public function render()
    {
        return view('livewire.edit-gapok');
    }
}