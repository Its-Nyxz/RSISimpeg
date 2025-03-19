<?php

namespace App\Livewire;

use App\Models\JadwalAbsensi;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\JadwalImport;
use Maatwebsite\Excel\Facades\Excel;

class DataJadwal extends Component
{
    use WithFileUploads;
    public $search = '';
    public $bulan, $tahun;
    public $jadwals = [];
    public $tanggalJadwal = [];
    public $filteredShifts = [];
    public $file;

    public function mount()
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadData();
    }

    public function loadData()
    {
        $this->bulan = (int) $this->bulan;
        $this->tahun = (int) $this->tahun;

        $unitId = auth()->user()->unit_id;

        $this->tanggalJadwal = collect(range(1, cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun)))
            ->map(fn($day) => sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day))
            ->toArray();

        $jadwalData = JadwalAbsensi::with(['user', 'shift'])
            ->whereYear('tanggal_jadwal', $this->tahun)
            ->whereMonth('tanggal_jadwal', $this->bulan)
            ->whereHas('user', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->get();

        $this->jadwals = $jadwalData->groupBy('user_id')->map(fn($items) => $items->values());

        $this->filteredShifts = [];
        foreach ($jadwalData as $jadwal) {
            $this->filteredShifts[$jadwal->user_id][$jadwal->tanggal_jadwal] = optional($jadwal->shift)->nama_shift ?? '-';
        }
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048', // Validasi file
        ]);

        Excel::import(new JadwalImport, $this->file);

        session()->flash('success', 'Jadwal berhasil diimport!');
        $this->loadData(); // Refresh data setelah import
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bulan', 'tahun'])) {
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.data-jadwal');
    }
}
