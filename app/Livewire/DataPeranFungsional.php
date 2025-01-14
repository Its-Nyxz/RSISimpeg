<?php

namespace App\Livewire;

use App\Models\JadwalAbsensi;
use Livewire\Component;

class DataPeranFungsional extends Component
{
    public $data;

    public function render()
    {
        return view('livewire.data-peran-fungsional', [
            'data' => $this->data
        ]);
    }

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = JadwalAbsensi::with(['user', 'shift'])
            ->get()
            ->map(function ($jadwal) {
                return [
                    'nama' => $jadwal->user->name ?? 'Belum ada data',
                    'shift' => $jadwal->shift->id ?? 'Belum ada data',
                ];
            });
    }
}