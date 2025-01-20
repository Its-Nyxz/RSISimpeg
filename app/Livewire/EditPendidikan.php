<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterPendidikan;
use App\Models\MasterGolongan;

class EditPendidikan extends Component
{
    public $pendidikan_id, $nama, $deskripsi, $minim_gol, $maxim_gol;
    public $golongans;

    // Method mount untuk menangkap parameter id dari route
    public function mount($pendidikanId)
    {
        $pendidikan = MasterPendidikan::findOrFail($pendidikanId);
        $this->pendidikan_id = $pendidikan->id;
        $this->nama = $pendidikan->nama;
        $this->deskripsi = $pendidikan->deskripsi;
        $this->minim_gol = $pendidikan->minim_gol;
        $this->maxim_gol = $pendidikan->maxim_gol;

        // Ambil semua data golongan
        $this->golongans = MasterGolongan::all();
    }

    // Method untuk update data
    public function updatePendidikan()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'minim_gol' => 'required|exists:master_golongan,id',
            'maxim_gol' => 'required|exists:master_golongan,id',
        ]);

        // Update data pendidikan
        $pendidikan = MasterPendidikan::find($this->pendidikan_id);
        $pendidikan->update([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'minim_gol' => $this->minim_gol,
            'maxim_gol' => $this->maxim_gol,
        ]);

        session()->flash('success', 'Pendidikan berhasil diperbarui!');
        return redirect()->route('pendidikan.index');
    }

    public function render()
    {
        return view('livewire.edit-pendidikan');
    }
}
