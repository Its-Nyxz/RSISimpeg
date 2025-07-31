<?php

namespace App\Livewire;

use App\Models\CutiKaryawan;
use App\Models\TukarJadwal;
use Livewire\Component;

class DataPengajuan extends Component
{
    public $tipe; // Menerima tipe dari blade
    public $dataPengajuan;

    // Lifecycle hook untuk menerima parameter dari blade
    public function mount($tipe)
    {
        $this->tipe = $tipe;
        $this->loadData();
    }

    public function loadData()
    {
        $userId = auth()->id();

        switch ($this->tipe) {
            case 'cuti':
                $this->dataPengajuan = CutiKaryawan::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'ijin':
                // $this->dataPengajuan = IjinKaryawan::where('user_id', $userId)
                //     ->orderBy('created_at', 'desc')
                //     ->get();
                break;

            case 'tukar_jadwal':
                $this->dataPengajuan = TukarJadwal::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            default:
                abort(404); // Tipe tidak valid, kembalikan 404
        }
    }

    public function render()
    {
        return view('livewire.data-pengajuan');
    }
}
