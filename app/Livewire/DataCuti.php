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
use Illuminate\Support\Facades\DB;

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
            // ✅ PERBAIKAN UTAMA: Logika filtering berdasarkan hierarki yang benar dengan auto-skip
            $query->where('status_cuti_id', 3);

            // Filter berdasarkan role dan hierarki yang benar dengan logic skip
            switch ($userRole) {
                case 'Kepala Ruang':
                    // Kepala Ruang bisa melihat pengajuan dari Staf di unit yang sama
                    $query->whereHas('user', function ($q) use ($user) {
                        $q->where('unit_id', $user->unit_id)
                            ->whereHas('roles', function ($r) {
                                $r->where('name', 'like', 'Staf%');
                            });
                    });
                    break;

                case 'Kepala Instalasi':
                    // Kepala Instalasi bisa melihat dari Kepala Ruang atau skip langsung dari Staf jika KR tidak ada
                    $query->whereHas('user', function ($q) use ($user) {
                        $q->where('unit_id', $user->unit_id)
                            ->whereHas('roles', function ($r) {
                                // Bisa dari Kepala Ruang atau langsung dari Staf (jika KR tidak ada di unit)
                                $r->where('name', 'Kepala Ruang')
                                    ->orWhere('name', 'like', 'Staf%');
                            });
                    })->where(function ($subQuery) use ($user) {
                        // Jika ada Kepala Ruang di unit, hanya terima dari KR
                        // Jika tidak ada KR, terima langsung dari Staf
                        $hasKepalaRuang = $this->roleExistsInUnit('Kepala Ruang', $user->unit_id);
                        if ($hasKepalaRuang) {
                            $subQuery->whereHas('user.roles', function ($r) {
                                $r->where('name', 'Kepala Ruang');
                            });
                        } else {
                            $subQuery->whereHas('user.roles', function ($r) {
                                $r->where('name', 'like', 'Staf%');
                            });
                        }
                    });
                    break;

                case 'Kepala Unit':
                    // Kepala Unit bisa melihat dari KI/KR atau skip langsung dari level bawah jika tidak ada
                    $query->whereHas('user', function ($q) use ($user) {
                        $q->where('unit_id', $user->unit_id)
                            ->whereHas('roles', function ($r) {
                                $r->whereIn('name', ['Kepala Instalasi', 'Kepala Ruang'])
                                    ->orWhere('name', 'like', 'Staf%');
                            });
                    })->where(function ($subQuery) use ($user) {
                        $hasKI = $this->roleExistsInUnit('Kepala Instalasi', $user->unit_id);
                        $hasKR = $this->roleExistsInUnit('Kepala Ruang', $user->unit_id);

                        if ($hasKI) {
                            $subQuery->whereHas('user.roles', function ($r) {
                                $r->where('name', 'Kepala Instalasi');
                            });
                        } elseif ($hasKR) {
                            $subQuery->whereHas('user.roles', function ($r) {
                                $r->where('name', 'Kepala Ruang');
                            });
                        } else {
                            $subQuery->whereHas('user.roles', function ($r) {
                                $r->where('name', 'like', 'Staf%');
                            });
                        }
                    });
                    break;

                case 'Kepala Seksi':
                case 'Kepala Seksi Keuangan':
                case 'Kepala Seksi Kepegawaian':
                    // Kepala Seksi bisa melihat dari semua level bawah di unit atau skip sesuai ketersediaan
                    $query->whereHas('user', function ($uq) use ($user) {
                        $uq->where('unit_id', $user->unit_id)
                            ->whereHas('roles', function ($rq) {
                                $rq->where('name', 'like', 'Staf%')
                                    ->orWhereIn('name', ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit']);
                            });
                    });
                    break;

                case 'Manager':
                    // Manager bisa melihat dari Kepala Seksi atau skip dari level bawah jika KS tidak ada
                    $query->where(function ($subQuery) {
                        // Cek apakah ada pengajuan yang seharusnya dari KS tapi di-skip karena KS tidak ada
                        $subQuery->whereHas('user.roles', function ($r) {
                            $r->where('name', 'Kepala Seksi');
                        })
                            // ATAU pengajuan yang di-skip dari unit yang tidak punya KS
                            ->orWhere(function ($skipQuery) {
                                $skipQuery->whereHas('user', function ($uq) {
                                    // Cari pengajuan dari unit yang tidak punya Kepala Seksi
                                    $uq->whereHas('roles', function ($rq) {
                                        $rq->where('name', 'like', 'Staf%')
                                            ->orWhereIn('name', ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit']);
                                    });
                                })
                                    ->whereDoesntHave('user', function ($noKsQuery) {
                                        // Pastikan unit tersebut tidak punya Kepala Seksi
                                        $noKsQuery->whereExists(function ($existsQuery) {
                                            $existsQuery->select(\DB::raw(1))
                                                ->from('users as u2')
                                                ->join('model_has_roles as mhr2', 'u2.id', '=', 'mhr2.model_id')
                                                ->join('roles as r2', 'mhr2.role_id', '=', 'r2.id')
                                                ->whereRaw('u2.unit_id = users.unit_id')
                                                ->where('r2.name', 'Kepala Seksi');
                                        });
                                    });
                            });
                    });
                    break;

                case 'Wadir':
                    // Wadir bisa melihat dari Manager atau skip jika Manager tidak ada
                    $query->where(function ($subQuery) {
                        $subQuery->whereHas('user.roles', function ($r) {
                            $r->where('name', 'Manager');
                        })
                            ->orWhere(function ($skipQuery) {
                                // Skip jika tidak ada Manager - terima dari Kepala Seksi
                                $skipQuery->whereHas('user.roles', function ($r) {
                                    $r->where('name', 'Kepala Seksi');
                                })
                                    ->whereDoesntHave('user', function ($noManagerQuery) {
                                        $noManagerQuery->whereExists(function ($existsQuery) {
                                            $existsQuery->select(\DB::raw(1))
                                                ->from('users as u3')
                                                ->join('model_has_roles as mhr3', 'u3.id', '=', 'mhr3.model_id')
                                                ->join('roles as r3', 'mhr3.role_id', '=', 'r3.id')
                                                ->where('r3.name', 'Manager');
                                        });
                                    });
                            });
                    });
                    break;

                case 'Direktur':
                    // Direktur bisa melihat dari Wadir atau skip jika Wadir tidak ada
                    $query->where(function ($subQuery) {
                        $subQuery->whereHas('user.roles', function ($r) {
                            $r->where('name', 'Wadir');
                        })
                            ->orWhere(function ($skipQuery) {
                                // Skip jika tidak ada Wadir - terima dari Manager atau level bawah
                                $skipQuery->whereHas('user.roles', function ($r) {
                                    $r->whereIn('name', ['Manager', 'Kepala Seksi']);
                                })
                                    ->whereDoesntHave('user', function ($noWadirQuery) {
                                        $noWadirQuery->whereExists(function ($existsQuery) {
                                            $existsQuery->select(\DB::raw(1))
                                                ->from('users as u4')
                                                ->join('model_has_roles as mhr4', 'u4.id', '=', 'mhr4.model_id')
                                                ->join('roles as r4', 'mhr4.role_id', '=', 'r4.id')
                                                ->where('r4.name', 'Wadir');
                                        });
                                    });
                            });
                    });
                    break;

                default:
                    // Role lain tidak bisa melihat approval apapun
                    $query->whereNull('id');
                    break;
            }

            // Pengecualian agar approver tidak melihat pengajuan cuti mereka sendiri
            $query->where('user_id', '!=', $user->id);
        }

        return $query->orderByDesc('id')->paginate(10);
    }

    /**
     * 🔥 FUNGSI BARU: Cek apakah role tertentu ada di unit kerja
     */
    private function roleExistsInUnit($roleName, $unitId)
    {
        return User::where('unit_id', $unitId)
            ->whereHas('roles', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })
            ->exists();
    }

    /**
     * 🔥 FUNGSI BARU: Mencari next approver dengan auto-skip logic
     */
    private function findNextApproversWithSkip($targetUser, $currentApproverRole)
    {
        $targetUserRole = $targetUser->roles->first()->name ?? 'Staf';
        $unitId = $targetUser->unit_id;

        // 🔥 Tambahan untuk staf khusus
        if (stripos($targetUserRole, 'Staf Kepegawaian') !== false) {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'like', '%Kepala Seksi Kepegawaian%');
            })->get();
        }

        if (stripos($targetUserRole, 'Staf Keuangan') !== false) {
            $ksKeu = User::where('unit_id', $unitId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'like', '%Kepala Seksi Keuangan%');
                })->get();

            return $ksKeu->count() > 0 ? $ksKeu : User::whereHas('roles', function ($q) {
                $q->where('name', 'like', '%Kepala Seksi Kepegawaian%');
            })->get();
        }

        // Definisi hierarki approval berdasarkan role pemohon
        $approvalHierarchy = [
            'Staf' => [
                'unit_based' => ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit', 'Kepala Seksi'],
                'cross_unit' => ['Manager', 'Wadir', 'Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Kepala Ruang' => [
                'unit_based' => ['Kepala Instalasi', 'Kepala Unit', 'Kepala Seksi'],
                'cross_unit' => ['Manager', 'Wadir', 'Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Kepala Instalasi' => [
                'unit_based' => ['Kepala Unit', 'Kepala Seksi'],
                'cross_unit' => ['Manager', 'Wadir', 'Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Kepala Unit' => [
                'unit_based' => ['Kepala Seksi'],
                'cross_unit' => ['Manager', 'Wadir', 'Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Kepala Seksi' => [
                'unit_based' => [],
                'cross_unit' => ['Manager', 'Wadir', 'Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Manager' => [
                'unit_based' => [],
                'cross_unit' => ['Wadir', 'Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Wadir' => [
                'unit_based' => [],
                'cross_unit' => ['Direktur'],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
            'Direktur' => [
                'unit_based' => [],
                'cross_unit' => [],
                'final' => 'Kepala Seksi Kepegawaian'
            ],
        ];

        $hierarchy = $approvalHierarchy[$targetUserRole] ?? $approvalHierarchy['Staf'];
        $allRoles = array_merge($hierarchy['unit_based'], $hierarchy['cross_unit'], [$hierarchy['final']]);

        // Cari posisi current approver dalam hierarki
        $currentIndex = array_search($currentApproverRole, $allRoles);

        if ($currentIndex === false) {
            // Jika current role tidak ditemukan, kembalikan ke final approver
            return User::whereHas('roles', function ($q) use ($hierarchy) {
                $q->where('name', $hierarchy['final']);
            })->get();
        }

        // Cari next approver dengan skip logic
        for ($i = $currentIndex + 1; $i < count($allRoles); $i++) {
            $nextRole = $allRoles[$i];

            // Jika final approver, langsung return
            if ($nextRole === $hierarchy['final']) {
                return User::whereHas('roles', function ($q) use ($nextRole) {
                    $q->where('name', $nextRole);
                })->get();
            }

            // Cek apakah role ada di unit (untuk unit-based roles)
            if (in_array($nextRole, $hierarchy['unit_based'])) {
                if ($this->roleExistsInUnit($nextRole, $unitId)) {
                    return User::where('unit_id', $unitId)
                        ->whereHas('roles', function ($q) use ($nextRole) {
                            $q->where('name', $nextRole);
                        })->get();
                }
                // Jika tidak ada, lanjut ke iterasi berikutnya (auto-skip)
            } else {
                // Untuk cross-unit roles, langsung cari tanpa filter unit
                $users = User::whereHas('roles', function ($q) use ($nextRole) {
                    $q->where('name', $nextRole);
                })->get();

                if ($users->count() > 0) {
                    return $users;
                }
                // Jika tidak ada, lanjut ke iterasi berikutnya (auto-skip)
            }
        }

        // Jika semua di-skip, kembalikan final approver
        return User::whereHas('roles', function ($q) use ($hierarchy) {
            $q->where('name', $hierarchy['final']);
        })->get();
    }

    /**
     * 🔥 FUNGSI BARU: Cek apakah sudah final approval
     */
    private function isFinalApproval($currentApproverRole, $nextApprovers)
    {
        return $currentApproverRole === 'Kepala Seksi Kepegawaian' ||
            $nextApprovers->isEmpty() ||
            $nextApprovers->every(function ($approver) {
                return $approver->roles->first()->name === 'Kepala Seksi Kepegawaian';
            });
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

        // 🔥 GUNAKAN FUNGSI BARU untuk mencari next approver dengan auto-skip
        $nextApprovers = $this->findNextApproversWithSkip($targetUser, $approverRole);

        // 🔥 CEK APAKAH INI FINAL APPROVAL
        if ($this->isFinalApproval($approverRole, $nextApprovers)) {
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
