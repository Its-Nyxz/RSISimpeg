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
            'katjab_id' => $this->katjab_id,
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
