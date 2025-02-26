<?php

namespace App\Livewire;

use App\Models\KategoriJabatan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MasterJabatan;
use App\Models\MasterPendidikan;

class EditProfile extends Component
{
    public $name, $jabatan_id, $tempat, $tanggal_lahir, $tanggal_tetap, $pendidikan_id, $pendidikan_penyesuaian, $tgl_penyesuaian, $pensiun;
    public $jabatans, $pendidikans;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->jabatan_id = $user->jabatan_id;
        $this->tempat = $user->tempat;
        $this->tanggal_lahir = $user->tanggal_lahir;
        $this->tanggal_tetap = $user->tanggal_tetap;
        $this->pendidikan_id = $user->pendidikan;
        $this->pendidikan_penyesuaian = $user->pendidikan_penyesuaian;
        $this->tgl_penyesuaian = $user->tgl_penyesuaian;
        $this->pensiun = $user->pensiun;


        $this->jabatans = KategoriJabatan::all();
        $this->pendidikans = MasterPendidikan::all();
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'jabatan_id' => 'nullable|exists:kategori_jabatans,id',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_tetap' => 'nullable|date',
            'pendidikan_id' => 'nullable|string|max:255',
            'pendidikan_penyesuaian' => 'nullable|string|max:255',
            'tgl_penyesuaian' => 'nullable|date',
            'pensiun' => 'nullable|date',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'jabatan_id' => $this->jabatan_id,
            'tempat' => $this->tempat,
            'tanggal_lahir' => $this->tanggal_lahir,
            'tanggal_tetap' => $this->tanggal_tetap,
            'pendidikan' => $this->pendidikan_id,
            'pendidikan_penyesuaian' => $this->pendidikan_penyesuaian,
            'tgl_penyesuaian' => $this->tgl_penyesuaian,
            'pensiun' => $this->pensiun,
        ]);

        return redirect()->route('userprofile.index')->with('success', 'Profile berhasil diupdate.');
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
