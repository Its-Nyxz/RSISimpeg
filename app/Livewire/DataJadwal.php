<?php

namespace App\Livewire;

use App\Models\JadwalAbsensi;
use Livewire\Component;

class DataJadwal extends Component
{
    public $search = ''; 
    public $bulan, $tahun;
    public $jadwals = [];
    public $tanggalJadwal = [];
    public $filteredShifts = [];

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

        $this->tanggalJadwal = collect(range(1, cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun)))
            ->map(fn ($day) => sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day))
            ->toArray();

        $jadwalData = JadwalAbsensi::with(['user', 'shift'])
            ->whereYear('tanggal_jadwal', $this->tahun)
            ->whereMonth('tanggal_jadwal', $this->bulan)
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->get();

        $this->jadwals = $jadwalData->groupBy('user_id')->map(fn($items) => $items->values());

        $this->filteredShifts = [];
        foreach ($jadwalData as $jadwal) {
            $this->filteredShifts[$jadwal->user_id][$jadwal->tanggal_jadwal] = optional($jadwal->shift)->nama_shift ?? '-';
        }
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