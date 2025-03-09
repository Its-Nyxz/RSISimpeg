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
    public $katjab_nama;
    public $suggestions = [];

    public function fetchSuggestions($type, $query)
    {
        if ($type === 'jabatan') {
            $this->suggestions = KategoriJabatan::where('nama', 'like', "%$query%")
                ->get()
                ->toArray();
        }
    }

    public function selectJabatan($id, $name)
    {
        $this->katjab_id = $id;
        $this->katjab_nama = $name;
        $this->suggestions = [];
    }

    protected $rules = [
        'katjab_id' => 'required|exists:kategori_jabatans,id',
        'nama' => 'required',
        'nominal' => 'required',
        'deskripsi' => 'required',
    ];

      // Method untuk memilih fungsi
    public function selectFungsi($id, $name)
    {
        $this->fungsi_id = $id;
        $this->fungsi_nama = $name;
        $this->showDropdown = false; // Tutup dropdown setelah memilih
    }
  
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
