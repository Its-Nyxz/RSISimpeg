<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class DetailKaryawan extends Component
{
    public $user;
    public $namaJabatan;
    public $deskripsiPendidikan;

    public function mount($user)
    {
        // Mendapatkan data user
        $this->user = $user;

        // Mendapatkan nama jabatan berdasarkan jabatan_id
        $this->namaJabatan = $user->jabatan->nama ?? '-';

        // Mendapatkan deskripsi pendidikan berdasarkan pendidikan_id
        $this->deskripsiPendidikan = $user->pendidikan->deskripsi ?? '-';
    }

    public function render()
    {
        return view('livewire.detail-karyawan');
    }
}
