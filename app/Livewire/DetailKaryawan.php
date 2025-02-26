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

        $this->tanggal_lahir = $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->locale('id')->translatedFormat('d F Y') : '-';
        $this->tanggal_tetap = $user->tanggal_tetap ? Carbon::parse($user->tanggal_tetap)->locale('id')->translatedFormat('d F Y') : '-';
        $this->tgl_penyesuaian = $user->tgl_penyesuaian ? Carbon::parse($user->tgl_penyesuaian)->locale('id')->translatedFormat('d F Y') : '-';
        $this->pensiun = $user->pensiun ? Carbon::parse($user->pensiun)->locale('id')->translatedFormat('d F Y') : '-';
    }

    public function render()
    {
        return view('livewire.detail-karyawan');
    }
}
