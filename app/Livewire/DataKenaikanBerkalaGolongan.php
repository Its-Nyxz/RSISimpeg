<?php
namespace App\Livewire;

use App\Models\Gapok;
use Livewire\Component;

class DataKenaikanBerkalaGolongan extends Component
{
    public $data;

    public function render()
    {
        return view('livewire.data-kenaikan-berkala-golongan', [
            'data' => $this->data
        ]);
    }

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = Gapok::with(['user', 'user.historyPendidikan'])
        ->get()
        ->map(function ($gapok) {
            $historyPendidikan = $gapok->user->historyPendidikan->first();
            return [
                'nama' => $gapok->user->name ?? 'Belum ada data',
                'tmt' => $gapok->user->tmt ?? 'Belum ada data',
                'pendidikan' => $historyPendidikan ? $historyPendidikan->keterangan : 'Belum ada data', // Cek jika ada data
                'gaji_sekarang' => 'Rp. ' . number_format($gapok->gapoks->nominal_gapok ?? 0, 0, ',', '.'),
            ];
        });
    }
}