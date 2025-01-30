<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MasterJabatan;

class EditProfile extends Component
{
    public $name, $jabatan_id, $tempat, $tanggal_lahir, $tanggal_tetap, $pendidikan_awal, $pendidikan_penyesuaian, $tgl_penyesuaian;
    public $jabatans; // Untuk menyimpan data jabatan

    public function mount()
    {
        $user = Auth::user();

        // Set data awal dari user yang sedang login
        $this->name = $user->name;
        $this->jabatan_id = $user->jabatan_id;
        $this->tempat = $user->tempat;
        $this->tanggal_lahir = $user->tanggal_lahir;
        $this->tanggal_tetap = $user->tanggal_tetap;
        $this->pendidikan_awal = $user->pendidikan_awal;
        $this->pendidikan_penyesuaian = $user->pendidikan_penyesuaian;
        $this->tgl_penyesuaian = $user->tgl_penyesuaian;

        // Ambil semua jabatan dari database
        $this->jabatans = MasterJabatan::all();
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'jabatan_id' => 'nullable|exists:master_jabatan,id',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_tetap' => 'nullable|date',
            'pendidikan_awal' => 'nullable|string|max:255',
            'pendidikan_penyesuaian' => 'nullable|string|max:255',
            'tgl_penyesuaian' => 'nullable|date',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'jabatan_id' => $this->jabatan_id,
            'tempat' => $this->tempat,
            'tanggal_lahir' => $this->tanggal_lahir,
            'tanggal_tetap' => $this->tanggal_tetap,
            'pendidikan_awal' => $this->pendidikan_awal,
            'pendidikan_penyesuaian' => $this->pendidikan_penyesuaian,
            'tgl_penyesuaian' => $this->tgl_penyesuaian,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
