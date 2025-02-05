<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterJabatan;
use App\Models\KategoriJabatan;

class CreateJabatan extends Component
{
    public $nama, $kualifikasi, $nominal, $deskripsi;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'kualifikasi' => 'required|string|max:255',
        'nominal' => 'required|numeric|min:0',
        'deskripsi' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        $kategori = KategoriJabatan::where('nama', $this->nama)
                    ->where('tunjangan', 'jabatan')
                    ->first();

        if (!$kategori) {
            $kategori = KategoriJabatan::create([
                'nama' => $this->nama,
                'tunjangan' => 'jabatan'
            ]);
        }

        MasterJabatan::create([
            'katjab_id' => $kategori->id,
            'nama' => $this->nama,
            'kualifikasi' => $this->kualifikasi,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset(['nama', 'kualifikasi', 'nominal', 'deskripsi']);

        return redirect()->route('jabatan.index')->with('success', 'Data Tunjangan Jabatan berhasil ditambahkan.');
    }


    public function render()
    {
        return view('livewire.create-jabatan');
    }
}
