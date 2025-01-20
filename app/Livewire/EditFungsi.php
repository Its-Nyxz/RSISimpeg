<?php

namespace App\Livewire;

use App\Models\MasterFungsi;
use Livewire\Component;

class EditFungsi extends Component
{
    public $fungsi_id;
    public $nama;
    public $nominal;
    public $deskripsi;

    public function mount($fungsiId){
        $fungsi = MasterFungsi::findOrFail($fungsiId);
        $this->fungsi_id = $fungsi->id;
        $this->nama = $fungsi->nama;
        $this->nominal = $fungsi->nominal;
        $this->deskripsi = $fungsi->deskripsi;
    }

    public function updateFungsi(){
        $this->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $fungsi = MasterFungsi::findOrFail($this->fungsi_id);
        $fungsi->update([
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'fungsi berhasil diperbarui!');
        return redirect()->route('tunjangan.index');
    }
    public function render()
    {
        return view('livewire.edit-fungsi');
    }
}
