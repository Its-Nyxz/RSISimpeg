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
use App\Models\RiwayatApproval;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataCuti extends Component
{
    use WithPagination;

    public $isKepegawaian = false;

    public function mount()
    {
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $user = auth()->user();
        $this->isKepegawaian = $user->unit_id == $unitKepegawaianId;
    }

    public function loadData()
    {
        $user = auth()->user();
        $userRole = $user->roles->first()->name ?? 'Staf';
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');

        $query = CutiKaryawan::with('user');

        if ($this->isKepegawaian) {
            // Logika untuk staf Kepegawaian - hanya melihat yang sudah disetujui atau dari level tinggi
            $query->where(function ($q) {
                // Tampilkan pengajuan yang telah disetujui atasan unit (status 4)
                $q->where('status_cuti_id', 4)
                    // ATAU tampilkan pengajuan dari peran tingkat tinggi yang alurnya langsung ke Kepegawaian (status 3)
                    ->orWhere(function ($subQ) {
                        $subQ->where('status_cuti_id', 3)
                            ->whereHas('user.roles', function ($r) {
                                $r->whereIn('name', ['Direktur', 'Wadir', 'Manager', 'Kepala Seksi']);
                            });
                    });
            });
        } else {
            // ✅ PERBAIKAN UTAMA: Logika filtering berdasarkan hierarki yang benar
            $query->where('status_cuti_id', 3);

            // Filter berdasarkan role dan hierarki yang benar
            switch ($userRole) {
                case 'Kepala Ruang':
                    // Kepala Ruang hanya bisa melihat pengajuan dari Staf di unit yang sama
                    $query->whereHas('user', function ($q) use ($user) {
                        $q->where('unit_id', $user->unit_id)
                            ->whereHas('roles', function ($r) {
                                $r->where('name', 'like', 'Staf%'); // tangkap Staf, Staf Keuangan, Staf Kepegawaian
                            });
                    });
                    break;

                case 'Kepala Instalasi':
                    // Kepala Instalasi hanya bisa melihat pengajuan dari Kepala Ruang di unit yang sama
                    $query->whereHas('user', function ($q) use ($user) {
                        $q->where('unit_id', $user->unit_id)
                            ->whereHas('roles', function ($r) {
                                $r->where('name', 'Kepala Ruang');
                            });
                    });
                    break;

                case 'Kepala Unit':
                    // Kepala Unit tidak ada dalam alur approval cuti, jadi tidak bisa melihat apapun
                    $query->whereNull('id'); // Tidak akan menampilkan data apapun
                    break;

                case 'Kepala Seksi':
                    $query
                        ->whereHas('user', function ($uq) use ($user) {
                            $uq->where('unit_id', $user->unit_id);
                        })
                        ->whereHas('user.roles', function ($rq) {
                            $rq->where(function ($w) {
                                $w->where('name', 'like', 'Staf%') // semua varian Staf
                                    ->orWhereIn('name', ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit']);
                            });
                        });
                    break;

                case 'Kepala Seksi Keuangan':
                    $query
                        ->whereHas('user', function ($uq) use ($user) {
                            $uq->where('unit_id', $user->unit_id);
                        })
                        ->whereHas('user.roles', function ($rq) {
                            $rq->where(function ($w) {
                                $w->where('name', 'like', 'Staf%') // semua varian Staf
                                    ->orWhereIn('name', ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit']);
                            });
                        });
                    break;

                case 'Kepala Seksi Kepegawaian ':
                    $query
                        ->whereHas('user', function ($uq) use ($user) {
                            $uq->where('unit_id', $user->unit_id);
                        })
                        ->whereHas('user.roles', function ($rq) {
                            $rq->where(function ($w) {
                                $w->where('name', 'like', 'Staf%') // semua varian Staf
                                    ->orWhereIn('name', ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit']);
                            });
                        });
                    break;

                case 'Manager':
                    // Manager hanya bisa melihat pengajuan dari Kepala Seksi lintas unit
                    $query->whereHas('user', function ($q) {
                        $q->whereHas('roles', function ($r) {
                            $r->where('name', 'Kepala Seksi');
                        });
                    });
                    break;

                case 'Wadir':
                    // Wadir hanya bisa melihat pengajuan dari Manager lintas unit
                    $query->whereHas('user', function ($q) {
                        $q->whereHas('roles', function ($r) {
                            $r->where('name', 'Manager');
                        });
                    });
                    break;

                case 'Direktur':
                    // Direktur hanya bisa melihat pengajuan dari Wadir lintas unit
                    $query->whereHas('user', function ($q) {
                        $q->whereHas('roles', function ($r) {
                            $r->where('name', 'Wadir');
                        });
                    });
                    break;

                default:
                    // Role lain tidak bisa melihat approval apapun
                    $query->whereNull('id'); // Tidak akan menampilkan data apapun
                    break;
            }

            // Pengecualian agar approver tidak melihat pengajuan cuti mereka sendiri
            $query->where('user_id', '!=', $user->id);
        }

        return $query->orderByDesc('id')->paginate(10);
    }

    private function getNextApprovers($roleName)
    {
        $approvalFlow = [
            // Staf ditangani KR atau KS di step-1 (di-loadData sudah dibuka untuk KS),
            // next step final = Kepala Seksi Kepegawaian
            'Staf'            => ['Kepala Ruang', 'Kepala Seksi'], // (siapa pun yang take action di step-1)
            'Kepala Ruang'    => ['Kepala Seksi Kepegawaian'],     // langsung final
            'Kepala Instalasi' => ['Kepala Seksi', 'Kepala Seksi Kepegawaian'],
            'Kepala Unit'     => ['Kepala Seksi', 'Kepala Seksi Kepegawaian'],
            'Kepala Seksi'    => ['Kepala Seksi Kepegawaian'],     // untuk Staf langsung final
            'Manager'         => ['Wadir', 'Kepala Seksi Kepegawaian'],
            'Wadir'           => ['Direktur', 'Kepala Seksi Kepegawaian'],
            'Direktur'        => ['Kepala Seksi Kepegawaian'],
        ];

        return $approvalFlow[$roleName] ?? ['Kepala Seksi Kepegawaian'];
    }

    private function getUsersByRoles($roles)
    {
        return User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
    }

    public function approveCuti($cutiId, $userId)
    {
        $cuti = CutiKaryawan::find($cutiId);
        $user = auth()->user();
        $targetUser = User::findOrFail($userId);

        // Tambahkan validasi untuk mencegah pengguna menyetujui pengajuan cuti mereka sendiri.
        if ($user->id === $targetUser->id) {
            return redirect()->route('approvalcuti.index')->with('error', 'Anda tidak dapat menyetujui pengajuan cuti Anda sendiri.');
        }

        if (!$cuti) {
            return redirect()->route('approvalcuti.index')->with('error', 'Pengajuan cuti tidak ditemukan.');
        }

        $approverRole = $user->roles->first()->name ?? 'default';
        $nextApproverRoles = $this->getNextApprovers($approverRole);

        if (in_array('Kepala Seksi Kepegawaian', $nextApproverRoles) && $approverRole == 'Kepala Seksi Kepegawaian') {
            // Final approval
            if ($cuti->jenisCuti && strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan') {
                $userCuti = SisaCutiTahunan::where('user_id', $userId)
                    ->where('tahun', now('Asia/Jakarta')->year)
                    ->first();

                if ($userCuti) {
                    if ($userCuti->sisa_cuti >= $cuti->jumlah_hari) {
                        $cuti->update(['status_cuti_id' => 1]); // Status: Disetujui Final
                        $userCuti->decrement('sisa_cuti', $cuti->jumlah_hari);
                    } else {
                        $cuti->update(['status_cuti_id' => 2]); // Status: Ditolak
                        $message = 'Pengajuan Cuti anda (' . $targetUser->name . ') ... Ditolak karena sisa cuti tahunan tidak cukup.';
                        Notification::send($targetUser, new UserNotification($message, "/pengajuan/cuti"));
                        return redirect()->route('approvalcuti.index')->with('error', 'Sisa cuti tahunan tidak cukup, pengajuan otomatis ditolak.');
                    }
                } else {
                    return redirect()->route('approvalcuti.index')->with('error', 'Data sisa cuti tidak ditemukan.');
                }
            } else {
                $cuti->update(['status_cuti_id' => 1]); // Status: Disetujui Final
            }

            // Logic final approval: Update shifts, create absences, etc.
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
                $jadwal = JadwalAbsensi::with('shift')->where('user_id', $userId)->whereDate('tanggal_jadwal', $date->toDateString())->first();
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

            // Send notification to the user who requested the leave
            $message = 'Pengajuan Cuti anda (' . $targetUser->name . ') ... telah <span class="text-success-600 font-bold">Disetujui Final</span> oleh ' . $user->name;
            Notification::send($targetUser, new UserNotification($message, "/pengajuan/cuti"));

            // Record the final approval history
            RiwayatApproval::create([
                'cuti_id' => $cutiId,
                'approver_id' => $user->id,
                'status_approval' => 'disetujui_final',
                'approve_at' => now(),
                'catatan' => null
            ]);

            return redirect()->route('approvalcuti.index')->with('success', 'Pengajuan Cuti Disetujui Final!');
        } else {
            // Intermediate approval
            $cuti->update(['status_cuti_id' => 4]);

            $nextApprovers = collect();
            $targetUserRole = $targetUser->roles->first()->name ?? 'Staf';

            // --- ATURAN BARU: Maksimal 2 langkah untuk pemohon Staf ---
            if ($targetUserRole === 'Staf' && in_array($approverRole, ['Kepala Ruang', 'Kepala Seksi'])) {
                // Siapa pun yang approve duluan (KR/KS), langsung ke final: Kepala Seksi Kepegawaian
                $nextApprovers = User::whereHas('roles', function ($q) {
                    $q->where('name', 'Kepala Seksi Kepegawaian');
                })->get();
            } else {
                // --- Aturan lama untuk non-Staf (tetap seperti sebelumnya) ---
                if ($approverRole === 'Kepala Seksi' && in_array($targetUserRole, ['Kepala Instalasi', 'Kepala Unit'])) {
                    $nextApprovers = User::whereHas('roles', fn($q) => $q->where('name', 'Kepala Seksi Kepegawaian'))->get();
                } elseif ($approverRole === 'Kepala Seksi') {
                    $nextApprovers = User::whereHas('roles', fn($q) => $q->where('name', 'Manager'))->get();
                } elseif ($approverRole === 'Manager') {
                    $nextApprovers = User::whereHas('roles', fn($q) => $q->where('name', 'Wadir'))->get();
                } elseif ($approverRole === 'Wadir') {
                    $nextApprovers = User::whereHas('roles', fn($q) => $q->where('name', 'Direktur'))->get();
                } elseif ($approverRole === 'Direktur') {
                    $nextApprovers = User::whereHas('roles', fn($q) => $q->where('name', 'Kepala Seksi Kepegawaian'))->get();
                }
            }

            // --- Perbaiki label notifikasi agar dinamis (bukan "Kepala Unit") ---
            $statusLabel = 'Disetujui ' . ($user->roles->first()->name ?? 'Approver');

            $messageToUser = 'Pengajuan Cuti anda (' . $targetUser->name . ') telah <span class="text-success-600 font-bold">'
                . $statusLabel . '</span> oleh ' . $user->name;
            Notification::send($targetUser, new UserNotification($messageToUser, "/pengajuan/cuti"));

            if ($nextApprovers->count() > 0) {
                $messageToApprover = 'Pengajuan Cuti atas nama (' . $targetUser->name . ') telah <span class="text-success-600 font-bold">'
                    . $statusLabel . '</span> oleh ' . $user->name . ', silahkan melanjutkan persetujuan.';
                Notification::send($nextApprovers, new UserNotification($messageToApprover, "/approvalcuti"));
            }

            RiwayatApproval::create([
                'cuti_id' => $cutiId,
                'approver_id' => $user->id,
                'status_approval' => 'disetujui_intermediate',
                'approve_at' => now(),
                'catatan' => null
            ]);

            return redirect()->route('approvalcuti.index')->with('success', 'Cuti disetujui (menunggu final)!');
        }
    }

    public function rejectCuti($cutiId, $userId, $reason = null)
    {
        $cuti = CutiKaryawan::find($cutiId);
        $user = auth()->user();

        if ($cuti) {
            $cuti->update(['status_cuti_id' => 2]);
            $targetUser = User::find($userId);

            $message = 'Pengajuan Cuti anda (' . $targetUser->name .
                ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' .  $cuti->tanggal_selesai .
                '</span>  dengan keterangan "' . $cuti->keterangan . '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . $user->name .
                '. Alasan: "' . $reason . '"';

            $url = "/pengajuan/cuti";
            if ($targetUser) {
                Notification::send($targetUser, new UserNotification($message, $url));
            }

            RiwayatApproval::create([
                'cuti_id' => $cutiId,
                'approver_id' => $user->id,
                'status_approval' => 'ditolak',
                'catatan' => $reason,
                'approve_at' => now(),
            ]);

            return redirect()->route('approvalcuti.index')->with('success', 'Pengajuan cuti ditolak!');
        }
    }


    public function render()
    {
        $cutiData = $this->loadData();
        return view('livewire.data-cuti', [
            'users' => $cutiData,
            'isKepegawaian' => $this->isKepegawaian,
        ]);
    }
}
