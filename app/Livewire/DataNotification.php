<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DataNotification extends Component
{
    use WithPagination;
    
    public $notificationId;
    public $filterStatus = 'all'; // Default: Semua Pesan

    public function mount($notificationId = null) 
    {
        $this->notificationId = $notificationId;
    }

    public function markAsRead($notificationId, $url)
    {
        // Temukan notifikasi dan tandai sudah dibaca
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect ke URL yang ditentukan
            return redirect()->to($url);
        }
    }

    public function render()
    {
        // Query notifikasi berdasarkan filter
        $user = Auth::user();
        $query = $user->notifications();
        
        // Filter berdasarkan status baca
        if ($this->filterStatus === 'unread') {
            $query->whereNull('read_at'); // Belum dibaca
        } elseif ($this->filterStatus === 'read') {
            $query->whereNotNull('read_at'); // Sudah dibaca
        }
        // Jika 'all' atau nilai lainnya, tidak ada filter tambahan
        
        // Paginasi dengan 10 data per halaman
        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('livewire.data-notification', [
            'notifications' => $notifications
        ]);
    }
}