<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterGolongan;

class CreateGolongan extends Component
{
    public $nama;

    protected $rules = [
        'nama' => 'required|string|max:255',
    ];

    public function save()
    {
        // Validasi dan simpan data golongan
        $this->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Menyimpan data golongan baru
        MasterGolongan::create([
            'nama' => $this->nama,
        ]);

        // Reset input setelah simpan
        $this->reset('nama');

        // Redirect dengan membawa pesan sukses
        return redirect()->route('golongan.index')->with('success', 'Data golongan baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-golongan');
    }

}

