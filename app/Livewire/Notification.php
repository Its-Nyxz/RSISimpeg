<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notification extends Component
{
    public function markAsRead($notificationId, $href)
    {
        $notification = Auth::user()->notifications->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        redirect()->to($href);
    }
    public function render()
    {
        return view('livewire.notification');
    }
}
