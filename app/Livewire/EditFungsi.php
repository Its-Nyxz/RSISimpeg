<?php

namespace App\Livewire;

use App\Models\MasterFungsi;
use Livewire\Component;
use App\Models\KategoriJabatan;

class EditFungsi extends Component
{
    public $fungsi_id;
    public $katjab_id;
    public $nominal;
    public $deskripsi;

    public $katjabs = [];

    public function mount($fungsiId){
        $fungsi = MasterFungsi::findOrFail($fungsiId);
        $this->fungsi_id = $fungsi->id;
        $this->katjab_id = $fungsi->katjab_id;
        $this->nominal = $fungsi->nominal;
        $this->deskripsi = $fungsi->deskripsi;
        $this->katjabs = KategoriJabatan::where('tunjangan', 'umum')->get();
    }

    public function updateFungsi(){
        $this->validate([
            'katjab_id' => 'required|exists:kategori_jabatan,id',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
        ]);

        $fungsi = MasterFungsi::findOrFail($this->fungsi_id);
        $fungsi->update([
            'katjab_id' => $this->katjab_id,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'fungsi berhasil diperbarui!');
        return redirect()->route('fungsional.index');
    }
    public function render()
    {
        return view('livewire.edit-fungsi');
    }
}
