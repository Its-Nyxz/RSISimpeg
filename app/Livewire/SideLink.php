<?php

namespace App\Livewire;

use Livewire\Component;

class SideLink extends Component
{
    public $title;
    public $href;
    public $icon;
    public $child = [];
    public function render()
    {
        return view('livewire.side-link');
    }
}
