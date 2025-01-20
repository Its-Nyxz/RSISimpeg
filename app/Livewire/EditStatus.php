<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StatusAbsen;

class EditStatus extends Component
{
    public $status_id;
    public $nama;
    public $keterangan;

    public function mount($statusId){
        $status = StatusAbsen::findOrFail($statusId);
        $this->status_id = $status->id;
        $this->nama = $status->nama;
        $this->keterangan = $status->keterangan;
    }

    public function updateStatus(){
        $this->validate([
            'nama' => 'required',
            'keterangan' => 'required',
        ]);

        $status = StatusAbsen::findOrFail($this->status_id);
        $status->update([
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('success', 'Status berhasil diperbarui!');
        return redirect()->route('absensi.index');
    }
    public function render()
    {
        return view('livewire.edit-status');
    }
}
