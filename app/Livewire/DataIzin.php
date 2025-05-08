<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Shift;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\StatusAbsen;
use App\Models\IzinKaryawan;
use Livewire\WithPagination;
use App\Models\JadwalAbsensi;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataIzin extends Component
{
    use WithPagination;
    public $isKepegawaian = false;

    public function mount()
    {
        $this->loadData();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $user = auth()->user();

        $this->isKepegawaian = $user->unit_id == $unitKepegawaianId;
    }

    public function loadData()
    {
        $user = auth()->user();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');

        if ($user->unit_id == $unitKepegawaianId) {
            // Kalau dari unit KEPEGAWAIAN:
            return IzinKaryawan::with('user')
                ->where(function ($query) use ($unitKepegawaianId) {
                    $query->where('status_izin_id', 4)
                        ->orWhere(function ($q) use ($unitKepegawaianId) {
                            $q->where('status_izin_id', 3)
                                ->whereHas('user', function ($subquery) use ($unitKepegawaianId) {
                                    $subquery->where('unit_id', $unitKepegawaianId);
                                });
                        });
                })
                ->orderByDesc('id')
                ->paginate(10);
        } else {
            // Selain KEPEGAWAIAN: hanya tampilkan berdasarkan unit_id user
            return IzinKaryawan::with('user')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('unit_id', $user->unit_id);
                })
                ->orderByDesc('id')
                ->paginate(10);
        }
    }

    public function approveIzin($izinId, $userId)
    {
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();
        $izin = IzinKaryawan::find($izinId);
        $user = auth()->user();
        $targetUser = User::findOrFail($userId);
        if ($izin) {
            if ($user->unit_id == $unitKepegawaianId) {
                $izin->update(['status_izin_id' => 1]);
                $shift = Shift::firstOrCreate(
                    ['nama_shift' => 'I'],
                    [
                        'unit_id' =>  $targetUser->unit_id, // Unit dari user yang minta
                        'jam_masuk' => null,
                        'jam_keluar' => null,
                        'keterangan' => 'Izin'
                    ]
                );
                $start = Carbon::parse($izin->tanggal_mulai);
                $end = Carbon::parse($izin->tanggal_selesai);

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

                foreach (Carbon::parse($izin->tanggal_mulai)->toPeriod(Carbon::parse($izin->tanggal_selesai)) as $date) {
                    $jadwal = JadwalAbsensi::with('shift')
                        ->where('user_id', $userId)
                        ->whereDate('tanggal_jadwal', $date->toDateString())
                        ->first();

                    if ($jadwal && $jadwal->shift) {
                        $statusIzinId = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
                        $jenisIzin = $izin->jenisIzin->nama_izin ?? 'Izin';

                        Absen::updateOrCreate(
                            [
                                'user_id' => $userId,
                                'jadwal_id' => $jadwal->id,
                            ],
                            [
                                'status_absen_id' => $statusIzinId,
                                'present' => 1,
                                'absent' => 0,
                                'late' => 0,
                                'time_in' => null,
                                'time_out' => null,
                                'keterangan' => 'Izin disetujui',
                                'deskripsi_in' => $jenisIzin,
                                'deskripsi_out' => $jenisIzin,
                                'is_dinas' => false,
                                'is_lembur' => false,
                                'approved_lembur' => false,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }

                $nextUser = User::where('id', $userId)->first();
                $message = 'Pengajuan Izin anda (' . $nextUser->name .
                    ') mulai <span class="font-bold">' . $izin->tanggal_mulai . ' sampai ' .  $izin->tanggal_selesai .
                    '</span> ' . '  dengan keterangan "' . $izin->keterangan . '"  telah <span class="text-green-600 font-bold">Disetujui Final</span> oleh ' . $user->name;


                $url = "/pengajuan/ijin";
                if ($nextUser) {
                    Notification::send($nextUser, new UserNotification($message, $url));
                }
                return redirect()->route('approvalizin.index')->with('success', 'Pengajuan Izin disetujui Final.');
            } else {
                // Kalau unit selain kepegawaian, hanya setujui kepala unit
                $izin->update(['status_izin_id' => 4]);

                $nextUser = User::where('id', $userId)->first();
                $message = 'Pengajuan Ijim anda (' . $nextUser->name .
                    ') telah <span class="text-green-600 font-bold">Disetujui Kepala Unit</span> oleh ' . $user->name;
                $messagekepegawaian = 'Pengajuan Ijin atas nama (' . $nextUser->name .
                    ') telah <span class="text-green-600 font-bold">Disetujui Kepala Unit</span> oleh ' . $user->name . ', silahkan melanjutkan persetujuan ';

                $url = "/pengajuan/ijin";
                $urlkepegawaian = "/approvalizin";
                if ($nextUser) {
                    Notification::send($nextUser, new UserNotification($message, $url));
                    Notification::send($kepegawaianUsers, new UserNotification($messagekepegawaian, $urlkepegawaian));
                }

                return redirect()->route('approvalizin.index')->with('success', 'Ijin disetujui Kepala Unit!');
                $this->resetPage();
            }
        }
    }

    public function rejectIzin($izinId, $userId, $reason = null)
    {
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();
        $izin = IzinKaryawan::find($izinId);
        if ($izin) {
            $izin->update(['status_izin_id' => 2]);
            $nextUser = User::where('id', $userId)->first();
            $message = 'Pengajuan Izin anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $izin->tanggal_mulai . ' sampai ' .  $izin->tanggal_selesai .
                '</span>  dengan keterangan "' . $izin->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name .
                '. Alasan: "' . $reason . '"';
            $messageKepegawaian = 'Pengajuan Izin atas nama (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $izin->tanggal_mulai . ' sampai ' .  $izin->tanggal_selesai .
                '</span>  dengan keterangan "' . $izin->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name .
                '. Alasan: "' . $reason . '"';

            $url = "/pengajuan/ijin";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
                Notification::send($kepegawaianUsers, new UserNotification($messageKepegawaian, $url));
            }
            return redirect()->route('approvalizin.index')->with('success', 'Izin berhasil ditolak.');
        }
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-izin', [
            'userIzin' => $users,
            'isKepegawaian' => $this->isKepegawaian,
        ]);
    }
}
