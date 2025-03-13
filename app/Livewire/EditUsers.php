<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditUsers extends Component
{
    public $user;
    public $user_id, $nip, $email, $password, $username, $name;
    public $jabatans, $pendidikans;
    public $current_password, $new_password, $new_password_confirmation;

    public function mount($user)
    {
        $this->user = $user;
        $this->user_id = $user->id;
        $this->nip = $user->nip;
        $this->email = $user->email;
        $this->password = $user->password;
        $this->username = $user->username;
        $this->name = $user->name;


        $this->jabatans = KategoriJabatan::all();
        $this->pendidikans = MasterPendidikan::all();
    }

    public function updateUser()
    {
        $this->validate([
            'nip' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'username' => 'required|string|max:255',
        ]);

        User::findorfail($this->user_id)->update([
            'nip' => $this->nip,
            'email' => $this->email,
            'username' => $this->username,
        ]);

        return redirect()->route('userprofile.index')->with('success', 'User berhasil diupdate.');
    }


    public function updatePassword()
    {
        // Validasi input
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
            'new_password_confirmation' => 'required|string',
        ], [
            'current_password.required' => 'Password Lama harus di isi',
            'new_password.required' => 'Password Baru harus di isi',
            'new_password_confirmation.required' => 'Ulangi Password Baru harus di isi',
            'new_password.confirmed' => 'Konfirmasi Password tidak cocok dengan Password Baru',
        ]);

        if (!Hash::check($this->current_password, $this->password)) {
            // Jika password lama tidak sesuai
            $this->addError('current_password', 'Password lama tidak sesuai.');
            return;
        }
        User::findOrFail($this->user_id)->update([
            'password' => Hash::make($this->new_password)
        ]);

        session()->flash('success', 'Password berhasil diupdate.');
        return redirect()->route('userprofile.index');
    }

    public function render()
    {
        return view('livewire.edit-users');
    }
}
