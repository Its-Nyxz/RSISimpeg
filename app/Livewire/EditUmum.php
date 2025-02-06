<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterUmum;
use App\Models\KategoriJabatan;

class EditUmum extends Component
{
    public $umum_id;
    public $katjab_id;
    public $nominal;
    public $deskripsi;
    
    public $katjabs = [];

    public function mount($umumId){
        $umum = MasterUmum::findOrFail($umumId);
        $this->umum_id = $umum->id;
        $this->katjab_id = $umum->katjab_id;
        $this->nominal = $umum->nominal;
        $this->deskripsi = $umum->deskripsi;
        $this->katjabs = KategoriJabatan::where('tunjangan', 'umum')->get();
    }

    public function updateUmum(){
        $this->validate([
            'katjab_id' => 'required|exists:kategori_jabatan,id',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
        ]);

        $umum = MasterUmum::findOrFail($this->umum_id);
        $umum->update([
            'katjab_id' => $this->katjab_id,
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
