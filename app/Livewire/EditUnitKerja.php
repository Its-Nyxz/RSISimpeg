<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitKerja;

class EditUnitKerja extends Component
{
    public $unitkerja_id;
    public $nama;
    public $kode;
    public $keterangan;

    public function mount($unitkerjaId){
        $unitkerja = UnitKerja::findOrFail($unitkerjaId);
        $this->unitkerja_id = $unitkerja->id;
        $this->nama = $unitkerja->nama;
        $this->kode = $unitkerja->kode;
        $this->keterangan = $unitkerja->keterangan;
    }

    public function updateUnitKerja(){
        $this->validate([
            'nama' => 'required',
            'kode' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $unitkerja = UnitKerja::findOrFail($this->unitkerja_id);
        $unitkerja->update([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'keterangan' => $this->keterangan,
        ]);

        return redirect()->route('unitkerja.index')->with('success', 'Data Unit Kerja berhasil di update.');
    }

    public function render()
    {
        return view('livewire.edit-unit-kerja');
    }
}
