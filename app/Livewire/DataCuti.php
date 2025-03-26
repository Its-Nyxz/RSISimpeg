<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\CutiKaryawan;
use Livewire\WithPagination;

use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataCuti extends Component
{
    use WithPagination;
    // public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        return CutiKaryawan::with('user')->orderByDesc('id')->paginate(15);
    }


    public function approveCuti($cutiId, $userId)
    {
        $cuti = CutiKaryawan::find($cutiId);
        if ($cuti) {
            $cuti->update(['status_cuti_id' => 1]);

            $nextUser = User::where('id', $userId)->first();
            $message = 'Pengajuan Cuti anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' .  $cuti->tanggal_selesai .
                '</span> ' . '  karena "' . $cuti->keterangan . '"  telah <span class="text-green-600 font-bold">Disetujui</span> oleh ' . auth()->user()->name;

            $url = "/pengajuan/cuti";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
            }
            return redirect()->route('approvalcuti.index')->with('success', 'Pengajuan Cuti Disetujui!');
            $this->resetPage();
        }
    }
    public function rejectCuti($cutiId, $userId)
    {
        $cuti = CutiKaryawan::find($cutiId);

        if ($cuti) {
            $cuti->update(['status_cuti_id' => 2]);

            // Ambil user yang mengajukan cuti
            $nextUser = User::find($userId);

            // Pesan notifikasi untuk user
            $message = 'Pengajuan Cuti anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' .  $cuti->tanggal_selesai .
                '</span>  karena "' . $cuti->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name;

            $url = "/pengajuan/cuti";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
            }

            return redirect()->route('approvalcuti.index')->with('success', 'Pengajuan cuti ditolak!');
            $this->resetPage();
        }
    }


    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-cuti', [
            'users' => $users,
        ]);
    }
}
