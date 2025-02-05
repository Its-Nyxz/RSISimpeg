<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterUmum;

class EditUmum extends Component
{
    public $umum_id;
    public $nama;
    public $nominal;
    public $deskripsi;

    public function mount($umumId){
        $umum = MasterUmum::findOrFail($umumId);
        $this->umum_id = $umum->id;
        $this->nama = $umum->nama;
        $this->nominal = $umum->nominal;
        $this->deskripsi = $umum->deskripsi;
    }

    public function updateUmum(){
        $this->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $umum = MasterUmum::findOrFail($this->umum_id);
        $umum->update([
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'Umum berhasil diperbarui!');
        return redirect()->route('umum.index');
    }
    public function render()
    {
        return view('livewire.edit-umum');
    }
}
