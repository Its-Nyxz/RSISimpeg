<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KategoriJabatan;

class EditKategoriJabatan extends Component
{
    public $katjab_id;
    public $nama;
    public $tunjangan;
    public $keterangan;

    public function mount($katjabId){
        $katjab = KategoriJabatan::findOrFail($katjabId);
        $this->katjab_id = $katjab->id;
        $this->nama = $katjab->nama;
        $this->tunjangan = $katjab->tunjangan;
        $this->keterangan = $katjab->keterangan;
    }

    public function updateKatjab(){
        $this->validate([
            'nama' => 'required|string|max:255',
            'tunjangan' => 'required|in:jabatan,fungsi,umum',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $katjab = KategoriJabatan::findOrFail($this->katjab_id);
        $katjab->update([
            'nama' => $this->nama,
            'tunjangan' => $this->tunjangan,
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('success', 'Kategori Jabatan berhasil diperbarui!');
        return redirect()->route('katjab.index');
    }

    public function render()
    {
        return view('livewire.edit-kategori-jabatan');
    }
}
