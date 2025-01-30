<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StatusAbsen;

class CreateStatus extends Component
{
    public $nama;
    public $keterangan;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'keterangan' => 'required|string|max:255',
    ];

    public function save()
    {
        // Validasi dan simpan data golongan
        $this->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
        ]);

        // Menyimpan data golongan baru
        StatusAbsen::create([
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
        ]);

        // Reset input setelah simpan
        $this->reset('nama');
        $this->reset('keterangan');

        // Redirect dengan membawa pesan sukses
        return redirect()->route('status.index')->with('success', 'Data Status baru berhasil ditambahkan.');
    }
    public function render()
    {
        return view('livewire.create-status');
    }
}
