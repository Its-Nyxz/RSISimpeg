<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;
use App\Models\User;

class EditUsers extends Component
{
    public $user;
    public $user_id, $nip, $email, $username, $name, $jabatan_id, $tempat, $tanggal_lahir, $tanggal_tetap, $pendidikan_id, $pendidikan_penyesuaian, $tgl_penyesuaian, $pensiun;
    public $jabatans, $pendidikans;

    public function mount($user)
    {
        $this->user = $user;
        $this->user_id = $user->id;
        $this->nip = $user->nip;
        $this->email = $user->email;
        $this->username = $user->username;
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
            'nip' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'jabatan_id' => 'nullable|exists:kategori_jabatans,id',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_tetap' => 'nullable|date',
            'pendidikan_id' => 'nullable|max:255',
            'pendidikan_penyesuaian' => 'nullable|string|max:255',
            'tgl_penyesuaian' => 'nullable|date',
            'pensiun' => 'nullable|date',
        ]);

        User::findorfail($this->user_id)->update([
            'nip' => $this->nip,
            'email' => $this->email,
            'username' => $this->username,
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

        return redirect()->route('userprofile.index')->with('success', 'User berhasil diupdate.');
    }

    public function render()
    {
        return view('livewire.edit-users');
    }
}
