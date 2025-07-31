<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Shift;
use Livewire\Component;

use App\Models\UnitKerja;
use App\Models\StatusAbsen;
use App\Models\CutiKaryawan;
use Livewire\WithPagination;
use App\Models\JadwalAbsensi;
use App\Models\SisaCutiTahunan;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataCuti extends Component
{
    use WithPagination;
    // public $search = '';
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
            return CutiKaryawan::with('user')
                ->where(function ($query) use ($unitKepegawaianId) {
                    $query->where('status_cuti_id', 4)
                        ->orWhere(function ($q) use ($unitKepegawaianId) {
                            $q->where('status_cuti_id', 3)
                                ->whereHas('user', function ($subquery) use ($unitKepegawaianId) {
                                    $subquery->where('unit_id', $unitKepegawaianId);
                                });
                        });
                })
                ->orderByDesc('id')
                ->paginate(10);
        } else {
            // Selain KEPEGAWAIAN: hanya tampilkan berdasarkan unit_id user
            return CutiKaryawan::with('user')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('unit_id', $user->unit_id);
                })
                ->orderByDesc('id')
                ->paginate(10);
        }
    }

    public function approveCuti($cutiId, $userId)
    {
        $cuti = CutiKaryawan::find($cutiId);

        if ($cuti) {
            $user = auth()->user();
            $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
            $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)
                ->permission('approve-cuti') // ✅ Spatie helper method
                ->get();
            $targetUser = User::findOrFail($userId);
            if ($user->unit_id == $unitKepegawaianId) {
                // Jika user dari unit kepegawaian, setujui final
                if ($cuti->jenisCuti && strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan') {
                    $userCuti = SisaCutiTahunan::where('user_id', $userId)
                        ->where('tahun', now('Asia/Jakarta')->year)
                        ->first();

                    if ($userCuti) {
                        if ($userCuti->sisa_cuti >= $cuti->jumlah_hari) {
                            $cuti->update(['status_cuti_id' => 1]);
                            $userCuti->decrement('sisa_cuti', $cuti->jumlah_hari);
                        } else {
                            $cuti->update(['status_cuti_id' => 2]);

                            $nextUser = User::find($userId);
                            $message = 'Pengajuan Cuti anda (' . $nextUser->name . ') dari tanggal <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' . $cuti->tanggal_selesai . '</span> <span class="text-red-600 font-bold">Ditolak</span> karena sisa cuti tahunan tidak cukup.';

                            $url = "/pengajuan/cuti";
                            if ($nextUser) {
                                Notification::send($nextUser, new UserNotification($message, $url));
                            }

                            return redirect()->route('approvalcuti.index')->with('error', 'Sisa cuti tahunan tidak cukup, pengajuan otomatis ditolak.');
                        }
                    } else {
                        return redirect()->route('approvalcuti.index')->with('error', 'Data sisa cuti tidak ditemukan.');
                    }
                } else {
                    $cuti->update(['status_cuti_id' => 1]);
                }


                $shift = Shift::firstOrCreate(
                    ['nama_shift' => 'C'],
                    [
                        'unit_id' => $targetUser->unit_id,
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

                foreach (Carbon::parse($cuti->tanggal_mulai)->toPeriod(Carbon::parse($cuti->tanggal_selesai)) as $date) {
                    $jadwal = JadwalAbsensi::with('shift')->where('user_id', $userId)
                        ->whereDate('tanggal_jadwal', $date->toDateString())
                        ->first();

                    if ($jadwal && $jadwal->shift) {
                        $shift = $jadwal->shift;

                        $cutiStatusId = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
                        $jenisCuti = $cuti->jenisCuti->nama_cuti ?? 'Cuti';

                        Absen::updateOrCreate(
                            [
                                'user_id' => $userId,
                                'jadwal_id' => $jadwal->id,
                            ],
                            [
                                'status_absen_id' => $cutiStatusId,
                                'present' => 1,
                                'absent' => 0,
                                'late' => 0,
                                'time_in' => null,
                                'time_out' => null,
                                'keterangan' => 'Cuti disetujui',
                                'deskripsi_in' => $jenisCuti,
                                'deskripsi_out' => $jenisCuti,
                                'is_dinas' => false,
                                'is_lembur' => false,
                                'approved_lembur' => false,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }


                $nextUser = User::find($userId);
                $message = 'Pengajuan Cuti anda (' . $nextUser->name .
                    ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' . $cuti->tanggal_selesai .
                    '</span> telah <span class="text-success-600 font-bold">Disetujui Final</span> oleh ' . $user->name;

                $url = "/pengajuan/cuti";
                if ($nextUser) {
                    Notification::send($nextUser, new UserNotification($message, $url));
                }

                return redirect()->route('approvalcuti.index')->with('success', 'Pengajuan Cuti Disetujui Final!');
                $this->resetPage();
            } else {
                // Kalau unit selain kepegawaian, hanya setujui kepala unit
                $cuti->update(['status_cuti_id' => 4]);

                $nextUser = User::find($userId);
                $message = 'Pengajuan Cuti anda (' . $nextUser->name .
                    ') telah <span class="text-success-600 font-bold">Disetujui Kepala Unit</span> oleh ' . $user->name;
                $messagekepegawaian = 'Pengajuan Cuti atas nama (' . $nextUser->name .
                    ') telah <span class="text-success-600 font-bold">Disetujui Kepala Unit</span> oleh ' . $user->name . ', silahkan melanjutkan persetujuan ';

                $url = "/pengajuan/cuti";
                $urlkepegawaian = "/approvalcuti";
                if ($nextUser) {
                    Notification::send($nextUser, new UserNotification($message, $url));
                    Notification::send($kepegawaianUsers, new UserNotification($messagekepegawaian, $urlkepegawaian));
                }

                return redirect()->route('approvalcuti.index')->with('success', 'Cuti disetujui Kepala Unit!');
                $this->resetPage();
            }
        }
    }

    public function rejectCuti($cutiId, $userId, $reason = null)
    {
        $cuti = CutiKaryawan::find($cutiId);

        if ($cuti) {
            $cuti->update(['status_cuti_id' => 2]);

            // Ambil user yang mengajukan cuti
            $nextUser = User::find($userId);

            // Pesan notifikasi untuk user
            $message = 'Pengajuan Cuti anda (' . $nextUser->name .
                ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' .  $cuti->tanggal_selesai .
                '</span>  dengan keterangan "' . $cuti->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . auth()->user()->name .
                '. Alasan: "' . $reason . '"';

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
            'isKepegawaian' => $this->isKepegawaian,
        ]);
    }
}
