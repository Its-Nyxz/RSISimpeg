<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterGolongan;

class EditGolongan extends Component
{
    public $golongan_id;
    public $nama;

    public function mount($golonganId){
        
        $golongan = MasterGolongan::findOrFail($golonganId);
        $this->golongan_id = $golongan->id;
        $this->nama = $golongan->nama;
    }

    public function updateGolongan(){
        $this->validate([
            'nama' => 'required',
        ]);

        $golongan = MasterGolongan::findOrFail($this->golongan_id);
        $golongan->update([
            'nama' => $this->nama,
        ]);

        session()->flash('success', 'Golongan berhasil diperbarui!');
        return redirect()->route('golongan.index');
    }
    public function render()
    {
        return view('livewire.edit-golongan');
    }
}
