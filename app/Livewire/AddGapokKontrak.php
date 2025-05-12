<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GapokKontrak;
use App\Models\KategoriJabatan;

class AddGapokKontrak extends Component
{
    public $id;
    public $tipe;
    public $kontraks;
    public $kontrak;
    public $min_masa_kerja;
    public $max_masa_kerja;
    public $nominal;
    public $jabatan;

    public $suggestions = [
        'jabatan' => [],
    ];

    public function fetchSuggestions($field, $value)
    {
        $this->suggestions[$field] = [];

        // if ($value) {
        if ($field === 'jabatan') {
            $categories = KategoriJabatan::where('nama', 'like', "%$value%")
                ->get()
                ->groupBy('tunjangan');

            foreach ($categories as $tunjangan => $katjabList) {
                $this->suggestions[$field][$tunjangan] = $katjabList->pluck('nama')->toArray();
            }
        }
    }

    public function selectSuggestion($field, $value)
    {
        if ($field === 'jabatan') {
            $this->jabatan = $value;
        }
        $this->suggestions[$field] = [];
    }


    public function hideSuggestions($field)
    {
        $this->suggestions[$field] = [];
    }


    public function mount()
    {
        $this->kontraks = GapokKontrak::all();

        if ($this->tipe) {
            $kontrak = GapokKontrak::find($this->tipe);

            if ($kontrak) {
                $this->jabatan = $kontrak->kategoriJabatan->nama ?? null;
                $this->min_masa_kerja = $kontrak->min_masa_kerja;
                $this->max_masa_kerja = $kontrak->max_masa_kerja;
                $this->nominal = $kontrak->nominal;
            }
        }
    }

    public function removeKontrak()
    {
        GapokKontrak::destroy($this->tipe);
        return redirect()->route('gapokkontrak.index')->with('success', 'Gaji Pokok Kontrak berhasil dihapus.');
    }

    public function saveKontrak()
    {
        $this->validate([
            'jabatan' => 'nullable',
            'min_masa_kerja' => 'required|numeric|min:0',
            'max_masa_kerja' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|min:0',
        ]);
        $kategoriJabatan = KategoriJabatan::where('nama', $this->jabatan)->first();

        $kontrak = GapokKontrak::updateOrCreate(
            ['id' => $this->tipe ?? 0],
            [
                'kategori_jabatan_id' => $kategoriJabatan->id ?? null,
                'min_masa_kerja' => $this->min_masa_kerja,
                'max_masa_kerja' => $this->max_masa_kerja,
                'nominal' => $this->nominal,
            ]
        );

        if ($kontrak->wasRecentlyCreated) {
            return redirect()->route('gapokkontrak.index')->with('success', 'Berhasil Menambah Gaji Pokok Kontrak.');
        } else {
            return redirect()->route('gapokkontrak.index')->with('success', 'Berhasil Mengubah Gaji Pokok Kontrak.');
        }
    }

    public function render()
    {
        return view('livewire.add-gapok-kontrak');
    }
}
