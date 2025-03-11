<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Shift;
use Livewire\Component;

class EditShift extends Component
{
    public $shift_id;
    public $nama_shift;
    public $jam_masuk;
    public $jam_keluar;
    public $keterangan;

    // Load data saat mount
    public function mount($shiftId)
    {
        $shift = Shift::findOrFail($shiftId);
        $this->shift_id = $shift->id;
        $this->nama_shift = $shift->nama_shift;

        // Konversi ke format Asia/Jakarta
        $this->jam_masuk = Carbon::parse($shift->jam_masuk)->setTimezone('Asia/Jakarta')->format('H:i');
        $this->jam_keluar = Carbon::parse($shift->jam_keluar)->setTimezone('Asia/Jakarta')->format('H:i');
        $this->keterangan = $shift->keterangan;
    }

    // Update shift
    public function updateShift()
    {
        $this->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

        $shift = Shift::findOrFail($this->shift_id);

        // Konversi waktu ke timezone Asia/Jakarta sebelum menyimpan
        $jamMasuk = Carbon::createFromFormat('H:i', $this->jam_masuk, 'Asia/Jakarta')->format('H:i:s');
        $jamKeluar = Carbon::createFromFormat('H:i', $this->jam_keluar, 'Asia/Jakarta')->format('H:i:s');

        $shift->update([
            'nama_shift' => $this->nama_shift,
            'jam_masuk' => $jamMasuk,
            'jam_keluar' => $jamKeluar,
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('success', 'Shift berhasil diperbarui!');
        return redirect()->route('shift.index');
    }
    public function render()
    {
        return view('livewire.edit-shift');
    }
}
