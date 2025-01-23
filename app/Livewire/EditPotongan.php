<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterPotongan;
use App\Models\MasterFungsi;

class EditPotongan extends Component
{
    public $fungsi_id;
    public $potongan_id;
    public $fungsis;
    public $nama;
    public $nominal;
    public $deskripsi;

    public function mount($potonganId){
        $potongan = MasterPotongan::findOrFail($potonganId);
        $this->potongan_id = $potongan->id;
        $this->fungsi_id = $potongan->fungsi_id;
        $this->nama = $potongan->nama;
        $this->nominal = $potongan->nominal;
        $this->deskripsi = $potongan->deskripsi;
        $this->fungsis = MasterFungsi::all();
    }

    public function updatePotongan(){
        $this->validate([
            'fungsi_id' => 'required|exists:master_fungsi,id',
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $potongan = MasterPotongan::findOrFail($this->potongan_id);
        $potongan->update([
            'fungsi_id' => $this->fungsi_id,
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'Potongan berhasil diperbarui!');
        return redirect()->route('potongan.index');
    }
    public function render()
    {
        return view('livewire.edit-potongan');
    }
}
