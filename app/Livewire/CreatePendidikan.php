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
    public $golongans = [];
    
    public $minim_gol_nama;
    public $maxim_gol_nama;
    public $minimGolonganOptions = [];
    public $maximGolonganOptions = [];

    // Mengambil data golongan untuk dropdown
    public function mount()
    {
        $this->golongans = MasterGolongan::all();
    }

    public function fetchSuggestions($field, $query)
    {
        if ($field === 'minim_gol') {
            $this->minimGolonganOptions = MasterGolongan::where('nama', 'like', "%$query%")
                ->get();
        } elseif ($field === 'maxim_gol') {
            $this->maximGolonganOptions = MasterGolongan::where('nama', 'like', "%$query%")
                ->get();
        }
    }

    public function selectGolongan($field, $id, $name)
    {
        if ($field === 'minim_gol') {
            $this->minim_gol = $id;
            $this->minim_gol_nama = $name;
            $this->minimGolonganOptions = [];
        } elseif ($field === 'maxim_gol') {
            $this->maxim_gol = $id;
            $this->maxim_gol_nama = $name;
            $this->maximGolonganOptions = [];
        }
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
