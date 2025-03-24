<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditUsername extends Component
{
    public $username;
    public $old_username;

    public function mount()
    {
        // $this->username = Auth::user()->username;
        $this->old_username = Auth::user()->username;
    }
    public function updateUsername()
    {
        $this->validate([
            'username' => 'required|unique:users,username,',
        ]);

        $user = Auth::user();
        $user->username = $this->username;
        $user->save();

        return redirect()->route('userprofile.index')->with('success', 'Username berhasil diupdate.');
    }
    public function render()
    {
        return view('livewire.edit-username');
    }
}
