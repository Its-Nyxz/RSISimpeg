<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\MasterFungsi;
use App\Models\MasterPotongan;
use App\Models\KategoriJabatan;

class CreatePotongan extends Component
{
    public $nama;
    public $is_wajib = false;
    public $nominal = 0; // Tambahan field default

    protected $rules = [
        'nama' => 'required|string|unique:master_potongan,nama',
        'is_wajib' => 'boolean',
        'nominal' => 'required|integer|min:0',
    ];

    public function store()
    {
        $this->validate();

        MasterPotongan::create([
            'nama' => strtolower($this->nama),
            'slug' => Str::slug($this->nama),
            'is_wajib' => $this->is_wajib,
            'nominal' => $this->nominal, // Tambahkan field nominal
        ]);

        session()->flash('success', 'Master potongan berhasil ditambahkan!');
        return redirect()->route('potongan.index');
    }

    public function render()
    {
        return view('livewire.create-potongan');
    }
}
