<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Shift;
use Livewire\Component;

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

        // Konversi waktu ke timezone Asia/Jakarta
        $jamMasuk = Carbon::createFromFormat('H:i', $this->jam_masuk, 'Asia/Jakarta')->format('H:i:s');
        $jamKeluar = Carbon::createFromFormat('H:i', $this->jam_keluar, 'Asia/Jakarta')->format('H:i:s');

        Shift::create([
            'nama_shift' => $this->nama_shift,
            'jam_masuk' => $jamMasuk,
            'jam_keluar' => $jamKeluar,
            'keterangan' => $this->keterangan,
        ]);

        // Reset input setelah simpan
        $this->reset('nama_shift', 'jam_masuk', 'jam_keluar', 'keterangan');
        return redirect()->route('shift.index')->with('success', 'Data Shift baru berhasil ditambahkan.');
    }

    // Method to render the component view

    public function render()
    {
        return view('livewire.create-shift');
    }
}
