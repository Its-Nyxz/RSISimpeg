<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public $userprofile;
    public function mount()
    {
        // Ambil data user yang sedang login
        $this->userprofile = User::with('kategorijabatan', 'pendidikanUser')
            ->where('id', Auth::id()) // Filter berdasarkan ID user yang login
            ->first(); 
    }
    public function render()
    {
        return view('livewire.user-profile', [
            'userprofile' => $this->userprofile,
        ]);
    }
}
