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
    // public $search = '';
    public $isKepegawaian = false;

    public function mount()
    {
        $this->loadData();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $user = auth()->user();

        $this->isKepegawaian = $user->unit_id == $unitKepegawaianId;
    }

    private function getNextApprover($currentJabatan)
    {
        // Mapping specific staff positions to their respective heads
        $staffToHead = [
            // Staf to Ka. Unit mapping
            'Staf Humas dan Program RS' => 'Ka. Unit Pemasaran',
            'Staf SDM' => 'Ka. Seksi',
            'Staf Akuntansi' => 'Ka. Seksi',
            'Staf Keuangan' => 'Ka. Seksi',
            'Staf Asuransi' => 'Ka. Seksi',
            'Staf Aset dan Logistik' => 'Ka. Seksi',
            'Staf Pelayanan Medik' => 'Ka. Seksi',
            'Staf Keperawatan' => 'Ka. Seksi',
            'Staf Penunjang' => 'Ka. Seksi',
            'Staf Unit Pemasaran' => 'Ka. Unit Pemasaran',
            'Staf Anggota SPI' => 'Ketua SPI',
            
            // Installation staff to Installation Head mapping
            'Staf Instalasi Teknologi Informasi' => 'Ka. Instalasi Teknologi Informasi',
            'Staf Administrasi Inst Laboratorium' => 'Ka. Instalasi CSSD',
            'Staf Administrasi IBS' => 'Ka. Instalasi CSSD',
            'Staf Administrasi IRJ' => 'Ka. Instalasi CSSD',
            'Staf Instalasi Rekam Medik' => 'Ka. Instalasi CSSD',
            'Staf Instalasi Gas Medik' => 'Ka. Instalasi Pemeliharaan Sarpras',
            'Staf Unit Ambulance' => 'Ka. Unit Ambulance',
            'Staf Instalasi Gizi' => 'Ka. Instalasi CSSD',
            'Staf Unit Pengamanan' => 'Ka. Unit Pengamanan',
            'Staf Unit Transportasi' => 'Ka. Unit Transportasi',
            'Staf Unit Gudang' => 'Ka. Unit Gudang',
            'Staf Unit Pengelolaan Linen' => 'Ka. Unit Pengelolaan Linen',
            'Staf Instalasi Peml. Sarpras' => 'Ka. Instalasi Pemeliharaan Sarpras',
            'Staf Unit MCU dan Poskes' => 'Ka. Unit PJBR',
            'Staf Instalasi CSSD' => 'Ka. Instalasi CSSD',
            'Staf Unit PJBR' => 'Ka. Unit PJBR',
        ];

        // Main approval hierarchy
        $mainHierarchy = [
            'Ka. Unit' => 'Ka. Seksi',
            'Ka. Instalasi' => 'Ka. Seksi',
            'Ka. Seksi' => 'Manajer',
            'Manajer' => 'Wadir',
            'Wadir' => 'Direktur',
            'Direktur' => 'KEPEGAWAIAN',
            
            // Unit heads
            'Ka. Unit Ambulance' => 'Ka. Seksi',
            'Ka. Unit PJBR' => 'Ka. Seksi',
            'Ka. Unit Transportasi' => 'Ka. Seksi',
            'Ka. Unit Pengelolaan Linen' => 'Ka. Seksi',
            'Ka. Unit Gudang' => 'Ka. Seksi',
            'Ka. Unit Pengamanan' => 'Ka. Seksi',
            'Ka. Unit Pemasaran' => 'Ka. Seksi',
            
            // Installation heads
            'Ka. Instalasi CSSD' => 'Ka. Seksi',
            'Ka. Instalasi Pemeliharaan Sarpras' => 'Ka. Seksi',
            'Ka. Instalasi Teknologi Informasi' => 'Ka. Seksi',
            
            // Special positions
            'Supervisor' => 'Ka. Seksi',
            'Ketua SPI' => 'Direktur',
        ];

        // First check if it's a staff position
        if (isset($staffToHead[$currentJabatan])) {
            return $staffToHead[$currentJabatan];
        }

        // Then check main hierarchy
        return $mainHierarchy[$currentJabatan] ?? 'KEPEGAWAIAN';
    }

    public function loadData()
    {
        $user = auth()->user();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $userJabatan = $user->kategorijabatan->nama ?? '';

        if ($user->unit_id == $unitKepegawaianId) {
            // Kepegawaian sees all pending approvals
            return CutiKaryawan::with(['user', 'user.kategorijabatan'])
                ->where('status_cuti_id', 4)
                ->orderByDesc('id')
                ->paginate(10);
        } else {
            // Others only see what they can approve based on hierarchy
            return CutiKaryawan::with(['user', 'user.kategorijabatan'])
                ->where('status_cuti_id', 3)
                ->whereHas('user', function($query) use ($userJabatan) {
                    $query->whereHas('kategorijabatan', function($q) use ($userJabatan) {
                        // Get all positions that report to current user's position
                        $subordinatePositions = collect(array_filter(array_keys($this->getStaffToHeadMapping()), function($position) use ($userJabatan) {
                            return $this->getNextApprover($position) === $userJabatan;
                        }));
                        
                        $q->whereIn('nama', $subordinatePositions);
                    });
                })
                ->orderByDesc('id')
                ->paginate(10);
        }
    }

    private function getStaffToHeadMapping()
    {
        // Return the same mapping used in getNextApprover
        return [
            'Staf Humas dan Program RS' => 'Ka. Unit Pemasaran',
            'Staf SDM' => 'Ka. Seksi',
            'Staf Akuntansi' => 'Ka. Seksi',
            'Staf Keuangan' => 'Ka. Seksi',
            'Staf Asuransi' => 'Ka. Seksi',
            'Staf Aset dan Logistik' => 'Ka. Seksi',
            'Staf Pelayanan Medik' => 'Ka. Seksi',
            'Staf Keperawatan' => 'Ka. Seksi',
            'Staf Penunjang' => 'Ka. Seksi',
            'Staf Unit Pemasaran' => 'Ka. Unit Pemasaran',
            'Staf Anggota SPI' => 'Ketua SPI',
            
            // Installation staff to Installation Head mapping
            'Staf Instalasi Teknologi Informasi' => 'Ka. Instalasi Teknologi Informasi',
            'Staf Administrasi Inst Laboratorium' => 'Ka. Instalasi CSSD',
            'Staf Administrasi IBS' => 'Ka. Instalasi CSSD',
            'Staf Administrasi IRJ' => 'Ka. Instalasi CSSD',
            'Staf Instalasi Rekam Medik' => 'Ka. Instalasi CSSD',
            'Staf Instalasi Gas Medik' => 'Ka. Instalasi Pemeliharaan Sarpras',
            'Staf Unit Ambulance' => 'Ka. Unit Ambulance',
            'Staf Instalasi Gizi' => 'Ka. Instalasi CSSD',
            'Staf Unit Pengamanan' => 'Ka. Unit Pengamanan',
            'Staf Unit Transportasi' => 'Ka. Unit Transportasi',
            'Staf Unit Gudang' => 'Ka. Unit Gudang',
            'Staf Unit Pengelolaan Linen' => 'Ka. Unit Pengelolaan Linen',
            'Staf Instalasi Peml. Sarpras' => 'Ka. Instalasi Pemeliharaan Sarpras',
            'Staf Unit MCU dan Poskes' => 'Ka. Unit PJBR',
            'Staf Instalasi CSSD' => 'Ka. Instalasi CSSD',
            'Staf Unit PJBR' => 'Ka. Unit PJBR',
        ];
    }

    public function approveCuti($cutiId, $userId)
    {
        $cuti = CutiKaryawan::find($cutiId);
        $currentUser = auth()->user();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');

        if (!$cuti) return;

        // Prevent self-approval except for Kepegawaian
        if ($userId == $currentUser->id && $currentUser->unit_id != $unitKepegawaianId) {
            return redirect()->route('approvalcuti.index')
                ->with('error', 'Anda tidak dapat menyetujui cuti Anda sendiri!');
        }

        $requestingUser = User::find($userId);
        $currentJabatan = $requestingUser->kategorijabatan->nama ?? '';

        // If current user is from Kepegawaian
        if ($currentUser->unit_id == $unitKepegawaianId) {
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

        // Get next approver based on hierarchy
        $nextLevel = $this->getNextApprover($currentJabatan);
        
        // Update cuti status
        $cuti->update(['status_cuti_id' => 4]);

        // Find next approvers
        if ($nextLevel === 'KEPEGAWAIAN') {
            $nextApprovers = User::where('unit_id', $unitKepegawaianId)
                ->permission('approve-cuti')
                ->get();
        } else {
            $nextApprovers = User::whereHas('kategorijabatan', function($q) use ($nextLevel) {
                $q->where('nama', $nextLevel);
            })->get();
        }

        // Notify requesting user
        $message = 'Pengajuan Cuti anda (' . $requestingUser->name .
            ') telah <span class="text-success-600 font-bold">Disetujui oleh ' . 
            $currentUser->name . ' (' . $currentUser->kategorijabatan->nama . ')</span>';

        // Notify next approver
        $messageNext = 'Pengajuan Cuti dari ' . $requestingUser->name .
            ' memerlukan persetujuan Anda sebagai ' . $nextLevel;

        // Send notifications
        Notification::send($requestingUser, new UserNotification($message, '/pengajuan/cuti'));
        Notification::send($nextApprovers, new UserNotification($messageNext, '/approvalcuti'));

        // Record approval history
        RiwayatApproval::create([
            'cuti_id' => $cutiId,
            'approver_id' => $currentUser->id,
            'status_approval' => 'disetujui_' . Str::slug($currentUser->kategorijabatan->nama),
            'approve_at' => now(),
            'catatan' => null
        ]);

        return redirect()->route('approvalcuti.index')
            ->with('success', "Cuti disetujui dan diteruskan ke $nextLevel!");
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

            RiwayatApproval::create([
                'cuti_id' => $cutiId,
                'approver_id' => auth()->id(),
                'status_approval' => 'ditolak',
                'catatan' => $reason,
                'approve_at' => now(), // Changed from approved_at to approve_at
            ]);

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