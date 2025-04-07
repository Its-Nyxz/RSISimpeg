<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\IzinKaryawan;
use Livewire\WithPagination;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataIzin extends Component
{
    use WithPagination;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        $roles = ['Super Admin', 'Kepala Seksi Kepegawaian', 'Staf Kepegawaian', 'Kepegawaian', 'Administrator'];

        // Jika pengguna memiliki salah satu role di atas, tampilkan semua data
        if (in_array($user->hasAnyRole($roles), $roles)) {
            return IzinKaryawan::with('user')->orderByDesc('id')->paginate(10);
        }

        // Jika bukan, filter berdasarkan unit_id
        return IzinKaryawan::with('user')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('unit_id', $user->unit_id);
            })->orderByDesc('id')->paginate(10);
    }

    public function approveIzin($izinId, $userId)
    {
        $izin = IzinKaryawan::find($izinId);
        if ($izin) {
            $izin->update(['status_izin_id' => 1]);
            $nextUser = User::where('id', $userId)->first();
            $message = 'Pengajuan Izin anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $izin->tanggal_mulai . ' sampai ' .  $izin->tanggal_selesai .
                '</span> ' . '  dengan keterangan "' . $izin->keterangan . '"  telah <span class="text-green-600 font-bold">Disetujui</span> oleh ' . auth()->user()->name;

            $url = "/pengajuan/ijin";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
            }
            return redirect()->route('approvalizin.index')->with('success', 'Izin berhasil disetujui.');
        }
    }

    public function rejectIzin($izinId, $userId)
    {
        $izin = IzinKaryawan::find($izinId);
        if ($izin) {
            $izin->update(['status_izin_id' => 2]);
            $nextUser = User::where('id', $userId)->first();
            $message = 'Pengajuan Izin anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $izin->tanggal_mulai . ' sampai ' .  $izin->tanggal_selesai .
                '</span>  dengan keterangan "' . $izin->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name;

            $url = "/pengajuan/ijin";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
            }
            return redirect()->route('approvalizin.index')->with('success', 'Izin berhasil ditolak.');
        }
    }

    public function render()
    {
        return view('livewire.data-izin', [
            'userIzin' => $this->loadData(),
        ]);
    }
}
