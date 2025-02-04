<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterFungsi;
use App\Models\MasterPotongan;

class CreatePotongan extends Component
{
    public $fungsi_id;
    public $fungsi_nama; // Menyimpan nama fungsi yang dipilih
    public $nama;
    public $nominal;
    public $deskripsi;
    public $fungsis = [];
    public $showDropdown = false; // Kontrol visibilitas dropdown

    protected $rules = [
        'fungsi_id' => 'required|exists:master_fungsi,id',
        'nama' => 'required|string',
        'nominal' => 'required|numeric',
        'deskripsi' => 'required|string',
    ];

    public function mount()
    {
        $this->fungsis = MasterFungsi::all();
    }

    // Method untuk memilih fungsi
    public function selectFungsi($id, $name)
    {
        $this->fungsi_id = $id;
        $this->fungsi_nama = $name;
        $this->showDropdown = false; // Tutup dropdown setelah memilih
    }

    // Method untuk menyimpan data potongan
    public function store()
    {
        $this->validate();

        MasterPotongan::create([
            'fungsi_id' => $this->fungsi_id,
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
            'fungsis' => $this->fungsis,
        ]);
    }
}
