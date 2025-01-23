<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasaKerja;

class CreateMasaKerja extends Component
{
    public $nama;
    public $point;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'point' => 'required|numeric|min:0',
    ];

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'point' => 'required|numeric|min:0',
        ]);

        MasaKerja::create([
            'nama' =>$this->nama,
            'point' => $this->point,
        ]);

        $this->reset('nama');
        $this->reset('point');
        // Redirect dengan membawa pesan sukses
        return redirect()->route('tukin.index')->with('success', 'Data Masa Kerja baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-masa-kerja');
    }
}
