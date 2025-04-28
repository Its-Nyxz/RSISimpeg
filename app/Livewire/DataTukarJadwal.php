<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\TukarJadwal;
use Livewire\WithPagination;
use App\Models\JadwalAbsensi;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataTukarJadwal extends Component
{
    use WithPagination;

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
        //     return TukarJadwal::with('user')->orderByDesc('id')->paginate(10);
        // }

        // Jika bukan, filter berdasarkan unit
        return TukarJadwal::with('user')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('unit_id', $user->unit_id);
            })->orderByDesc('id')->paginate(10);
    }


    public function approveTukar($tukarId, $userId)
    {
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();
        $tukar = TukarJadwal::find($tukarId);
        if ($tukar) {
            $tukar->update(['is_approved' => 1]);

            $shift = Shift::find($tukar->shift_id);

            JadwalAbsensi::updateOrCreate(
                [
                    'user_id' => $userId,
                    'tanggal_jadwal' => $tukar->tanggal,
                ],
                [
                    'shift_id' => $shift->id,
                ]
            );

            $nextUser = User::where('id', $userId)->first();
            $message = 'Pengajuan Tukar Jadwal anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $tukar->tanggal .
                '</span> ' . '  dengan keterangan "' . $tukar->keterangan . '"  telah <span class="text-green-600 font-bold">Disetujui</span> oleh ' . auth()->user()->name;
            $messageKepegawaian = 'Pengajuan Tukar Jadwal atas nama (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $tukar->tanggal .
                '</span> ' . '  dengan keterangan "' . $tukar->keterangan . '"  telah <span class="text-green-600 font-bold">Disetujui</span> oleh ' . auth()->user()->name;

            $url = "/pengajuan/tukar_jadwal";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
                Notification::send($kepegawaianUsers, new UserNotification($messageKepegawaian, $url));
            }
            return redirect()->route('approvaltukar.index')->with('success', 'Pengajuan Tukar Jadwal Disetujui!');
            $this->resetPage();
        }
    }
    public function rejectTukar($tukarId, $userId, $reason = null)
    {
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();
        $tukar = TukarJadwal::find($tukarId);

        if ($tukar) {
            $tukar->update(['is_approved' => 0]);

            // Ambil user yang mengajukan tukar
            $nextUser = User::find($userId);

            // Pesan notifikasi untuk user
            $message = 'Pengajuan Tukar Jadwal anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $tukar->tanggal .
                '</span>  dengan keterangan "' . $tukar->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name .
                '. Alasan: "' . $reason . '"';
            $messageKepegawaian = 'Pengajuan Tukar Jadwal atas nama (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $tukar->tanggal .
                '</span>  dengan keterangan "' . $tukar->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name .
                '. Alasan: "' . $reason . '"';

            $url = "/pengajuan/tukar_jadwal";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
                Notification::send($kepegawaianUsers, new UserNotification($messageKepegawaian, $url));
            }

            return redirect()->route('approvaltukar.index')->with('success', 'Pengajuan Tukar Jadwal ditolak!');
            $this->resetPage();
        }
    }


    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-tukar-jadwal', [
            'users' => $users,
        ]);
    }
}
