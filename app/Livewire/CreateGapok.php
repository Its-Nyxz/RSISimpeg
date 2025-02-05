<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterGapok;
use App\Models\MasterGolongan;

class CreateGapok extends Component
{
    public $golongan_id; // Properti untuk menyimpan pilihan golongan
    public $nominal_gapok; // Properti untuk nominal gaji pokok
    public $golongans = []; // Properti untuk daftar golongan

    protected $rules = [
        'golongan_id' => 'required|exists:master_golongan,id', // Validasi pilihan golongan
        'nominal_gapok' => 'required|numeric|min:0', // Validasi nominal gaji pokok
    ];

    public function mount()
    {
        $this->golongans = MasterGolongan::all(); // Ambil daftar golongan dari table master_golongan
    }

    public function store()
    {
        $this->validate(); // Validasi input

        // Simpan Gaji Pokok ke dalam table master_gapok
        MasterGapok::create([
            'gol_id' => $this->golongan_id,
            'nominal_gapok' => $this->nominal_gapok,
        ]);

        // Flash message dan redirect
        session()->flash('success', 'Gaji Pokok berhasil ditambahkan!');
        return redirect()->route('gapok.index');
    }

    public function render()
    {
        return view('livewire.create-gapok', [
            'golongans' => $this->golongans,
        ]);
    }
}