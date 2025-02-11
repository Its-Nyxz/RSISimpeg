<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterJabatan;
use App\Models\KategoriJabatan;

class EditJabatan extends Component
{
    public $jabatan_id;
    public $kualifikasi;
    public $nominal;
    public $deskripsi;
    public $katjab_id;
    public $katjab_id_nama;

    public $katjabs = [];

    public function mount($jabatanId)
    {
        $jabatan = MasterJabatan::findOrFail($jabatanId);
        $this->jabatan_id = $jabatan->id;
        $this->katjab_id = $jabatan->katjab_id;
        $this->kualifikasi = $jabatan->kualifikasi;
        $this->nominal = $jabatan->nominal;
        $this->deskripsi = $jabatan->deskripsi;
        $this->katjabs = KategoriJabatan::where('tunjangan', 'jabatan')->get();

        // Ambil nama kategori jabatan berdasarkan katjab_id
        $this->katjab_id_nama = KategoriJabatan::where('id', $this->katjab_id)->value('nama');
    }

    public function updateJabatan()
    {
        $this->validate([
            'katjab_id' => 'required|exists:kategori_jabatan,id',
            'kualifikasi' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
        ]);

        $jabatan = MasterJabatan::findOrFail($this->jabatan_id);
        $jabatan->update([
            'katjab_id' => $this->katjab_id,
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
