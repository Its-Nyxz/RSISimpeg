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
    public bool $is_wajib = false;
    public $nominal = 0; // Tambahan properti
    public $no_urut = null;
    public $is_active = 1;

    public function mount($potonganId)
    {
        $potongan = MasterPotongan::findOrFail($potonganId);

        $this->potongan_id = $potongan->id;
        $this->nama = $potongan->nama;
        $this->is_wajib = $potongan->is_wajib;
        $this->nominal = $potongan->nominal; // Ambil nilai nominal
        $this->no_urut = $potongan->no_urut;
        $this->is_active = $potongan->is_active;
    }

    protected function rules()
    {
        return [
            'nama' => 'required|string|unique:master_potongan,nama,' . $this->potongan_id,
            'is_wajib' => 'boolean',
            'nominal' => 'required|integer|min:0',
            'no_urut' => 'nullable|integer|min:1|unique:master_potongan,no_urut',
            'is_active' => 'nullable|in:0,1',
        ];
    }

    protected $messages = [
        'no_urut.unique' => 'No urut sudah digunakan, silakan pilih nomor lain.',
        'nama.unique' => 'Nama potongan sudah ada.',
    ];

    public function updatePotongan()
    {
        $this->validate();

        MasterPotongan::where('id', $this->potongan_id)->update([
            'nama' => strtolower($this->nama),
            'slug' => Str::slug($this->nama),
            'nominal' => $this->nominal,
            'is_wajib' => (bool) $this->is_wajib,
            'no_urut' => $this->no_urut !== '' ? $this->no_urut : null,
            'is_active' => $this->is_active !== '' ? (int) $this->is_active : null,
        ]);

        session()->flash('success', 'Potongan berhasil diperbarui!');
        return redirect()->route('potongan.index');
    }


    public function render()
    {
        return view('livewire.edit-potongan');
    }
}
