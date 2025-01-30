<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OpsiAbsen;

class EditOpsi extends Component
{
    public $opsi_id;
    public $name;
    
    public function mount($opsiId){
        $opsi = OpsiAbsen::findOrFail($opsiId);
        $this->opsi_id = $opsi->id;
        $this->name = $opsi->name;
    }

    public function updateOpsi(){
        $this->validate([
            'name' => 'required',
        ]);

        $opsi = OpsiAbsen::findOrFail($this->opsi_id);
        $opsi->update([
            'name' => $this->name,
        ]);

        session()->flash('success', 'Opsi berhasil diperbarui!');
        return redirect()->route('opsi.index');
    }
    public function render()
    {
        return view('livewire.edit-opsi');
    }
}
