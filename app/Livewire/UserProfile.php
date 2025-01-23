<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public $user;

    public function mount()
    {
        // Ambil data user yang sedang login
        $this->user = User::with('jabatan')
            ->select('name', 'tempat', 'tanggal_lahir', 'tgl_penyesuaian', 'pensiun', 'jabatan_id', 'no_ktp', 'no_hp', 'email', 'password','id')
            ->where('id', Auth::id()) // Filter berdasarkan ID user yang login
            ->first(); // Ambil satu record karena hanya ada satu user login
        
        //    dd($this->user);
    }

    public function render()
    {
        return view('livewire.user-profile', [
            'user' => $this->user, // Mengirimkan data user ke view
        ]);
    }
}
