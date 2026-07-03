<?php

namespace App\Livewire;

use App\Models\JenisKaryawan;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Shift;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\StatusAbsen;
use App\Models\IzinKaryawan;
use Livewire\WithPagination;
use App\Exports\ExportRiwayat;
use App\Models\JadwalAbsensi;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class DataIzin extends Component
{
    use WithPagination;
    public $isKepegawaian = false;
    public $isRiwayatIzin = false;
    // Riwayat (history) state
    public $selectedRiwayatUserId = null;
    public $selectedRiwayatUserName = '';

    // Filters
    public $selectedUserAktif = 1;
    public $selectedUnit = '';
    public $search = '';
    public $units = [];
    public $bulan;
    public $tahun;
    public $tanggalMulaiFilter = '';
    public $tanggalSelesaiFilter = '';
    public $jenisKaryawan = [];
    public $selectedJenisKaryawan = '';

    public function mount()
    {
        $this->loadData();
        $unitKepegawaianId = 87;
        $user = auth()->user();

        $this->isKepegawaian = $user->unit_id == $unitKepegawaianId || $user->roles->pluck('id')->first() == 2 || $user->roles->pluck('id')->first() == 14 || $user->hasRole('Super Admin');
        $this->isRiwayatIzin = request()->routeIs('riwayatizin.*') || request()->is('riwayatizin');

        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->tanggalMulaiFilter = now()->format('Y-m-d');
        $this->units = \App\Models\UnitKerja::orderBy('id')->get();
        $this->jenisKaryawan = JenisKaryawan::orderBy('id')->get();
    }

    public function loadData()
    {
        $user = auth()->user();
        if (!$user->can('approval-izin')) {
            return IzinKaryawan::whereNull('id')->paginate(10);
        }

        $query = IzinKaryawan::with(['user.unitKerja', 'jenisIzin', 'statusIzin'])
            ->whereIn('status_izin_id', [3, 4])
            ->where('user_id', '!=', $user->id);

        $query = $this->applyFiltersToUserQuery($query);

        if ($this->isKepegawaian) {
            return $query->orderByDesc('id')->paginate(10);
        }

        $hasChild = \App\Models\UnitKerja::where('parent_id', $user->unit_id)->exists();
        $unitIds = $hasChild ? $this->getAllChildUnitIds($user->unit_id) : [$user->unit_id];
        
        return $query->whereHas('user', function ($q) use ($unitIds) {
            $q->whereIn('unit_id', $unitIds);
        })->orderByDesc('id')->paginate(10);

        // if ($user->unit_id == $unitKepegawaianId) {
        //     // Kalau dari unit KEPEGAWAIAN:
        //     return IzinKaryawan::with('user')
        //         ->where(function ($query) use ($unitKepegawaianId) {
        //             $query->where('status_izin_id', 4)
        //                 ->orWhere(function ($q) use ($unitKepegawaianId) {
        //                     $q->where('status_izin_id', 3)
        //                         ->whereHas('user', function ($subquery) use ($unitKepegawaianId) {
        //                             $subquery->where('unit_id', $unitKepegawaianId);
        //                         });
        //                 });
        //         })
        //         ->orderByDesc('id')
        //         ->paginate(10);
        // } else {
        //     // Selain KEPEGAWAIAN: hanya tampilkan berdasarkan unit_id user
        //     return IzinKaryawan::with('user')
        //         ->whereHas('user', function ($query) use ($user) {
        //             $query->where('unit_id', $user->unit_id);
        //         })
        //         ->orderByDesc('id')
        //         ->paginate(10);
        // }
    }

    // --- Helpers for riwayat/history mode ---
    private function getAllChildUnitIds($unitId)
    {
        $unitIds = [$unitId];
        $childs = \App\Models\UnitKerja::where('parent_id', $unitId)->pluck('id')->toArray();
        foreach ($childs as $childId) {
            $unitIds = array_merge($unitIds, $this->getAllChildUnitIds($childId));
        }
        return $unitIds;
    }

    public function updatedSelectedUserAktif() { $this->resetPage('usersPage'); }
    public function updatedSelectedUnit() { $this->resetPage('usersPage'); }
    public function updatedSelectedJenisKaryawan() { $this->resetPage('usersPage'); }
    public function updatedBulan() { $this->tanggalMulaiFilter = ''; $this->tanggalSelesaiFilter = ''; $this->resetPage('usersPage'); }
    public function updatedTahun() { $this->tanggalMulaiFilter = ''; $this->tanggalSelesaiFilter = ''; $this->resetPage('usersPage'); }
    public function updatedTanggalMulaiFilter() { $this->resetPage('usersPage'); $this->resetPage('detailsPage'); }
    public function updatedTanggalSelesaiFilter() { $this->resetPage('usersPage'); $this->resetPage('detailsPage'); }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage('usersPage');
    }
    private function applyFiltersToUserQuery($query)
    {
        $isUserQuery = $query->getModel() instanceof User;
        if (!empty($this->search)) {
            if ($isUserQuery) {
                $query->where('name', 'like', '%' . $this->search . '%');
            } else {
                $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            }
        }
        if ($this->selectedUnit) {
            if ($isUserQuery) {
                $query->where('unit_id', $this->selectedUnit);
            } else {
                $query->whereHas('user', fn($q) => $q->where('unit_id', $this->selectedUnit));
            }
        }
        if ($this->selectedJenisKaryawan) {
            if ($isUserQuery) {
                $query->where('jenis_id', $this->selectedJenisKaryawan);
            } else {
                $query->whereHas('user', fn($q) => $q->where('jenis_id', $this->selectedJenisKaryawan));
            }
        }
        if (isset($this->selectedUserAktif)) {
            if ($isUserQuery) {
                $query->where('status_karyawan', $this->selectedUserAktif);
            } else {
                $query->whereHas('user', fn($q) => $q->where('status_karyawan', $this->selectedUserAktif));
            }
        }
        return $query;
    }

    public function loadUsers()
    {
        $user = auth()->user();
        $query = User::with(['kategorijabatan', 'unitKerja'])
            ->where('id', '!=', $user->id)
            // Only users who have izin records matching filters
            ->whereHas('izinKaryawan', function ($q) {
                if ($this->tanggalMulaiFilter) {
                    $q->where('tanggal_mulai', '>=', $this->tanggalMulaiFilter);
                }
                if ($this->tanggalSelesaiFilter) {
                    $q->where('tanggal_selesai', '<=', $this->tanggalSelesaiFilter);
                }
                if (empty($this->tanggalMulaiFilter) && empty($this->tanggalSelesaiFilter) && $this->bulan && $this->tahun) {
                    $q->whereYear('tanggal_mulai', $this->tahun)->whereMonth('tanggal_mulai', $this->bulan);
                }
            });

        $query = $this->applyFiltersToUserQuery($query);

        if (!$this->isKepegawaian) {
            $hasChild = \App\Models\UnitKerja::where('parent_id', $user->unit_id)->exists();
            $unitIds = $hasChild ? $this->getAllChildUnitIds($user->unit_id) : [$user->unit_id];
            $query->whereIn('unit_id', $unitIds);
        }

        return $query->orderBy('name', 'asc')->paginate(5, ['*'], 'usersPage');
    }

    public function loadIzin()
    {
        if (!$this->selectedRiwayatUserId) return null;

        $query = IzinKaryawan::with(['jenisIzin', 'statusIzin'])->where('user_id', $this->selectedRiwayatUserId);

        if ($this->tanggalMulaiFilter) {
            $query->where('tanggal_mulai', '>=', $this->tanggalMulaiFilter);
        }
        if ($this->tanggalSelesaiFilter) {
            $query->where('tanggal_selesai', '<=', $this->tanggalSelesaiFilter);
        }

        return $query->orderBy('tanggal_mulai', 'desc')->paginate(10, ['*'], 'detailsPage');
    }

    public function selectRiwayatUser($userId, $userName)
    {
        $this->selectedRiwayatUserId = $userId;
        $this->selectedRiwayatUserName = $userName;
        $this->resetPage('detailsPage');
    }

    public function closeRiwayatUser()
    {
        $this->selectedRiwayatUserId = null;
        $this->selectedRiwayatUserName = '';
    }

    public function approveIzin($izinId, $userId)
    {
        $unitKepegawaianId = 87;
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)
            ->permission('approval-izin') // ✅ Spatie helper method
            ->get();
        $izin = IzinKaryawan::find($izinId);
        $user = auth()->user();
        $targetUser = User::findOrFail($userId);
        if ($izin) {
            if ($this->isKepegawaian) {
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
                    '</span> ' . '  dengan keterangan "' . $izin->keterangan . '"  telah <span class="text-success-600 font-bold">Disetujui Final</span> oleh ' . $user->name;


                $url = "/pengajuan/ijin";
                if ($nextUser) {
                    Notification::send($nextUser, new UserNotification($message, $url));
                }
                return redirect()->route('approvalizin.index')->with('success', 'Pengajuan Izin disetujui Final.');
            } else {
                // Kalau unit selain kepegawaian, hanya setujui kepala unit
                $izin->update(['status_izin_id' => 4]);

                $nextUser = User::where('id', $userId)->first();
                $message = 'Pengajuan Ijin anda (' . $nextUser->name .
                    ') telah <span class="text-success-600 font-bold">Disetujui Kepala Unit</span> oleh ' . $user->name;
                $messagekepegawaian = 'Pengajuan Ijin atas nama (' . $nextUser->name .
                    ') telah <span class="text-success-600 font-bold">Disetujui Kepala Unit</span> oleh ' . $user->name . ', silahkan melanjutkan persetujuan ';

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
        $unitKepegawaianId = 87;
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

    /** ----------------------------------------------------------------
     *  download
     *  ---------------------------------------------------------------- */
    public function export($param)
    {
        $bulan = $param['bulan'];
        $tahun = $param['tahun'];
        $unitId = $param['unitId'];
        $unit = $param['unit'];
        $jenisId = (int) $param['jenis'];
        $keyword = $param['keyword'];
        $mode = $param['mode'];
        $selected = Str::slug($param['selected']);

        $monthName = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM');
        if ($mode === 'user') {
            $filename = "riwayat_izin_{$selected}_{$monthName}_{$tahun}.xlsx";
        } elseif ($unitId && $mode !== 'user') {
            $filename = "riwayat_izin_{$unit}_{$monthName}_{$tahun}.xlsx";
        } elseif ($jenisId && $mode !== 'user') {
            $filename = "riwayat_izin_{$monthName}_{$tahun}.xlsx";
        } else {
            $filename = "riwayat_izin_{$monthName}_{$tahun}.xlsx";
        }

        // dd($filename);
        // dd($bulan, $tahun, $unit, $unitId, $jenisId, $keyword, $mode, $selected, $filename);
        // dd($this->selectedJenisKaryawan);

        return Excel::download(
            new ExportRiwayat($bulan, $tahun, $unit, $unitId, $jenisId, $keyword, $mode, $selected, 'izin'),
            $filename
        );
    }

    public function render()
    {
        if (!$this->isRiwayatIzin) {
            $users = $this->loadData();
            return view('livewire.data-izin', [
                'userIzin' => $users,
                'isKepegawaian' => $this->isKepegawaian,
                'isRiwayatIzin' => $this->isRiwayatIzin,
                'jenisKaryawans' => $this->jenisKaryawan,
            ]);
        }

        // Riwayat mode
        $usersList = $this->loadUsers();
        $riwayatIzinDetail = $this->loadIzin();
        return view('livewire.data-izin', [
            'users' => $usersList,
            'riwayatIzinDetail' => $riwayatIzinDetail,
            'jenisKaryawans' => $this->jenisKaryawan,
            'isKepegawaian' => $this->isKepegawaian,
            'isRiwayatIzin' => $this->isRiwayatIzin,
            'selectedRiwayatUserId' => $this->selectedRiwayatUserId,
            'selectedRiwayatUserName' => $this->selectedRiwayatUserName,
        ]);
    }
}
