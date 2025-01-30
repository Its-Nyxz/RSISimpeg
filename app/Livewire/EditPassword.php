<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditPassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function updatePassword()
    {
        // Validasi input
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed', // Password baru harus memiliki minimal 8 karakter dan cocok dengan konfirmasi
        ]);

        // Cek apakah password lama sesuai
        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            // Jika password lama tidak sesuai
            session()->flash('error', 'Password lama tidak sesuai.');
            return;
        }

        // Update password baru
        $user->password = Hash::make($this->new_password);
        $user->save();

        session()->flash('success', 'Password berhasil diupdate.');
        return redirect()->route('userprofile.index');
    }

    public function render()
    {
        return view('livewire.edit-password');
    }
}
