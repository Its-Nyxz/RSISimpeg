<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasaKerja;

class EditMasaKerja extends Component
{
    public $masa_id;
    public $nama;
    public $point;

    public function mount($masaId){
        $masakerja = MasaKerja::findOrFail($masaId);
        $this->masa_id = $masakerja->id;
        $this->nama = $masakerja->nama;
        $this->point = $masakerja->point;
    }

    public function updateMasaKerja(){
        $this->validate([
            'nama' => 'required',
            'point' => 'required',
        ]);

        $masakerja = MasaKerja::findOrFail($this->masa_id);
        $masakerja->update([
            'nama' => $this->nama,
            'point' => $this->point,
        ]);

        return redirect()->route('tukin.index')->with('success', 'Data Masa Kerja berhasil diupdate.');
    }
    
    public function render()
    {
        return view('livewire.edit-masa-kerja');
    }
}
