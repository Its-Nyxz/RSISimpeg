<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterPendidikan;
use App\Models\MasterPenyesuaian;

class AddPenyesuaian extends Component
{
    public $id;
    public $tipe; // id penyesuaian untuk edit
    public $penyesuaians;
    public $pendidikans;
    public $pendidikan_awal;
    public $pendidikan_penyesuaian;
    public $masa_kerja;

    public function mount()
    {
        $this->pendidikans = MasterPendidikan::all();
        $this->penyesuaians = MasterPenyesuaian::with(['pendidikanAwal', 'pendidikanPenyesuaian'])->get();

        if ($this->tipe) {
            $penyesuaian = MasterPenyesuaian::find($this->tipe);

            if ($penyesuaian) {
                $this->pendidikan_awal = $penyesuaian->pendidikan_awal;
                $this->pendidikan_penyesuaian = $penyesuaian->pendidikan_penyesuaian;
                $this->masa_kerja = $penyesuaian->masa_kerja;
            }
        }
    }

    public function savePenyesuaian()
    {
        $this->validate([
            'pendidikan_awal' => 'required|exists:master_pendidikan,id',
            'pendidikan_penyesuaian' => 'required|exists:master_pendidikan,id|different:pendidikan_awal',
            'masa_kerja' => 'required|string|max:255',
        ]);

        $penyesuaian = MasterPenyesuaian::updateOrCreate(
            ['id' => $this->tipe ?? 0],
            [
                'pendidikan_awal' => $this->pendidikan_awal,
                'pendidikan_penyesuaian' => $this->pendidikan_penyesuaian,
                'masa_kerja' => $this->masa_kerja,
            ]
        );

        $message = $penyesuaian->wasRecentlyCreated
            ? 'Berhasil Menambahkan Data Penyesuaian.'
            : 'Berhasil Memperbarui Data Penyesuaian.';

        return redirect()->route('penyesuaian.index')->with('success', $message);
    }


    public function render()
    {
        return view('livewire.add-penyesuaian');
    }
}
