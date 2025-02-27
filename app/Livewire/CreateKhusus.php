<?php

namespace App\Livewire;

use App\Models\MasterKhusus;
use Livewire\Component;
class CreateKhusus extends Component
{
    public $nama;
    public $nominal;
    public $deskripsi;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'nominal' => 'required|numeric|min:0',
        'deskripsi' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
        ]);

        MasterKhusus::create([
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        // Reset input setelah simpan
        $this->reset('nama');
        $this->reset('nominal');
        $this->reset('deskripsi');

        // Redirect dengan membawa pesan sukses

        return redirect()->route('khusus.index')->with('success', 'Data Tunjangan Khusus baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-khusus');
    }
}
