<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;

class DetailKaryawan extends Component
{
    public $user;
    public $namaJabatan;
    public $tanggal_lahir;
    public $tanggal_tetap;
    public $tgl_penyesuaian;
    public $pensiun;
    public $deskripsiPendidikan;

    public function mount($user)
    {
        // Mendapatkan data user
        $this->user = $user;

        // Mendapatkan nama jabatan berdasarkan jabatan_id
        $this->namaJabatan = $user->kategorijabatan->nama ?? '-';

        // Mendapatkan deskripsi pendidikan berdasarkan pendidikan_id
        $this->deskripsiPendidikan = $user->pendidikanUser->deskripsi ?? '-';
    }

    public function render()
    {
        return view('livewire.detail-karyawan');
    }
}
