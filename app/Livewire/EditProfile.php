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
    public $user_id, $name, $nip, $no_ktp, $no_hp, $no_rek, $pendidikan, $institusi, $jk, $alamat, $tempat, $tanggal_lahir;
    public $jabatans, $pendidikans;

    public function mount()
    {
        $user = Auth::user();
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->nip = $user->nip;
        $this->no_ktp = $user->no_ktp;
        $this->no_hp = $user->no_hp;
        $this->no_rek = $user->no_rek;
        $this->pendidikan = $user->pendidikan;
        $this->institusi = $user->institusi;
        $this->jk = $user->jk;
        $this->alamat = $user->alamat;
        $this->tempat = $user->tempat;
        $this->tanggal_lahir = $user->tanggal_lahir;


        $this->jabatans = KategoriJabatan::all();
        $this->pendidikans = MasterPendidikan::all();
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'nullable|string|max:255|unique:users,name,' . $this->user_id,
            'nip' => 'nullable|max:50|unique:users,nip,' . $this->user_id,
            'no_ktp' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:15',
            'no_rek' => 'nullable',
            'pendidikan' => 'nullable|exists:master_pendidikan,id',
            'institusi' => 'nullable|string|max:255',
            'jk' => 'nullable',
            'alamat' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name ?? null,
            'nip' => $this->nip ?? null,
            'email' => $this->email ?? null,
            'no_ktp' => $this->no_ktp ?? null,
            'no_hp' => $this->no_hp ?? null,
            'no_rek' => $this->no_rek ?? null,
            'pendidikan' => $this->pendidikan ?? null,
            'institusi' => $this->institusi ?? null,
            'jk' => $this->jk ?? null,
            'alamat' => $this->alamat ?? null,
            'tempat' => $this->tempat ?? null,
            'tanggal_lahir' => $this->tanggal_lahir ?? null,
        ]);

        return redirect()->route('userprofile.index')->with('success', 'Profile berhasil diupdate.');
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}