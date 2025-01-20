<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterKhusus;

class EditKhusus extends Component
{
    public $khusus_id;
    public $nama;
    public $nominal;
    public $deskripsi;

    public function mount($khususId){
        $khusus = MasterKhusus::findOrFail($khususId);
        $this->khusus_id = $khusus->id;
        $this->nama = $khusus->nama;
        $this->nominal = $khusus->nominal;
        $this->deskripsi = $khusus->deskripsi;
    }

    public function updateKhusus(){
        $this->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $khusus = MasterKhusus::findOrFail($this->khusus_id);
        $khusus->update([
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'khusus berhasil diperbarui!');
        return redirect()->route('tunjangan.index');
    }
    public function render()
    {
        return view('livewire.edit-khusus');
    }
}
