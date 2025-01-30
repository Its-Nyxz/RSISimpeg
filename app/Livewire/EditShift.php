<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shift;

class EditShift extends Component
{
    public $shift_id;
    public $nama_shift;
    public $jam_masuk;
    public $jam_keluar;
    public $keterangan;

    public function mount($shiftId){
        $shift = Shift::findOrFail($shiftId);
        $this->shift_id = $shift->id;
        $this->nama_shift = $shift->nama_shift;
        $this->jam_masuk = $shift->jam_masuk;
        $this->jam_keluar = $shift->jam_keluar;
        $this->keterangan = $shift->keterangan;
    }

    public function updateShift(){
        $this->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

        $shift = Shift::findOrFail($this->shift_id);
        $shift->update([
            'nama_shift' => $this->nama_shift,
            'jam_masuk' => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
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
