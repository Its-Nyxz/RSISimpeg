<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\MasterFungsi;
use App\Models\MasterPotongan;

class EditPotongan extends Component
{
    public $potongan_id;
    public $nama;
    public $jenis = 'nominal';
    public $is_wajib = false;

    public function mount($potonganId)
    {
        $potongan = MasterPotongan::findOrFail($potonganId);

        $this->potongan_id = $potongan->id;
        $this->nama = $potongan->nama;
        $this->jenis = $potongan->jenis;
        $this->is_wajib = $potongan->is_wajib;
    }

    public function updatePotongan()
    {
        $this->validate([
            'nama' => 'required|string|unique:master_potongan,nama,' . $this->potongan_id,
            'jenis' => 'required|in:nominal,persentase',
            'is_wajib' => 'boolean',
        ]);

        MasterPotongan::where('id', $this->potongan_id)->update([
            'nama' => strtolower($this->nama),
            'slug' => Str::slug($this->nama),
            'jenis' => $this->jenis,
            'is_wajib' => $this->is_wajib,
        ]);

        session()->flash('success', 'Potongan berhasil diperbarui!');
        return redirect()->route('potongan.index');
    }

    public function render()
    {
        return view('livewire.edit-potongan');
    }
}
