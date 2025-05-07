<?php

namespace App\Livewire;

use App\Models\KategoriJabatan;
use Livewire\Component;
use App\Models\MasterFungsi;
use App\Models\MasterPotongan;

class CreatePotongan extends Component
{
    public $nama;
    public $jenis = 'nominal'; // default
    public $is_wajib = false;

    protected $rules = [
        'nama' => 'required|string|unique:master_potongan,nama',
        'jenis' => 'required|in:nominal,persentase',
        'is_wajib' => 'boolean',
    ];

    public function store()
    {
        $this->validate();

        MasterPotongan::create([
            'nama' => strtolower($this->nama),
            'jenis' => $this->jenis,
            'is_wajib' => $this->is_wajib,
        ]);

        session()->flash('success', 'Master potongan berhasil ditambahkan!');
        return redirect()->route('potongan.index');
    }

    public function render()
    {
        return view('livewire.create-potongan');
    }
}
