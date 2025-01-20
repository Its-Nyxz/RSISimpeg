<?php

namespace App\Livewire;

use App\Models\MasterPendidikan;
use App\Models\MasterGolongan;
use Livewire\Component;

class CreatePendidikan extends Component
{
    public $nama;
    public $deskripsi;
    public $minim_gol;
    public $maxim_gol;
    public $golongans;

    // Mengambil data golongan untuk dropdown
    public function mount()
    {
        $this->golongans = MasterGolongan::all();
    }

    // Menyimpan data pendidikan baru
    public function store()
    {
        $validatedData = $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'minim_gol' => 'required|exists:master_golongan,id',
            'maxim_gol' => 'required|exists:master_golongan,id',
        ]);

        // Menyimpan data pendidikan ke database
        MasterPendidikan::create($validatedData);

        // Reset input setelah data disimpan
        $this->reset();

        // Memberikan notifikasi sukses
        session()->flash('success', 'Pendidikan berhasil ditambahkan!');

        // Redirect ke halaman pendidikan
        return redirect()->route('pendidikan.index');
    }

    public function render()
    {
        return view('livewire.create-pendidikan');
    }
}

