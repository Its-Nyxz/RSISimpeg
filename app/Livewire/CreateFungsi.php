<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterFungsi;
use App\Models\KategoriJabatan;

class CreateFungsi extends Component
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
                    ->where('tunjangan', 'fungsi')
                    ->first();

        if (!$kategori) {
            $kategori = KategoriJabatan::create([
                'nama' => $this->nama,
                'tunjangan' => 'fungsi'
            ]);
        }

        MasterFungsi::create([
            'katjab_id' => $kategori->id,
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset('nama');
        $this->reset('nominal');
        $this->reset('deskripsi');

        return redirect()->route('fungsional.index')->with('success', 'Data Tunjangan Fungsional baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-fungsi');
    }
}
