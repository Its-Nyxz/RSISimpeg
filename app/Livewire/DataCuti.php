<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Livewire\Component;
use App\Models\CutiKaryawan;

use Livewire\WithPagination;
use App\Models\JadwalAbsensi;
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
        $user = auth()->user();
        // $roles = ['Super Admin', 'Kepala Seksi Kepegawaian', 'Staf Kepegawaian', 'Kepegawaian', 'Administrator'];

        // // Jika pengguna memiliki salah satu role di atas, tampilkan semua data
        // if (in_array($user->hasAnyRole($roles), $roles)) {
        //     return CutiKaryawan::with('user')->orderByDesc('id')->paginate(10);
        // }

        // Jika bukan, filter berdasarkan unit
        return CutiKaryawan::with('user')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('unit_id', $user->unit_id);
            })->orderByDesc('id')->paginate(10);
    }


    public function approveCuti($cutiId, $userId)
    {
        $cuti = CutiKaryawan::find($cutiId);
        if ($cuti) {
            $cuti->update(['status_cuti_id' => 1]);

            $shift = Shift::firstOrCreate(
                ['nama_shift' => 'C'],
                [
                    'unit_id' => auth()->user()->unit_id, // Unit dari user yang approve
                    'jam_masuk' => null,
                    'jam_keluar' => null,
                    'keterangan' => 'Cuti'
                ]
            );
            $start = Carbon::parse($cuti->tanggal_mulai);
            $end = Carbon::parse($cuti->tanggal_selesai);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                JadwalAbsensi::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'tanggal_jadwal' => $date->toDateString()
                    ],
                    [
                        'shift_id' => $shift->id,
                    ]
                );
            }

            $nextUser = User::where('id', $userId)->first();
            $message = 'Pengajuan Cuti anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' .  $cuti->tanggal_selesai .
                '</span> ' . '  dengan keterangan "' . $cuti->keterangan . '"  telah <span class="text-green-600 font-bold">Disetujui</span> oleh ' . auth()->user()->name;

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
                '</span>  dengan keterangan "' . $cuti->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name;

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
