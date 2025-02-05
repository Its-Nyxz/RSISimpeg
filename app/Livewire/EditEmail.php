<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EditEmail extends Component
{
    public $email;
    public $old_email;

    public function mount()
    {
        $this->email = Auth::user()->email;
        $this->old_email = $this->email;
    }
    public function updateEmail()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email,',
        ]);

        $user = Auth::user();
        $user->email = $this->email;
        $user->save();

        return redirect()->route('userprofile.index')->with('success', 'Email berhasil diupdate.');
    }

    public function render()
    {
        return view('livewire.edit-email', [
            'old_email' => $this->old_email,
        ]);
    }
}
