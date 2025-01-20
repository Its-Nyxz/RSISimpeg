<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterTrans;

class CreateTrans extends Component
{
    public $nama;
    public $nom_makan;
    public $nom_transport;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'nom_makan' => 'required|numeric|min:0',
        'nom_transport' => 'required|numeric|min:0',

    ];

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'nom_makan' => 'required|numeric|min:0',
            'nom_transport' => 'required|numeric|min:0',
        ]);

        MasterTrans::create([
            'nama' => $this->nama,
            'nom_makan' => $this->nom_makan,
            'nom_transport' => $this->nom_transport,
        ]);

        // Reset input setelah simpan
        $this->reset('nama');
        $this->reset('nom_makan');
        $this->reset('nom_transport');

        // Redirect dengan membawa pesan sukses
        return redirect()->route('tunjangan.index')->with('success', 'Data Tunjangan Tidak Tetap baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-trans');
    }
}
