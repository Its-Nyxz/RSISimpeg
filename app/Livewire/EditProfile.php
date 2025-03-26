<?php

namespace App\Livewire;

use App\Models\KategoriJabatan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterPendidikan;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    public $user_id, $name, $nip, $no_ktp, $no_hp, $no_rek, $kategori_pendidikan, $pendidikan, $institusi, $jenisKelamin, $alamat, $tempat_lahir, $tanggal_lahir;
    public $photo, $currentPhoto;
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
        $this->kategori_pendidikan = $user->kategori_pendidikan;
        $this->pendidikan = $user->pendidikan;
        $this->institusi = $user->institusi;
        $this->jenisKelamin = $user->jk;
        $this->alamat = $user->alamat;
        $this->tempat_lahir = $user->tempat;
        $this->tanggal_lahir = $user->tanggal_lahir;
        $this->currentPhoto = $user->photo;


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
            'kategori_pendidikan' => 'nullable|exists:master_pendidikan,id',
            'institusi' => 'nullable|string|max:255',
            'jenisKelamin' => 'nullable',
            'alamat' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        if ($this->photo) {
            $fileName = $this->photo->store('photos', 'public');
            $user->photo = basename($fileName);
        }

        $user->update([
            'name' => $this->name ?? null,
            'nip' => $this->nip ?? null,
            'no_ktp' => $this->no_ktp ?? null,
            'no_hp' => $this->no_hp ?? null,
            'no_rek' => $this->no_rek ?? null,
            'kategori_pendidikan' => $this->kategori_pendidikan ?? null,
            'pendidikan' => $this->pendidikan ?? null,
            'institusi' => $this->institusi ?? null,
            'jk' => $this->jenisKelamin ?? null,
            'alamat' => $this->alamat ?? null,
            'tempat' => $this->tempat_lahir ?? null,
            'tanggal_lahir' => $this->tanggal_lahir ?? null,
        ]);

        return redirect()->route('userprofile.index')->with('success', 'Profile berhasil diupdate.');
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
