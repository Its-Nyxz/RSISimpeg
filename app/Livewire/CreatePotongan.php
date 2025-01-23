<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterFungsi;
use App\Models\MasterPotongan;

class CreatePotongan extends Component
{
    public $fungsi_id;
    public $nama;
    public $nominal;
    public $deskripsi;
    public $fungsis = [];

    protected $rules = [
        'fungsi_id' => 'required|exists:master_fungsi,id',
        'nama' => 'required',
        'nominal' => 'required',
        'deskripsi' => 'required',
    ];

    public function mount(){
        $this->fungsis = MasterFungsi::all();
    }

    public function store(){
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
