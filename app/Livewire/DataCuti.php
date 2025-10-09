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
        $role = $user->roles->first()->name ?? 'Staf';

        // Build base query (we will refine it per-role)
        $baseQuery = CutiKaryawan::with('user')
            ->whereIn('status_cuti_id', [3, 4]) // 3 = menunggu, 4 = approved sementara (intermediate)
            ->where('user_id', '!=', $user->id);

        // Jika user adalah Kepala Seksi Kepegawaian -> batasi hanya yg sudah disetujui oleh KS Keuangan
        if ($role === 'Kepala Seksi Kepegawaian') {
            // Ambil id cuti yg sudah punya riwayat 'disetujui_intermediate' oleh Kepala Seksi Keuangan
            $approvedByKeuangan = RiwayatApproval::where('status_approval', 'disetujui_intermediate')
                ->whereHas('approver.roles', function ($q) {
                    $q->where('name', 'Kepala Seksi Keuangan');
                })
                ->pluck('cuti_id')
                ->toArray();

            // Jika belum ada yang disetujui KS Keuangan -> kosongkan hasil
            if (empty($approvedByKeuangan)) {
                // Kembalikan query yang akan menghasilkan 0 row
                $query = $baseQuery->whereNull('id')->orderByDesc('id');
                return $query->paginate(10);
            }

            // Hanya tampilkan yang termasuk daftar yang sudah disetujui KS Keuangan
            $query = $baseQuery->whereIn('id', $approvedByKeuangan);
        } else {
            // Untuk role selain KS Kepegawaian gunakan baseQuery sebagai starting point
            $query = $baseQuery;
        }

        // Jika kepegawaian (unit KEPEGAWAIAN) → lihat semua (override role restrictions)
        if ($this->isKepegawaian) {
            return $query->orderByDesc('id')->paginate(10);
        }

        // Ambil unit user dan semua child unit
        $unitIds = $this->getAllChildUnitIds($user->unit_id);

        switch ($role) {
            case 'Kepala Ruang':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->where('unit_id', $user->unit_id)
                        ->whereHas('roles', fn($r) => $r->where('name', 'Staf'))
                );
                break;

            case 'Kepala Instalasi':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereIn('unit_id', $unitIds)
                        ->whereHas('roles', fn($r) => $r->whereIn('name', ['Staf', 'Kepala Ruang']))
                );
                break;

            case 'Kepala Unit':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereIn('unit_id', $unitIds)
                        ->whereHas('roles', fn($r) => $r->whereIn('name', ['Staf', 'Kepala Ruang', 'Kepala Instalasi']))
                );
                break;

            case 'Kepala Seksi':
            case 'Kepala Seksi Keuangan':
            case 'Kepala Seksi Kepegawaian':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereIn('unit_id', $unitIds)
                        ->whereHas('roles', fn($r) => $r->whereIn('name', ['Staf','Staf Keuangan', 'Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit']))
                );
                break;

            case 'Manager':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereIn('unit_id', $unitIds)
                        ->whereHas('roles', fn($r) => $r->whereIn('name', ['Kepala Seksi']))
                );
                break;

            case 'Wadir':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereIn('unit_id', $unitIds)
                        ->whereHas('roles', fn($r) => $r->whereIn('name', ['Manager']))
                );
                break;

            case 'Direktur':
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereIn('unit_id', $unitIds)
                        ->whereHas('roles', fn($r) => $r->whereIn('name', ['Wadir', 'Manager']))
                );
                break;

            default:
                $query->whereNull('id'); // role lain tidak bisa lihat
        }

        return $query->orderByDesc('id')->paginate(10);
    }



    private function getAllChildUnitIds($unitId)
    {
        $unitIds = [$unitId];

        $childs = UnitKerja::where('parent_id', $unitId)->pluck('id')->toArray();

        foreach ($childs as $childId) {
            $unitIds = array_merge($unitIds, $this->getAllChildUnitIds($childId));
        }

        return $unitIds;
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
    private function findNextApproversWithSkip($targetUser)
    {
        $targetUserRole = $targetUser->roles->first()->name ?? 'Staf';
        $unitId = $targetUser->unit_id;

        // 🔹 Case khusus Staf Kepegawaian → langsung final
        if (stripos($targetUserRole, 'Staf Kepegawaian') !== false) {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Kepala Seksi Kepegawaian');
            })->get();
        }

        // 🔹 Case khusus Staf Keuangan → wajib lewat Ka Seksi Keuangan dulu, baru Ka Seksi Kepegawaian
        if (stripos($targetUserRole, 'Staf Keuangan') !== false) {
            $ksKeu = User::where('unit_id', $unitId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Kepala Seksi Keuangan');
                })->get();

            // Jika ada KS Keuangan → kirim ke mereka dulu
            if ($ksKeu->count() > 0) {
                return $ksKeu;
            }

            // Jika tidak ada KS Keuangan → langsung ke KS Kepegawaian (final)
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Kepala Seksi Kepegawaian');
            })->get();
        }


        // 🔹 Hierarki untuk role biasa (hanya 1 level atasan lalu final)
        $approvalMap = [
            'Staf'            => ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit', 'Kepala Seksi', 'Manager', 'Wadir', 'Direktur'],
            'Kepala Ruang'    => ['Kepala Instalasi', 'Kepala Unit', 'Kepala Seksi'],
            'Kepala Instalasi' => ['Kepala Seksi'],
            'Kepala Unit'     => ['Kepala Seksi'],
            'Kepala Seksi'    => ['Manager'],
            'Manager'         => ['Wadir'],
            'Wadir'           => ['Direktur'],
            'Direktur'        => [], // langsung final
        ];


        $nextRoles = $approvalMap[$targetUserRole] ?? [];
        $nextApprovers = collect();

        if ($targetUserRole === 'Kepala Ruang') {
            $nextApprovers = $this->findParentUnitApprovers($unitId, 'Kepala Instalasi');
            if ($nextApprovers->isEmpty()) {
                $nextApprovers = $this->findParentUnitApprovers($unitId, 'Kepala Unit');
            }
            if ($nextApprovers->isEmpty()) {
                $nextApprovers = $this->findParentUnitApprovers($unitId, 'Kepala Seksi');
            }
        }

        // 🔹 Cari atasan langsung yang ada → kalau kosong, skip ke level di atasnya
        foreach ($nextRoles as $role) {
            $query = User::query();

            // Unit-based roles → cek di unit yang sama
            $unitBased = ['Kepala Ruang', 'Kepala Instalasi', 'Kepala Unit', 'Kepala Seksi'];
            if (in_array($role, $unitBased)) {
                $query->where('unit_id', $unitId);
            }

            $users = $query->whereHas('roles', fn($q) => $q->where('name', $role))->get();

            if ($users->count() > 0) {
                $nextApprovers = $users;
                break; // stop di atasan terdekat
            }
        }

        // 🔹 Jika tidak ketemu → langsung final (Ka Seksi Kepegawaian)
        if ($nextApprovers->isEmpty()) {
            $nextApprovers = User::whereHas('roles', function ($q) {
                $q->where('name', 'Kepala Seksi Kepegawaian');
            })->get();
        }

        return $nextApprovers;
    }

    private function findParentUnitApprovers($unitId, $roleName)
    {
        // Ambil unit sekarang
        $unit = UnitKerja::find($unitId);

        // Kalau tidak ada unit → langsung return kosong
        if (!$unit) return collect();

        // Cari atasan langsung (parent)
        $parentId = $unit->parent_id;

        while ($parentId) {
            $parentUnit = UnitKerja::find($parentId);

            if ($parentUnit) {
                // Cek apakah ada user di parent dengan role tertentu
                $approvers = User::where('unit_id', $parentUnit->id)
                    ->whereHas('roles', function ($q) use ($roleName) {
                        $q->where('name', $roleName);
                    })->get();

                if ($approvers->count() > 0) {
                    return $approvers; // ketemu, stop
                }

                // Kalau tidak ada → naik lagi ke parent di atasnya
                $parentId = $parentUnit->parent_id;
            } else {
                break;
            }
        }

        // Kalau mentok tapi tidak ketemu → return kosong
        return collect();
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
        // 🔥 Tambahan validasi agar KS Kepegawaian tidak bisa approve sebelum KS Keuangan menyetujui
        if ($approverRole === 'Kepala Seksi Kepegawaian') {
            $alreadyApprovedByKSKeuangan = RiwayatApproval::where('cuti_id', $cutiId)
                ->where('status_approval', 'disetujui_intermediate')
                ->whereHas('approver.roles', function ($q) {
                    $q->where('name', 'Kepala Seksi Keuangan');
                })
                ->exists();

            if (!$alreadyApprovedByKSKeuangan) {
                return redirect()->route('approvalcuti.index')
                    ->with('error', 'Cuti belum disetujui oleh Kepala Seksi Keuangan.');
            }
        }


        // 🔥 GUNAKAN FUNGSI BARU untuk mencari next approver dengan auto-skip
        $nextApprovers = $this->findNextApproversWithSkip($targetUser);

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
