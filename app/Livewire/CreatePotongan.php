<?php

namespace App\Livewire;

use App\Models\KategoriJabatan;
use Livewire\Component;
use App\Models\MasterFungsi;
use App\Models\MasterPotongan;

class CreatePotongan extends Component
{
    public $katjab_id;
    public $nama;
    public $nominal;
    public $deskripsi;
    public $jabatans = [];

    protected $rules = [
        'katjab_id' => 'required|exists:kategori_jabatans,id',
        'nama' => 'required',
        'nominal' => 'required',
        'deskripsi' => 'required',
    ];

    public function mount()
    {
        $this->jabatans = KategoriJabatan::all();
    }

    public function store()
    {
        $this->validate();

        MasterPotongan::create([
            'katjab_id' => $this->katjab_id,
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'Potongan berhasil ditambahkan!');
        return redirect()->route('potongan.index');
    }

    public function render()
    {
        return view('livewire.create-potongan', [
            'jabatans' => $this->jabatans,
        ]);
    }
}
