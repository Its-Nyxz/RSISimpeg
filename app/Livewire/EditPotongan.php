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
    public $is_wajib = false;
    public $nominal = 0; // Tambahan properti

    public function mount($potonganId)
    {
        $potongan = MasterPotongan::findOrFail($potonganId);

        $this->potongan_id = $potongan->id;
        $this->nama = $potongan->nama;
        $this->is_wajib = $potongan->is_wajib;
        $this->nominal = $potongan->nominal; // Ambil nilai nominal
    }

    public function updatePotongan()
    {
        $this->validate([
            'nama' => 'required|string|unique:master_potongan,nama,' . $this->potongan_id,
            'is_wajib' => 'boolean',
            'nominal' => 'required|integer|min:0',
        ]);

        MasterPotongan::where('id', $this->potongan_id)->update([
            'nama' => strtolower($this->nama),
            'slug' => Str::slug($this->nama),
            'nominal' => $this->nominal, // Update nilai nominal juga
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
