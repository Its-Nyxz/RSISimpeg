<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shift;

class CreateShift extends Component
{
    public $nama_shift;
    public $jam_masuk;
    public $jam_keluar;
    public $keterangan;


    // Method to store the shift
    public function store()
    {
        // Validation
        $this->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

            'nama_shift' => $this->nama_shift,
            'jam_masuk' => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
            'keterangan' => $this->keterangan,
        ]);

        // Provide a success message
        return redirect()->route('absensi.index')->with('success', 'Data Shift baru berhasil ditambahkan.');
    }

    // Method to render the component view

    public function render()
    {
        return view('livewire.create-shift');
    }
}
