<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OpsiAbsen;

class CreateOpsi extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function save()
    {
        // Validasi dan simpan data golongan
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        // Menyimpan data golongan baru
        OpsiAbsen::create([
            'name' => $this->name,
        ]);

        // Reset input setelah simpan
        $this->reset('name');

        // Redirect dengan membawa pesan sukses
        return redirect()->route('absensi.index')->with('success', 'Data Opsi baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-opsi');
    }
}
