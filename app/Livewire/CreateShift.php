<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\UnitKerja;
use Livewire\Component;

class CreateShift extends Component
{
    public $unit_kerja;
    public $unit_id;
    public $nama_shift;
    public $jam_masuk;
    public $jam_keluar;
    public $keterangan;
    public $unitKerjaOptions = [];

    protected $rules = [
        'unit_id' => 'required|exists:unit_kerjas,id',
    ];

    public function mount()
    {
        // Ambil unit_id dari user yang login
        $this->unit_id = auth()->user()->unit_id ?? null;

        // Jika unit_id tersedia, langsung set nama unit_kerja
        if ($this->unit_id) {
            $this->unit_kerja = UnitKerja::find($this->unit_id)?->nama;
        }
    }

    public function fetchSuggestions($field, $query)
    {
        $this->unitKerjaOptions = UnitKerja::where('nama', 'like', "%$query%")
            ->get();
    }

    public function selectUnitKerja($id, $name)
    {
        $this->unit_id = $id;
        $this->unit_kerja = $name;
        $this->unitKerjaOptions = [];
    }

    // Method to store the shift
    public function store()
    {
        // $this->validate([
        //     'nama_shift' => 'required|string|max:255',
        //     'unit_id' => 'required|exists:unit_kerjas,id'
        //     'jam_masuk' => 'required|date_format:H:i',
        //     'jam_keluar' => 'required|date_format:H:i',
        //     'keterangan' => 'nullable|string',
        // ]);

        $this->validate();

        // Konversi waktu ke timezone Asia/Jakarta
        $jamMasuk = $this->jam_masuk
            ? Carbon::createFromFormat('H:i', $this->jam_masuk, 'Asia/Jakarta')->format('H:i:s')
            : null;

        $jamKeluar = $this->jam_keluar
            ? Carbon::createFromFormat('H:i', $this->jam_keluar, 'Asia/Jakarta')->format('H:i:s')
            : null;

        Shift::create([
            'nama_shift' => $this->nama_shift,
            'unit_id' => $this->unit_id,
            'jam_masuk' => $jamMasuk,
            'jam_keluar' => $jamKeluar,
            'keterangan' => $this->keterangan,
        ]);

        return redirect()->route('shift.index')->with('success', 'Shift berhasil ditambahkan');
    }

    // Method to render the component view

    public function render()
    {
        return view('livewire.create-shift', [
            'unitKerjaOptions' => $this->unitKerjaOptions,
        ]);
    }
}
