<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KategoriJabatan;
use Illuminate\Support\Facades\Session;

class CreateKategoriJabatan extends Component
{
    public $nama, $tunjangan, $keterangan;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'tunjangan' => 'required|in:jabatan,fungsi,umum',
        'keterangan' => 'nullable|string|max:500',
    ];

    public function save()
    {
        $this->validate();

        KategoriJabatan::create([
            'nama' => $this->nama,
            'tunjangan' => $this->tunjangan,
            'keterangan' => $this->keterangan,
        ]);

        Session::flash('success', 'Kategori Jabatan berhasil ditambahkan.');

        return redirect()->route('katjab.index'); // Sesuaikan dengan rute yang benar
    }

    public function render()
    {
        return view('livewire.create-kategori-jabatan');
    }
}
