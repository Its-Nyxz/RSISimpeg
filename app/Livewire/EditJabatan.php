<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterJabatan;
class EditJabatan extends Component
{
    public $jabatan_id;
    public $nama;
    public $kualifikasi;
    public $nominal;
    public $deskripsi;

    public function mount($jabatanId){
        $jabatan = MasterJabatan::findOrFail($jabatanId);
        $this->jabatan_id = $jabatan->id;
        $this->nama = $jabatan->nama;
        $this->kualifikasi = $jabatan->kualifikasi;
        $this->nominal = $jabatan->nominal;
        $this->deskripsi = $jabatan->deskripsi;
    }

    public function updateJabatan(){
        $this->validate([
            'nama' => 'required',
            'kualifikasi' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $jabatan = MasterJabatan::findOrFail($this->jabatan_id);
        $jabatan->update([
            'nama' => $this->nama,
            'kualifikasi' => $this->kualifikasi,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'Jabatan berhasil diperbarui!');
        return redirect()->route('jabatan.index');
    }
    public function render()
    {
        return view('livewire.edit-jabatan');
    }
}
