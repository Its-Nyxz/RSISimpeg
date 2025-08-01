<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\MasterJatahCuti;

class AddJatahCuti extends Component
{
    public $id;
    public $tipe;
    public $cutis;
    public $cuti;
    public $jumlah_cuti;
    public $tahun;

    public function mount()
    {
        $this->cutis = MasterJatahCuti::all();

        if ($this->tipe) {
            $cuti = MasterJatahCuti::find($this->tipe);

            if ($cuti) {
                $this->tahun = $cuti->tahun; // langsung tahun, bukan Carbon parse
                $this->jumlah_cuti = $cuti->jumlah_cuti;
            }
        }
    }

    public function removeCuti()
    {
        MasterJatahCuti::destroy($this->tipe);
        return redirect()->route('jatahcuti.index')->with('success', 'Jatah Cuti Tahunan berhasil dihapus.');
    }

    public function saveCuti()
    {
        $this->validate([
            'tahun' => 'required|numeric|min:2020',
            'jumlah_cuti' => 'required|numeric|min:0',
        ]);

        $cuti = MasterJatahCuti::updateOrCreate(
            ['id' => $this->tipe ?? 0],
            [
                'tahun' => $this->tahun,
                'jumlah_cuti' => $this->jumlah_cuti,
            ]
        );

        if ($cuti->wasRecentlyCreated) {
            return redirect()->route('jatahcuti.index')->with('success', 'Berhasil Menambah Jatah Cuti Tahunan.');
        } else {
            return redirect()->route('jatahcuti.index')->with('success', 'Berhasil Mengubah Jatah Cuti Tahunan.');
        }
    }

    public function render()
    {
        return view('livewire.add-jatah-cuti');
    }
}
