<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\GajiBruto;

class DetailKeuangan extends Component
{
    public $user;
    public $potonganData;

    public function mount(User $user)
    {
        $this->user = $user;
        // Fetch the gaji_bruto record based on user_id
        $gajiBruto = GajiBruto::where('user_id', $user->id)->first();

        if ($gajiBruto) {
            // Fetch the potongan data using the bruto_id from gaji_bruto
            $this->potonganData = $gajiBruto->potongan; // This will fetch the potongan associated with gaji_bruto
        } else {
            // If no gaji_bruto found for this user, set potonganData to null
            $this->potonganData = null;
        }
    }

    public function render()
    {
        return view('livewire.detail-keuangan');
    }
}

