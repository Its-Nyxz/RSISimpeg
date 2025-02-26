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

    public $katjab_nama;
    public $suggestions = [];

    public function fetchSuggestions($type, $query)
    {
        if ($type === 'jabatan') {
            $this->suggestions = KategoriJabatan::where('nama', 'like', "%$query%")
                ->get()
                ->toArray();
        }

        // dd($this->suggestions);
    }

    public function selectJabatan($id, $name)
    {
        $this->katjab_id = $id;
        $this->katjab_nama = $name;
        $this->suggestions = [];
    }

    protected $rules = [
        'katjab_id' => 'required|exists:kategori_jabatans,id',
        'kualifikasi' => 'required|string|max:255',
        'nominal' => 'required|numeric|min:0',
        'deskripsi' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->katjabs = KategoriJabatan::where('tunjangan', 'jabatan')->get();
    }

    public function store()
    {
        // dd($this->katjab_id);
        $this->validate();

        MasterJabatan::create([
            'katjab_id' => $this->katjab_id,
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
