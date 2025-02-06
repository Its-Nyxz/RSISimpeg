<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterFungsi;
use App\Models\KategoriJabatan;

class CreateFungsi extends Component
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
        $this->katjabs = KategoriJabatan::where('tunjangan', 'fungsi')->get();
    }

    public function save()
    {
        $this->validate();

        MasterFungsi::create([
            'katjab_id' => $kategori->id,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        return redirect()->route('fungsional.index')->with('success', 'Data Tunjangan Fungsional baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-fungsi', [
            'katjabs' => $this->katjabs,
        ]);
    }
}
