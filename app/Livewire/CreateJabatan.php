<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterJabatan;
use App\Models\KategoriJabatan;

class CreateJabatan extends Component
{
    public $kualifikasi, $nominal, $deskripsi;
    public $katjab_id;

    public $katjabs = [];

    protected $rules = [
        'katjab_id' => 'required|exists:kategori_jabatan,id',
        'kualifikasi' => 'required|string|max:255',
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

        MasterJabatan::create([
            'katjab_id' => $katjab_id,
            'kualifikasi' => $this->kualifikasi,
            'nominal' => $this->nominal,
            'deskripsi' => $this->deskripsi,
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Data Tunjangan Jabatan berhasil ditambahkan.');
    }


    public function render()
    {
        return view('livewire.create-jabatan', [
            'katjabs' => $this->katjabs,
        ]);
    }
}
