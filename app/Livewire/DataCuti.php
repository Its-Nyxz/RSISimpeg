<?php

namespace App\Livewire;

use App\Exports\ExportRiwayat;
use App\Models\Absen;
use App\Models\CutiKaryawan;
use App\Models\JadwalAbsensi;
use App\Models\JenisKaryawan;
use App\Models\MasterJatahCuti;
use App\Models\RiwayatApproval;
use App\Models\Shift;
use App\Models\SisaCutiTahunan;
use App\Models\StatusAbsen;
use App\Models\StatusCuti;
use App\Models\UnitKerja;
use App\Models\User;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

    public $selectedJenisKaryawan = '';

    public $search = '';

    // Options for dropdowns
    public $units = [];

    public $jenisKaryawans = [];

    public $bulan;

    public $tahun;

    public $tanggalMulaiFilter = '';

    public $tanggalSelesaiFilter = '';

    public function mount()
    {
        $user = auth()->user();
        $this->unitKepegawaianId = 87;
        $this->isKepegawaian = $user->unit_id == $this->unitKepegawaianId || $user->roles->pluck('id')->first() == 2 || $user->roles->pluck('id')->first() == 14 || $user->hasRole('Super Admin');
        $this->isRiwayatCuti = request()->routeIs('riwayatcuti.*') || request()->is('riwayatcuti');
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->tanggalMulaiFilter = now()->format('Y-m-d');
        $this->units = UnitKerja::orderBy('id')->get();
        $this->jenisKaryawans = JenisKaryawan::orderBy('id')->get();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage('usersPage');
    }

    public function updatedSelectedUserAktif()
    {
        $this->resetPage('usersPage');
    }

    public function updatedSelectedUnit()
    {
        $this->resetPage('usersPage');
    }

    public function updatedSelectedJenisKaryawan()
    {
        $this->resetPage('usersPage');
    }

    public function updatedBulan()
    {
        $this->tanggalMulaiFilter = '';
        $this->tanggalSelesaiFilter = '';
        $this->resetPage('usersPage');
    }

    public function updatedTahun()
    {
        $this->tanggalMulaiFilter = '';
        $this->tanggalSelesaiFilter = '';
        $this->resetPage('usersPage');
    }

    public function updatedTanggalMulai()
    {
        $this->resetPage();
        $this->resetPage('usersPage');
        $this->resetPage('detailsPage');
    }

    public function updatedTanggalSelesaiFilter()
    {
        $this->resetPage();
        $this->resetPage('usersPage');
        $this->resetPage('detailsPage');
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

    /** ----------------------------------------------------------------
     * Load Data (for approval cuti - status 3,4)
     * ---------------------------------------------------------------- */
    public function loadData()
    {
        $user = auth()->user();

        if (! $user->can('approval-cuti')) {
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
            $query->where('name', 'like', '%'.$this->search.'%');
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

        if ($this->tanggalMulaiFilter || $this->tanggalSelesaiFilter) {
            $query->whereHas('cutiKaryawan', function ($q) {
                if ($this->tanggalMulaiFilter) {
                    $q->where('tanggal_mulai', '>=', $this->tanggalMulaiFilter)
                        ->whereMonth('tanggal_mulai', '=', $this->bulan)
                        ->whereYear('tanggal_mulai', '=', $this->tahun);
                }
                if ($this->tanggalSelesaiFilter) {
                    $q->where('tanggal_selesai', '<=', $this->tanggalSelesaiFilter)
                        ->whereMonth('tanggal_selesai', '=', $this->bulan)
                        ->whereYear('tanggal_selesai', '=', $this->tahun);
                }
                if ($this->tanggalMulaiFilter && $this->tanggalSelesaiFilter) {
                    $q->where('tanggal_mulai', '>=', $this->tanggalMulaiFilter)
                        ->where('tanggal_selesai', '<=', $this->tanggalSelesaiFilter);
                }
            });
        } elseif ($this->bulan && $this->tahun) {
            $query->whereHas('cutiKaryawan', fn ($q) => $q->whereYear('tanggal_mulai', $this->tahun)->whereMonth('tanggal_mulai', $this->bulan));
        }

        if (! $this->isKepegawaian) {
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
        if (! $this->selectedRiwayatUserId) {
            return null;
        }

        $query = CutiKaryawan::with(['jenisCuti', 'statusCuti'])
            ->where('user_id', $this->selectedRiwayatUserId);

        if ($this->tanggalMulaiFilter) {
            $query->where('tanggal_mulai', '>=', $this->tanggalMulaiFilter)
                ->whereMonth('tanggal_mulai', '=', $this->bulan)
                ->whereYear('tanggal_mulai', '=', $this->tahun);
        }

        if ($this->tanggalSelesaiFilter) {
            $query->where('tanggal_selesai', '<=', $this->tanggalSelesaiFilter)
                ->whereMonth('tanggal_selesai', '=', $this->bulan)
                ->whereYear('tanggal_selesai', '=', $this->tahun);
        }

        if ($this->tanggalMulaiFilter && $this->tanggalSelesaiFilter) {
            $query->where('tanggal_mulai', '>=', $this->tanggalMulaiFilter)
                ->where('tanggal_selesai', '<=', $this->tanggalSelesaiFilter);
        }

        if (empty($this->tanggalMulaiFilter) && empty($this->tanggalSelesaiFilter)) {
            $query->whereYear('tanggal_mulai', '=', $this->tahun)
                ->whereMonth('tanggal_mulai', '=', $this->bulan)
                ->when($this->bulan !== now()->month, function ($q) {
                    $q->whereMonth('tanggal_mulai', '=', $this->bulan);
                });
        }

        return $query->orderBy('tanggal_mulai', 'desc')
            ->paginate(10, ['*'], 'detailsPage');
    }

    private function applyFiltersCuti($query)
    {
        if ($this->search) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'));
        }
        if ($this->selectedUnit) {
            $query->whereHas('user', fn ($q) => $q->where('unit_id', $this->selectedUnit));
        }
        if ($this->selectedJenisKaryawan) {
            $query->whereHas('user', fn ($q) => $q->where('jenis_id', $this->selectedJenisKaryawan));
        }
        if (isset($this->selectedUserAktif)) {
            $query->whereHas('user', fn ($q) => $q->where('status_karyawan', $this->selectedUserAktif));
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
                $q->where('name', 'like', '%'.$this->search.'%');
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
     *  Potong saldo cuti tahunan atau tambahan, termasuk sistem kasbon kuota tahun depan
     *  jika jatah tahun berjalan sudah habis / kurang.
     *  ---------------------------------------------------------------- */
    private function potongSaldoCuti($cuti, $targetUser)
    {
        $tahunCuti = Carbon::parse($cuti->tanggal_mulai)->year;
        $tahunDepan = $tahunCuti + 1;

        // 1. Cek Cuti Tambahan
        if ($cuti->kategori_cuti == 'tambahan') {
            $userCuti = SisaCutiTahunan::firstOrCreate(
                ['user_id' => $cuti->user_id, 'tahun' => $tahunCuti],
                ['sisa_cuti' => MasterJatahCuti::where('tahun', $tahunCuti)->value('jumlah_cuti') ?? 12]
            );

            if ($userCuti->cuti_tambahan >= $cuti->jumlah_hari) {
                $userCuti->decrement('cuti_tambahan', $cuti->jumlah_hari);

                return ['success' => true, 'pesan' => ''];
            }

            return [
                'success' => false,
                'message' => 'Jatah Cuti Tambahan tidak cukup, pengajuan otomatis ditolak.',
            ];
        }

        // 2. Cek Cuti Tahunan (dengan sistem kasbon tahun depan jika kurang/habis)
        $jatahTahunIni = MasterJatahCuti::where('tahun', $tahunCuti)->value('jumlah_cuti') ?? 12;
        $userCuti = SisaCutiTahunan::firstOrCreate(
            ['user_id' => $cuti->user_id, 'tahun' => $tahunCuti],
            ['sisa_cuti' => $jatahTahunIni]
        );

        $kebutuhan = $cuti->jumlah_hari;
        $saldoTahunIni = $userCuti->sisa_cuti;

        // Skenario 1: Saldo tahun berjalan mencukupi
        if ($saldoTahunIni >= $kebutuhan) {
            $userCuti->decrement('sisa_cuti', $kebutuhan);

            return ['success' => true, 'pesan' => ''];
        }

        // Skenario 2: Saldo tahun berjalan habis (0) atau kurang -> Pinjam / Kasbon jatah tahun depan
        $dariTahunIni = max(0, $saldoTahunIni);
        $kekurangan = $kebutuhan - $dariTahunIni;

        $jatahTahunDepan = MasterJatahCuti::where('tahun', $tahunDepan)->value('jumlah_cuti') ?? 12;
        $userCutiTahunDepan = SisaCutiTahunan::firstOrCreate(
            ['user_id' => $cuti->user_id, 'tahun' => $tahunDepan],
            ['sisa_cuti' => $jatahTahunDepan]
        );

        if ($userCutiTahunDepan->sisa_cuti >= $kekurangan) {
            if ($dariTahunIni > 0) {
                $userCuti->decrement('sisa_cuti', $dariTahunIni);
            }
            $userCutiTahunDepan->decrement('sisa_cuti', $kekurangan);

            $pesanKasbon = " ({$kekurangan} hari diambil dari kuota cuti tahun depan {$tahunDepan})";

            return [
                'success' => true,
                'pesan' => $pesanKasbon,
                'kasbon' => true,
                'hari_kasbon' => $kekurangan,
                'tahun_depan' => $tahunDepan,
            ];
        }

        // Skenario 3: Kuota tahun ini + kuota tahun depan tidak cukup
        return [
            'success' => false,
            'message' => "Jatah Cuti Tahunan {$tahunCuti} dan jatah tahun depan ({$tahunDepan}) tidak cukup, pengajuan otomatis ditolak.",
        ];
    }

    /** ----------------------------------------------------------------
     *  Approve Cuti
     *  ---------------------------------------------------------------- */
    public function approveCuti($cutiId, $userId)
    {
        $user = auth()->user();

        if (! $user->can('approval-cuti')) {
            return redirect()->route('approvalcuti.index')
                ->with('error', 'Anda tidak memiliki izin untuk menyetujui cuti.');
        }

        $cuti = CutiKaryawan::find($cutiId);
        $targetUser = User::findOrFail($userId);

        if (! $cuti) {
            return redirect()->route('approvalcuti.index')
                ->with('error', 'Pengajuan cuti tidak ditemukan.');
        }

        $pesanTambahan = '';

        /** ------------------------------------------------------------
         *  Jika user adalah Wadir/Direktur dan cuti dari Manager → Final
         *  ------------------------------------------------------------ */
        if (in_array($user->jabatan_id, [1, 2]) && $cuti->user->jabatan_id == 3) {
            $cutiStatus = 1; // Disetujui final

            // 🔥 Validasi sisa jatah cuti (Tahunan atau Tambahan)
            if ($cuti->jenisCuti && strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan') {
                $potong = $this->potongSaldoCuti($cuti, $targetUser);

                if (! $potong['success']) {
                    $cuti->update(['status_cuti_id' => 2]);
                    Notification::send($targetUser, new UserNotification(
                        $potong['message'],
                        '/pengajuan/cuti'
                    ));

                    return redirect()->route('approvalcuti.index')
                        ->with('error', $potong['message']);
                }

                $pesanTambahan = $potong['pesan'] ?? '';
            }

            // ✅ Final approval
            $cuti->update(['status_cuti_id' => $cutiStatus]);
            $this->applyCutiToJadwal($cuti, $targetUser);
            $pesan = "Pengajuan cuti Anda telah <span class='text-success-600 font-bold'>Disetujui Final</span> oleh {$user->name} ({$user->jabatan->nama}){$pesanTambahan}.";
            Notification::send($targetUser, new UserNotification($pesan, '/pengajuan/cuti'));

            RiwayatApproval::create([
                'cuti_id' => $cutiId,
                'approver_id' => $user->id,
                'status_approval' => 'disetujui_final_wadir_direktur',
                'approve_at' => now(),
                'catatan' => null,
            ]);

            return redirect()->route('approvalcuti.index')
                ->with('success', "Cuti Manager telah disetujui final oleh Wadir/Direktur.{$pesanTambahan}");
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

        // 🔥 Validasi jatah cuti (Tahunan atau Tambahan) jika final
        if (
            $cutiStatus == 1 &&
            $cuti->jenisCuti &&
            strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan'
        ) {

            // dd($targetUser->jenis_id != 1);
            if ($targetUser->jenis_id != 1 || empty($targetUser->jenis_id)) {
                // (Logika pengecekan TMT karyawan kontrak tetap sama seperti sebelumnya)
                if (empty($targetUser->jenis_id) && empty($targetUser->tmt_masuk) && empty($targetUser->tmt)) {
                    return redirect()->route('approvalcuti.index')
                        ->with('error', 'Data jenis Karyawan & TMT belum terisi');
                }
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

                $now = now('Asia/Jakarta');
                $tmt = Carbon::parse($targetUser->tmt, 'Asia/Jakarta');
                $targetTanggal = $tmt->copy()->addMonths(12);

                if ($targetUser->jenis_id === 3) {
                    if (! $now->greaterThanOrEqualTo($targetTanggal)) {
                        $sisaBulan = ceil($now->floatDiffInMonths($targetTanggal));

                        return redirect()->route('approvalcuti.index')
                            ->with('error', "Maaf, karyawan kontrak baru bisa cuti setelah 12 bulan. Sisa masa tunggu: $sisaBulan bulan lagi.");
                    }
                }
            }

            $potong = $this->potongSaldoCuti($cuti, $targetUser);

            if (! $potong['success']) {
                $cuti->update(['status_cuti_id' => 2]);
                Notification::send($targetUser, new UserNotification(
                    $potong['message'],
                    '/pengajuan/cuti'
                ));

                return redirect()->route('approvalcuti.index')
                    ->with('error', $potong['message']);
            }

            $pesanTambahan = $potong['pesan'] ?? '';
        }

        // 🔹 Update status cuti
        $cuti->update(['status_cuti_id' => $cutiStatus]);

        // 🔹 Jika final → buat jadwal absen otomatis
        if ($cutiStatus == 1) {
            $this->applyCutiToJadwal($cuti, $targetUser);
        }

        // 🔔 Notifikasi ke pemohon
        $statusText = $isFinalApprover ? 'Disetujui Final' : 'Disetujui (Menunggu Final)';
        $message = "Pengajuan cuti anda telah <span class='text-success-600 font-bold'>{$statusText}</span> oleh {$user->name}{$pesanTambahan}";
        Notification::send($targetUser, new UserNotification($message, '/pengajuan/cuti'));

        // 🔔 Jika belum final → notifikasi ke Kepegawaian
        if (! $isFinalApprover) {
            $finalApprovers = User::where('unit_id', $this->unitKepegawaianId)
                ->orWhereHas('roles', fn ($q) => $q->where('id', 2))
                ->get();

            if ($finalApprovers->count() > 0) {
                $msg = "Pengajuan cuti atas nama {$targetUser->name} telah disetujui oleh {$user->name}, menunggu persetujuan final Kepegawaian.";
                Notification::send($finalApprovers, new UserNotification($msg, '/approvalcuti'));
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

            $message = 'Pengajuan Cuti anda ('.$targetUser->name.') mulai '.
                '<span class="font-bold">'.$cuti->tanggal_mulai.' sampai '.$cuti->tanggal_selesai.
                '</span> dengan keterangan "'.$cuti->keterangan.
                '" telah <span class="text-red-600 font-bold">Ditolak</span> oleh '.$user->name.
                '. Alasan: "'.$reason.'"';

            Notification::send($targetUser, new UserNotification($message, '/pengajuan/cuti'));

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
     *  Batalkan Cuti (Bisa dilakukan oleh Super Admin / Atasan walau sudah disetujui)
     *  ---------------------------------------------------------------- */
    public function batalkanCuti($cutiId, $reason = null)
    {
        $user = auth()->user();

        if (! $user->can('approval-cuti') && ! $user->hasRole('Super Admin')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk membatalkan cuti.');
            return;
        }

        $cuti = CutiKaryawan::with(['jenisCuti', 'user'])->find($cutiId);
        if (! $cuti) {
            session()->flash('error', 'Pengajuan cuti tidak ditemukan.');
            return;
        }

        $targetUser = $cuti->user;
        if (! $targetUser) {
            session()->flash('error', 'Data karyawan pemilik cuti tidak ditemukan.');
            return;
        }

        // Cek unit bawahan jika bukan Kepegawaian / Super Admin
        if (! $this->isKepegawaian) {
            $hasChild = UnitKerja::where('parent_id', $user->unit_id)->exists();
            $unitIds = $hasChild ? $this->getAllChildUnitIds($user->unit_id) : [$user->unit_id];
            if (! in_array($targetUser->unit_id, $unitIds)) {
                session()->flash('error', 'Anda hanya memiliki izin membatalkan cuti karyawan pada unit kerja Anda.');
                return;
            }
        }

        try {
            DB::transaction(function () use ($cuti, $targetUser, $user, $reason) {
                $statusBatal = StatusCuti::firstOrCreate(['nama_status' => 'Dibatalkan']);
                $sudahDisetujui = ($cuti->status_cuti_id == 1);

                // 1. Pulihkan saldo kuota cuti jika sebelumnya berstatus Disetujui
                if ($sudahDisetujui && $cuti->jenisCuti && strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan') {
                    $tahun = Carbon::parse($cuti->tanggal_mulai)->year;

                    if ($cuti->kategori_cuti === 'tambahan') {
                        $sisaCuti = SisaCutiTahunan::where('user_id', $cuti->user_id)
                            ->where('tahun', $tahun)
                            ->lockForUpdate()
                            ->first();

                        if ($sisaCuti) {
                            $sisaCuti->increment('cuti_tambahan', $cuti->jumlah_hari);
                        }
                    } else {
                        // Cuti Tahunan: Cek apakah ada kasbon kuota dari tahun depan
                        $tahunDepan = $tahun + 1;
                        $sisaDepan = SisaCutiTahunan::where('user_id', $cuti->user_id)
                            ->where('tahun', $tahunDepan)
                            ->lockForUpdate()
                            ->first();

                        $jatahDepan = MasterJatahCuti::where('tahun', $tahunDepan)->value('jumlah_cuti') ?? 12;
                        $jumlahBalik = $cuti->jumlah_hari;

                        if ($sisaDepan && $sisaDepan->sisa_cuti < $jatahDepan) {
                            $defisit = $jatahDepan - $sisaDepan->sisa_cuti;
                            $kembaliDepan = min($jumlahBalik, $defisit);
                            $sisaDepan->increment('sisa_cuti', $kembaliDepan);
                            $jumlahBalik -= $kembaliDepan;
                        }

                        if ($jumlahBalik > 0) {
                            $sisaCuti = SisaCutiTahunan::where('user_id', $cuti->user_id)
                                ->where('tahun', $tahun)
                                ->lockForUpdate()
                                ->first();

                            if ($sisaCuti) {
                                $sisaCuti->increment('sisa_cuti', $jumlahBalik);
                            }
                        }
                    }
                }

                // 2. Bersihkan Jadwal Absensi dan Absen cuti jika sebelumnya sudah disetujui
                if ($sudahDisetujui) {
                    $start = Carbon::parse($cuti->tanggal_mulai);
                    $end = Carbon::parse($cuti->tanggal_selesai);

                    $shiftCutiIds = Shift::where(function ($q) {
                        $q->where('nama_shift', 'C')->orWhere('nama_shift', 'Cuti');
                    })->pluck('id')->toArray();

                    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                        $tglStr = $date->toDateString();

                        $jadwal = JadwalAbsensi::where('user_id', $targetUser->id)
                            ->where('tanggal_jadwal', $tglStr)
                            ->first();

                        if ($jadwal) {
                            // Hapus absen cuti
                            Absen::where('user_id', $targetUser->id)
                                ->where('jadwal_id', $jadwal->id)
                                ->where(function ($q) {
                                    $q->where('keterangan', 'like', '%cuti%')
                                        ->orWhere('deskripsi_in', 'like', '%cuti%');
                                })
                                ->delete();

                            // Hapus jadwal shift cuti agar bisa dibuat jadwal baru
                            if (in_array($jadwal->shift_id, $shiftCutiIds)) {
                                $jadwal->delete();
                            }
                        }
                    }
                }

                // 3. Update status cuti ke Dibatalkan
                $cuti->update(['status_cuti_id' => $statusBatal->id]);

                // 4. Catat ke Riwayat Approval
                $alasanText = $reason ? trim($reason) : 'Dibatalkan karena ada urgensi pekerjaan';
                RiwayatApproval::create([
                    'cuti_id' => $cuti->id,
                    'approver_id' => $user->id,
                    'status_approval' => 'dibatalkan',
                    'catatan' => $alasanText,
                    'approve_at' => now(),
                ]);

                // 5. Kirim notifikasi ke karyawan pemohon
                $message = "Pengajuan cuti Anda ({$cuti->tanggal_mulai} s.d. {$cuti->tanggal_selesai}) telah <span class='text-red-600 font-bold'>DIBATALKAN</span> oleh {$user->name}. Alasan: \"{$alasanText}\". Kuota cuti telah dikembalikan dan jadwal shift telah direset.";
                Notification::send($targetUser, new UserNotification($message, '/pengajuan/cuti'));
            });

            $redirectRoute = $this->isRiwayatCuti ? 'riwayatcuti.index' : 'approvalcuti.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Pengajuan cuti berhasil dibatalkan. Kuota cuti telah dikembalikan dan jadwal cuti telah direset.');
        } catch (\Throwable $th) {
            $redirectRoute = $this->isRiwayatCuti ? 'riwayatcuti.index' : 'approvalcuti.index';
            return redirect()->route($redirectRoute)
                ->with('error', 'Terjadi kesalahan saat membatalkan cuti: ' . $th->getMessage());
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
            $filename = "riwayat_cuti_{$selected}_{$monthName}_{$tahun}.xlsx";
        } elseif ($unitId && $mode !== 'user') {
            $filename = "riwayat_cuti_{$unit}_{$monthName}_{$tahun}.xlsx";
        } elseif ($jenisId && $mode !== 'user') {
            $filename = "riwayat_cuti_{$monthName}_{$tahun}.xlsx";
        } else {
            $filename = "riwayat_cuti_{$monthName}_{$tahun}.xlsx";
        }

        // dd($param, $filename);

        return Excel::download(
            new ExportRiwayat($bulan, $tahun, $unit, $unitId, $jenisId, $keyword, $mode, $selected, 'cuti'),
            $filename
        );
    }

    public function applyCutiToJadwal(CutiKaryawan $cuti, User $targetUser)
    {
        $shift = Shift::firstOrCreate(
            [
                'unit_id' => $targetUser->unit_id,
                'nama_shift' => 'C',
            ],
            [
                'jam_masuk' => null,
                'jam_keluar' => null,
                'keterangan' => 'Cuti',
            ]
        );

        $start = Carbon::parse($cuti->tanggal_mulai);
        $end = Carbon::parse($cuti->tanggal_selesai);

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $jadwal = JadwalAbsensi::updateOrCreate(
                ['user_id' => $targetUser->id, 'tanggal_jadwal' => $date->toDateString()],
                ['shift_id' => $shift->id]
            );

            $cutiStatusId = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
            Absen::updateOrCreate(
                [
                    'user_id' => $targetUser->id,
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

    /** ----------------------------------------------------------------
     *  Render
     *  ---------------------------------------------------------------- */
    public function render()
    {
        $cutiData = null;
        $riwayatUsers = null;
        $riwayatCutiDetail = null;

        if (! $this->isRiwayatCuti) {
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
