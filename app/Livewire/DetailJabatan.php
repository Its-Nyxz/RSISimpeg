<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterJabatan;

class DetailJabatan extends Component
{
    public $id;
    public $jabatan;

    public function mount($id)
    {
        $this->id = $id;
        $this->jabatan = MasterJabatan::find($id);

        // Jika jabatan tidak ditemukan
        if (!$this->jabatan) {
            abort(404, 'Jabatan tidak ditemukan');
        }
    }

    public function render()
    {
        return view('livewire.detail-jabatan');
    }
}


