<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterTrans;

class EditTrans extends Component
{
    public $trans_id;
    public $nama;
    public $nom_makan;
    public $nom_transport;

    public function mount($transId){
        $trans = MasterTrans::findOrFail($transId);
        $this->trans_id = $trans->id;
        $this->nama = $trans->nama;
        $this->nom_makan = $trans->nom_makan;
        $this->nom_transport = $trans->nom_transport;
    }

    public function updateTrans(){
        $this->validate([
            'nama' => 'required',
            'nom_makan' => 'required',
            'nom_transport' => 'required',
        ]);

        $trans = MasterTrans::findOrFail($this->trans_id);
        $trans->update([
            'nama' => $this->nama,
            'nom_makan' => $this->nom_makan,
            'nom_transport' => $this->nom_transport,
        ]);

        session()->flash('success', 'Trans berhasil diperbarui!');
        return redirect()->route('trans.index');
    }
    public function render()
    {
        return view('livewire.edit-trans');
    }
}
