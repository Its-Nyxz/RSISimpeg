<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterUmum;
use App\Models\KategoriJabatan;

class CreateUmum extends Component
{
    public $katjab_id;
    public $nominal;
    public $deskripsi;

    public $katjabs = [];

    protected $rules = [
        'katjab_id' => 'required|exists:kategori_jabatan,id',
        'nominal' => 'required|numeric|min:0',
        'deskripsi' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->katjabs = KategoriJabatan::where('tunjangan', 'umum')->get();
    }

    public function store()
    {
        $this->validate();

        MasterUmum::create([
            'katjab_id' => $this->katjab_id,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        // $this->reset('katjab_id', 'nominal', 'deskripsi', 'search');

        return redirect()->route('umum.index')->with('success', 'Data Tunjangan Umum baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-umum', [
            'katjabs' => $this->katjabs,
        ]);
    }
}
