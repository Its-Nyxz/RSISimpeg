<?php
namespace App\Livewire;

use App\Models\Gapok;
use Livewire\Component;

class KenaikanGolongan extends Component
{
    public $data;

    public function render()
    {
        return view('livewire.kenaikan-golongan', [
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
                'pendidikan' => $historyPendidikan ? $historyPendidikan->keterangan : 'Belum ada data', // Cek jika ada data
                'gaji_sekarang' => 'Rp. ' . number_format($gapok->gapoks->nominal_gapok ?? 0, 0, ',', '.'),
            ];
        });
}

}

