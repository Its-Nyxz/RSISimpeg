<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitKerja;

class CreateUnitKerja extends Component
{
    public $nama;
    public $kode;
    public $keterangan;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'kode' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:255',
    ];

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
        'kode' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:255',
        ]);

        UnitKerja::create([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'keterangan' => $this->keterangan,
        ]);

        // Reset input setelah simpan
        $this->reset('nama');
        $this->reset('kode');
        $this->reset('keterangan');

        // Redirect dengan membawa pesan sukses

        return redirect()->route('unitkerja.index')->with('success', 'Data Unit Kerja baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-unit-kerja');
    }
}
