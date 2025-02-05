<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterUmum;
use App\Models\KategoriJabatan;

class CreateUmum extends Component
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
        $this->validate();

        $kategori = KategoriJabatan::where('nama', $this->nama)
                    ->where('tunjangan', 'umum')
                    ->first();

        if (!$kategori) {
            $kategori = KategoriJabatan::create([
                'nama' => $this->nama,
                'tunjangan' => 'umum'
            ]);
        }

        MasterUmum::create([
            'katjab_id' => $kategori->id,
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset('nama');
        $this->reset('nominal');
        $this->reset('deskripsi');


        return redirect()->route('umum.index')->with('success', 'Data Tunjangan Umum baru berhasil ditambahkan.');

    }

    public function render()
    {
        
        return view('livewire.create-umum');
    }
}
