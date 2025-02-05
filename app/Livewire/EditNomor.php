<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MasterJabatan;

class EditNomor extends Component
{
    public $no_hp;  // Property for the new WhatsApp number
    public $old_no_hp; // Property for the old WhatsApp number

    public function mount()
    {
        $user = Auth::user();
        $this->old_no_hp = $user->no_hp; // Set old number from the authenticated user
        $this->no_hp = null;     // Pre-fill new number input with the current value
    }

    public function updateNomor()
    {
        // Validate the input
        $this->validate([
            'no_hp' => 'required|numeric|digits_between:10,15',
        ]);

        // Update the user's WhatsApp number
        $user = Auth::user();
        $user->no_hp = $this->no_hp;
        $user->save();

        // Redirect with a success message
        session()->flash('success', 'Nomor WhatsApp berhasil diupdate.');
        return redirect()->route('userprofile.index');
    }

    public function render()
    {
        return view('livewire.edit-nomor');
    }
}
