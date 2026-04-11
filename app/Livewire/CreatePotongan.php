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
    public $no_urut = null;
    public $is_active = 1;

    protected $rules = [
        'nama' => 'required|string|unique:master_potongan,nama',
        'is_wajib' => 'boolean',
        'nominal' => 'required|integer|min:0',
        'no_urut' => 'nullable|integer|min:1|unique:master_potongan,no_urut',
        'is_active' => 'nullable|in:0,1',
    ];

    protected $messages = [
        'no_urut.unique' => 'No urut sudah digunakan, silakan pilih nomor lain.',
        'nama.unique' => 'Nama potongan sudah ada.',
    ];
    
    public function store()
    {
        $this->validate();

        MasterPotongan::create([
            'nama' => strtolower($this->nama),
            'slug' => Str::slug($this->nama),
            'is_wajib' => $this->is_wajib,
            'nominal' => $this->nominal, // Tambahkan field nominal
            'no_urut' => $this->no_urut !== '' ? $this->no_urut : null,
            'is_active' => $this->is_active !== '' ? (int) $this->is_active : null,
        ]);

        session()->flash('success', 'Master potongan berhasil ditambahkan!');
        return redirect()->route('potongan.index');
    }

    public function render()
    {
        return view('livewire.create-potongan');
    }
}
