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
use App\Models\JenisKaryawan;
use Livewire\WithPagination;
use App\Models\JadwalAbsensi;
use App\Models\MasterJatahCuti;
use App\Models\SisaCutiTahunan;
use App\Models\RiwayatApproval;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataCuti extends Component
{
    use WithPagination;

    public $isKepegawaian = false;
    public $unitKepegawaianId;
    public $isRiwayatCuti = false;

    // State for Riwayat Cuti detail view
    public $selectedRiwayatUserId = null;
    public $selectedRiwayatUserName = '';

    // Filter properties
    public $selectedUserAktif = 1;
    public $selectedUnit = '';
    public $selectedJenisKaryawan = 1;
    public $search = '';

    // Options for dropdowns
    public $units = [];
    public $jenisKaryawans = [];

    public function mount()
    {
        $user = auth()->user();
        $this->unitKepegawaianId = 87;
        $this->isKepegawaian = $user->unit_id == $this->unitKepegawaianId || $user->roles->pluck('id')->first() == 2 || $user->roles->pluck('id')->first() == 14 || $user->hasRole('Super Admin');
        $this->isRiwayatCuti = request()->routeIs('riwayatcuti.*') || request()->is('riwayatcuti');

        $this->units = UnitKerja::orderBy('id')->get();
        $this->jenisKaryawans = JenisKaryawan::orderBy('id')->get();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage('usersPage');
    }

    public function updatedSelectedUserAktif() { $this->resetPage('usersPage'); }
    public function updatedSelectedUnit() { $this->resetPage('usersPage'); }
    public function updatedSelectedJenisKaryawan() { $this->resetPage('usersPage'); }

    private function getAllChildUnitIds($unitId)
    {
        $unitIds = [$unitId];
        $childs = UnitKerja::where('parent_id', $unitId)->pluck('id')->toArray();
        foreach ($childs as $childId) {
            $unitIds = array_merge($unitIds, $this->getAllChildUnitIds($childId));
        }
        return $unitIds;
    }

    /** ----------------------------------------------------------------
     * Load Data (for approval cuti - status 3,4)
     * ---------------------------------------------------------------- */
    public function loadData()
    {
        $user = auth()->user();

        if (!$user->can('approval-cuti')) {
            return CutiKaryawan::whereNull('id')->paginate(10);
        }

        $query = CutiKaryawan::with(['user.unitkerja', 'jenisCuti', 'statusCuti'])
            ->whereIn('status_cuti_id', [3, 4])
            ->where('user_id', '!=', $user->id);

        $query = $this->applyFiltersCuti($query);

        if ($this->isKepegawaian) {
            return $query->orderByDesc('id')->paginate(10);
        }

        $hasChild = UnitKerja::where('parent_id', $user->unit_id)->exists();
        $unitIds = $hasChild ? $this->getAllChildUnitIds($user->unit_id) : [$user->unit_id];

        return $query->whereHas('user', function ($q) use ($unitIds) {
            $q->whereIn('unit_id', $unitIds);
        })->orderByDesc('id')->paginate(10);
    }

    /** ----------------------------------------------------------------
     * Load Users (for riwayat cuti list)
     * ---------------------------------------------------------------- */
    public function loadUsers()
    {
        $user = auth()->user();
        $query = User::with(['kategorijabatan', 'unitKerja'])->where('id', '!=', $user->id);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        if ($this->selectedUnit) {
            $query->where('unit_id', $this->selectedUnit);
        }
        if ($this->selectedJenisKaryawan) {
            $query->where('jenis_id', $this->selectedJenisKaryawan);
        }
        if (isset($this->selectedUserAktif)) {
            $query->where('status_karyawan', $this->selectedUserAktif);
        }

        if (!$this->isKepegawaian) {
            $hasChild = UnitKerja::where('parent_id', $user->unit_id)->exists();
            $unitIds = $hasChild ? $this->getAllChildUnitIds($user->unit_id) : [$user->unit_id];
            $query->whereIn('unit_id', $unitIds);
        }

        // Only load 5 users at a time to leave vertical room for the detail table
        return $query->orderBy('name', 'asc')->paginate(5, ['*'], 'usersPage');
    }

    /** ----------------------------------------------------------------
     * Load Detailed Cuti for a Selected User
     * ---------------------------------------------------------------- */
    public function loadUserCutiHistory()
    {
        if (!$this->selectedRiwayatUserId) return null;

        return CutiKaryawan::with(['jenisCuti', 'statusCuti'])
            ->where('user_id', $this->selectedRiwayatUserId)
            ->orderBy('id')
            ->paginate(10, ['*'], 'detailsPage');
    }

    private function applyFiltersCuti($query)
    {
        if ($this->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
        }
        if ($this->selectedUnit) {
            $query->whereHas('user', fn($q) => $q->where('unit_id', $this->selectedUnit));
        }
        if ($this->selectedJenisKaryawan) {
            $query->whereHas('user', fn($q) => $q->where('jenis_id', $this->selectedJenisKaryawan));
        }
        if (isset($this->selectedUserAktif)) {
            $query->whereHas('user', fn($q) => $q->where('status_karyawan', $this->selectedUserAktif));
        }
        return $query;
    }

    /** Select a user to view their history */
    public function selectRiwayatUser($userId, $userName)
    {
        $this->selectedRiwayatUserId = $userId;
        $this->selectedRiwayatUserName = $userName;
        $this->resetPage('detailsPage'); // Reset only the details table to page 1
    }

    /** Close specific user history */
    public function closeRiwayatUser()
    {
        $this->selectedRiwayatUserId = null;
        $this->selectedRiwayatUserName = '';
    }

    /** ----------------------------------------------------------------
     *  Apply Filters (shared between loadData and loadAllData)
     *  ---------------------------------------------------------------- */
    private function applyFilters($query)
    {
        // Apply search filter
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply unit filter
        if ($this->selectedUnit) {
            $query->whereHas('user', function ($q) {
                $q->where('unit_id', $this->selectedUnit);
            });
        }

        // Apply jenis karyawan filter
        if ($this->selectedJenisKaryawan) {
            $query->whereHas('user', function ($q) {
                $q->where('jenis_id', $this->selectedJenisKaryawan);
            });
        }

        // Apply aktif/non-aktif filter
        if ($this->selectedUserAktif == 1) {
            $query->whereHas('user', function ($q) {
                $q->where('status_karyawan', 1);
            });
        } else {
            $query->whereHas('user', function ($q) {
                $q->where('status_karyawan', 0);
            });
        }

        return $query;
    }

    /** ----------------------------------------------------------------
     *  Approve Cuti
     *  ---------------------------------------------------------------- */
    public function approveCuti($cutiId, $userId)
    {
        $user = auth()->user();

        if (!$user->can('approval-cuti')) {
            return redirect()->route('approvalcuti.index')
                ->with('error', 'Anda tidak memiliki izin untuk menyetujui cuti.');
        }

        $cuti = CutiKaryawan::find($cutiId);
        $targetUser = User::findOrFail($userId);

        if (!$cuti) {
            return redirect()->route('approvalcuti.index')
                ->with('error', 'Pengajuan cuti tidak ditemukan.');
        }

        /** ------------------------------------------------------------
         *  Jika user adalah Wadir/Direktur dan cuti dari Manager → Final
         *  ------------------------------------------------------------ */
        if (in_array($user->jabatan_id, [1, 2]) && $cuti->user->jabatan_id == 3) {
            $cutiStatus = 1; // Disetujui final

            // 🔥 Validasi sisa cuti tahunan
            if ($cuti->jenisCuti && strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan') {
                $userCuti = SisaCutiTahunan::where('user_id', $cuti->user_id)
                    ->where('tahun', now('Asia/Jakarta')->year)
                    ->first();

                if ($userCuti) {
                    if ($userCuti->sisa_cuti >= $cuti->jumlah_hari) {
                        $userCuti->decrement('sisa_cuti', $cuti->jumlah_hari);
                    } else {
                        $cuti->update(['status_cuti_id' => 2]);
                        Notification::send($targetUser, new UserNotification(
                            'Pengajuan cuti anda ditolak karena sisa cuti tahunan tidak cukup.',
                            "/pengajuan/cuti"
                        ));
                        return redirect()->route('approvalcuti.index')
                            ->with('error', 'Sisa cuti tahunan tidak cukup, pengajuan otomatis ditolak.');
                    }
                } else {
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Data sisa cuti tidak ditemukan.');
                }
            }

            // ✅ Final approval
            $cuti->update(['status_cuti_id' => $cutiStatus]);
            $pesan = "Pengajuan cuti Anda telah <span class='text-success-600 font-bold'>Disetujui Final</span> oleh {$user->name} ({$user->jabatan->nama}).";
            Notification::send($targetUser, new UserNotification($pesan, "/pengajuan/cuti"));

            RiwayatApproval::create([
                'cuti_id' => $cutiId,
                'approver_id' => $user->id,
                'status_approval' => 'disetujui_final_wadir_direktur',
                'approve_at' => now(),
                'catatan' => null,
            ]);

            return redirect()->route('approvalcuti.index')
                ->with('success', 'Cuti Manager telah disetujui final oleh Wadir/Direktur.');
        }

        /** ------------------------------------------------------------
         *  Logika default (Manager, Kepala, Kepegawaian, dst)
         *  ------------------------------------------------------------ */
        $userRoleId = $user->roles->pluck('id')->first();
        $isFinalApprover = (
            $userRoleId == 2 ||
            $user->unit_id == $this->unitKepegawaianId
        );

        $cutiStatus = $isFinalApprover ? 1 : 4; // 1 = Final, 4 = Menunggu Final

        // 🔥 Validasi sisa cuti tahunan jika final
        if (
            $cutiStatus == 1 &&
            $cuti->jenisCuti &&
            strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan'
        ) {

            // dd($targetUser->jenis_id != 1);
            if ($targetUser->jenis_id != 1 || empty($targetUser->jenis_id)) {
                //chek user apakah data jenis dan tmt masuk terisi
                // 1. Cek jika KEDUANYA kosong
                if (empty($targetUser->jenis_id) && empty($targetUser->tmt_masuk) && empty($targetUser->tmt)) {
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Data jenis Karyawan & TMT belum terisi');
                }

                // 2. Cek jika salah satu saja yang kosong
                if (empty($targetUser->jenis_id)) {
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Data jenis Karyawan belum terisi');
                }

                if (empty($targetUser->tmt_masuk)) {
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Data TMT masuk belum terisi');
                }
                if (empty($targetUser->tmt)) {
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Data TMT belum terisi');
                }

                //chek user apakah data jenis = kontrak dan tmt masuk  sudah bekrja selama 12 bulan >
                $now = now('Asia/Jakarta');
                $tmt = Carbon::parse($targetUser->tmt, 'Asia/Jakarta');
                $targetTanggal = $tmt->copy()->addMonths(12);

                // 3. Cek Kondisi
                if ($targetUser->jenis_id === 3) {
                    // jika masa kerja belum 12 bulan
                    if (!$now->greaterThanOrEqualTo($targetTanggal)) {
                        // targetUser kontrak belum boleh cuti
                        $sisaBulan = ceil($now->floatDiffInMonths($targetTanggal));
                        return redirect()->route('approvalcuti.index')
                            ->with('error', "Maaf, karyawan kontrak baru bisa cuti setelah 12 bulan. Sisa masa tunggu: $sisaBulan bulan lagi.");
                    } else {
                        $currentYear = now('Asia/Jakarta')->year;
                        // 1. Ambil jatah cuti dari master, default ke 12 jika tidak ada
                        $jatahCuti = MasterJatahCuti::where('tahun', $currentYear)->value('jumlah_cuti') ?? 12;

                        // 2. Cek apakah sisa cuti tahun ini sudah dibuat untuk targetUser ini
                        // Jika belum ada, maka buat baru. Jika sudah ada, ambil datanya.
                        SisaCutiTahunan::firstOrCreate(
                            [
                                'user_id' => $targetUser->id,
                                'tahun'   => $currentYear
                            ],
                            [
                                'sisa_cuti' => $jatahCuti // Ini hanya diisi jika record baru dibuat
                            ]
                        );
                    }
                }
            };
            // dd('1');
            $userCuti = SisaCutiTahunan::where('user_id', $userId)
                ->where('tahun', now('Asia/Jakarta')->year)
                ->first();

            if ($userCuti) {
                if ($userCuti->sisa_cuti >= $cuti->jumlah_hari) {
                    $userCuti->decrement('sisa_cuti', $cuti->jumlah_hari);
                } else {
                    $cuti->update(['status_cuti_id' => 2]);
                    Notification::send($targetUser, new UserNotification(
                        'Pengajuan cuti anda ditolak karena sisa cuti tahunan tidak cukup.',
                        "/pengajuan/cuti"
                    ));
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Sisa cuti tahunan tidak cukup, pengajuan otomatis ditolak.');
                }
            } else {
                return redirect()->route('approvalcuti.index')
                    ->with('error', 'Data sisa cuti tidak ditemukan.');
            }
        }

        // 🔹 Update status cuti
        $cuti->update(['status_cuti_id' => $cutiStatus]);

        // 🔹 Jika final → buat jadwal absen otomatis
        if ($cutiStatus == 1) {
            $shift = Shift::firstOrCreate(
                ['nama_shift' => 'C'],
                [
                    'unit_id' => $targetUser->unit_id,
                    'jam_masuk' => null,
                    'jam_keluar' => null,
                    'keterangan' => 'Cuti',
                ]
            );

            $start = Carbon::parse($cuti->tanggal_mulai);
            $end = Carbon::parse($cuti->tanggal_selesai);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                $jadwal = JadwalAbsensi::updateOrCreate(
                    ['user_id' => $userId, 'tanggal_jadwal' => $date->toDateString()],
                    ['shift_id' => $shift->id]
                );

                $cutiStatusId = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
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
                        'deskripsi_in' => $cuti->jenisCuti->nama_cuti ?? 'Cuti',
                        'deskripsi_out' => $cuti->jenisCuti->nama_cuti ?? 'Cuti',
                        'is_dinas' => false,
                        'is_lembur' => false,
                        'approved_lembur' => false,
                    ]
                );
            }
        }

        // 🔔 Notifikasi ke pemohon
        $statusText = $isFinalApprover ? 'Disetujui Final' : 'Disetujui (Menunggu Final)';
        $message = "Pengajuan cuti anda telah <span class='text-success-600 font-bold'>{$statusText}</span> oleh {$user->name}";
        Notification::send($targetUser, new UserNotification($message, "/pengajuan/cuti"));

        // 🔔 Jika belum final → notifikasi ke Kepegawaian
        if (!$isFinalApprover) {
            $finalApprovers = User::where('unit_id', $this->unitKepegawaianId)
                ->orWhereHas('roles', fn($q) => $q->where('id', 2))
                ->get();

            if ($finalApprovers->count() > 0) {
                $msg = "Pengajuan cuti atas nama {$targetUser->name} telah disetujui oleh {$user->name}, menunggu persetujuan final Kepegawaian.";
                Notification::send($finalApprovers, new UserNotification($msg, "/approvalcuti"));
            }
        }

        // 🧾 Simpan riwayat approval
        RiwayatApproval::create([
            'cuti_id' => $cutiId,
            'approver_id' => $user->id,
            'status_approval' => $isFinalApprover ? 'disetujui_final' : 'disetujui_intermediate',
            'approve_at' => now(),
            'catatan' => null,
        ]);

        return redirect()->route('approvalcuti.index')->with('success', "Cuti {$statusText}!");
    }

    /** ----------------------------------------------------------------
     *  Reject Cuti
     *  ---------------------------------------------------------------- */
    public function rejectCuti($cutiId, $userId, $reason = null)
    {
        $cuti = CutiKaryawan::find($cutiId);
        $user = auth()->user();

        if ($cuti) {
            $cuti->update(['status_cuti_id' => 2]);
            $targetUser = User::find($userId);

            $message = 'Pengajuan Cuti anda (' . $targetUser->name . ') mulai ' .
                '<span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' . $cuti->tanggal_selesai .
                '</span> dengan keterangan "' . $cuti->keterangan .
                '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh ' . $user->name .
                '. Alasan: "' . $reason . '"';

            Notification::send($targetUser, new UserNotification($message, "/pengajuan/cuti"));

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

    /** ----------------------------------------------------------------
     *  Render
     *  ---------------------------------------------------------------- */
    public function render()
    {
        $cutiData = null;
        $riwayatUsers = null;
        $riwayatCutiDetail = null;

        if (!$this->isRiwayatCuti) {
            $cutiData = $this->loadData();
        } else {
            $riwayatUsers = $this->loadUsers();
            $riwayatCutiDetail = $this->loadUserCutiHistory();
        }

        return view('livewire.data-cuti', [
            'users' => $cutiData,
            'riwayatUsers' => $riwayatUsers,
            'riwayatCutiDetail' => $riwayatCutiDetail,
            'units' => $this->units,
            'jenisKaryawans' => $this->jenisKaryawans,
            'isRiwayatCuti' => $this->isRiwayatCuti,
        ]);
    }
}