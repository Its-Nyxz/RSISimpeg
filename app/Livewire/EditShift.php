<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\UnitKerja;
use Livewire\Component;

class EditShift extends Component
{
    public $shift_id;
    public $nama_shift;
    public $jam_masuk;
    public $jam_keluar;
    public $keterangan;
    public $unit_id;
    public $unit_kerja;
    public $unitKerjaOptions = [];

    public function mount($shiftId)
    {
        $shift = Shift::findOrFail($shiftId);
        $this->shift_id = $shift->id;
        $this->nama_shift = $shift->nama_shift;
        $this->jam_masuk = $shift->jam_masuk ? Carbon::parse($shift->jam_masuk)->setTimezone('Asia/Jakarta')->format('H:i') : '';
        $this->jam_keluar = $shift->jam_keluar ? Carbon::parse($shift->jam_keluar)->setTimezone('Asia/Jakarta')->format('H:i') : '';
        $this->keterangan = $shift->keterangan;
        $this->unit_id = $shift->unit_id;
        $this->unit_kerja = UnitKerja::where('id', $this->unit_id)->value('nama');
    }

    public function fetchSuggestions($field, $value)
    {
        if ($field === 'unit_kerja') {
            $this->unitKerjaOptions = UnitKerja::where('nama', 'like', "%$value%")->get();
        }
    }

    public function selectUnitKerja($id, $nama)
    {
        $this->unit_id = $id;
        $this->unit_kerja = $nama;
        $this->unitKerjaOptions = []; // Sembunyikan dropdown setelah memilih
    }

    public function updateShift()
    {
        $this->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string',
            'unit_id' => 'required|exists:unit_kerjas,id',
        ]);

        $shift = Shift::findOrFail($this->shift_id);
        $shift->update([
            'nama_shift' => $this->nama_shift,
            'jam_masuk' => $this->jam_masuk !== '' ? Carbon::createFromFormat('H:i', $this->jam_masuk, 'Asia/Jakarta')->format('H:i') : null,
            'jam_keluar' => $this->jam_keluar !== '' ? Carbon::createFromFormat('H:i', $this->jam_keluar, 'Asia/Jakarta')->format('H:i') : null,
            'keterangan' => $this->keterangan,
            'unit_id' => $this->unit_id,
        ]);

        return redirect()->route('shift.index')->with('success', 'Shift berhasil diperbarui');
    }

    public function render()
    {
        return view('livewire.edit-shift');
    }
}
