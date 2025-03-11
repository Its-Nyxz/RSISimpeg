<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Absen;

class AktivitasAbsensi extends Component
{
    public $search = '';
    public $items = [];
    public $bulan, $tahun;

    public function mount()
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadData();
    }

    public function loadData()
    {
        $this->items = Absen::whereYear('created_at', $this->tahun)
            ->whereMonth('created_at', $this->bulan)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($absen) {
                return [
                    'hari' => Carbon::parse($absen->created_at)->translatedFormat('l'),
                    'tanggal' => Carbon::parse($absen->created_at)->format('d F Y'),
                    'jam_kerja' => $absen->time_in ? Carbon::parse($absen->time_in)->format('H:i') . ' - ' . ($absen->time_out ? Carbon::parse($absen->time_out)->format('H:i') : 'Sekarang') : '-',
                    'rencana_kerja' => $absen->keterangan_mulai ?? '-',
                    'laporan_kerja' => $absen->keterangan_selesai ?? '-',
                    'feedback' => '-',
                ];
            });
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bulan', 'tahun'])) {
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.aktivitas-absensi', [
            'bulanOptions' => range(1, 12),
            'tahunOptions' => range(now()->year - 5, now()->year)
        ]);
    }
}
