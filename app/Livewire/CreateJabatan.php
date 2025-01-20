<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterJabatan;

class CreateJabatan extends Component
{
    public $nama;
    public $kualifikasi;
    public $nominal;
    public $deskripsi;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'kualifikasi' => 'required|string|max:255',
        'nominal' => 'required|numeric|min:0',
        'deskripsi' => 'required|string|max:255',
    ];

    public function save()
    {
        // Validasi dan simpan data golongan
        $this->validate([
            'nama' => 'required|string|max:255',
            'kualifikasi' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
        ]);

        // Menyimpan data golongan baru
        MasterJabatan::create([
            'nama' => $this->nama,
            'kualifikasi' => $this->kualifikasi,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        // Reset input setelah simpan
        $this->reset('nama');
        $this->reset('kualifikasi');
        $this->reset('nominal');
        $this->reset('deskripsi');

        // Redirect dengan membawa pesan sukses
        return redirect()->route('tunjangan.index')->with('success', 'Data Tunjangan Jabatan baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-jabatan');
    }
}
