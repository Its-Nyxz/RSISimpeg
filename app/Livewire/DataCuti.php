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
    public $unitKepegawaianId;

    /** ----------------------------------------------------------------
     *  Initialization
     *  ---------------------------------------------------------------- */
    public function mount()
    {
        $user = auth()->user();

        // Ambil ID unit KEPEGAWAIAN sekali saja
        $this->unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');

        // Tandai apakah user berasal dari unit KEPEGAWAIAN
        $this->isKepegawaian = $user->unit_id == $this->unitKepegawaianId;
    }

    /** Rekursif ambil semua child unit */
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
     *  Load Data
     *  ---------------------------------------------------------------- */
    public function loadData()
    {
        $user = auth()->user();

        // ✅ Pastikan user punya izin approval cuti
        if (!$user->can('approval-cuti')) {
            logger("User {$user->name} tidak punya izin approval-cuti");
            return CutiKaryawan::whereNull('id')->paginate(10);
        }

        $query = CutiKaryawan::with(['user.unitkerja', 'jenisCuti', 'statusCuti'])
            ->whereIn('status_cuti_id', [3, 4]) // 3 = Menunggu, 4 = Disetujui sementara
            ->where('user_id', '!=', $user->id);

        // 🔹 Jika dari KEPEGAWAIAN → lihat semua data
        if ($this->isKepegawaian) {
            logger("User {$user->name} dari KEPEGAWAIAN, lihat semua data");
            return $query->orderByDesc('id')->paginate(10);
        }

        // 🔹 Cek apakah unit user punya child (berarti dia atasan)
        $hasChild = UnitKerja::where('parent_id', $user->unit_id)->exists();
        $unitIds = $hasChild
            ? $this->getAllChildUnitIds($user->unit_id)
            : [$user->unit_id];

        logger("User {$user->name} melihat cuti untuk unit: " . json_encode($unitIds));

        $result = $query->whereHas('user', function ($q) use ($unitIds) {
            $q->whereIn('unit_id', $unitIds);
        })->orderByDesc('id')->paginate(10);

        logger("Total data cuti tampil untuk {$user->name}: " . $result->total());

        return $result;
    }




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


        // 🔹 Tentukan apakah user adalah final approver (unit KEPEGAWAIAN)
        $userRole = $user->roles->first()->name ?? '';
        $isFinalApprover = (
            strcasecmp($userRole, 'Kepala Seksi Kepegawaian') === 0 ||
            $user->unit_id == $this->unitKepegawaianId
        );

        $cutiStatus = $isFinalApprover ? 1 : 4; // 1 = Final, 4 = Menunggu Final

        // 🔥 Validasi sisa cuti tahunan jika final
        if (
            $cutiStatus == 1 &&
            $cuti->jenisCuti &&
            strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan'
        ) {
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
            $end   = Carbon::parse($cuti->tanggal_selesai);

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
                ->orWhereHas('roles', fn($q) => $q->where('name', 'Kepala Seksi Kepegawaian'))
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
        $cutiData = $this->loadData();

        return view('livewire.data-cuti', [
            'users' => $cutiData,
            'isKepegawaian' => $this->isKepegawaian,
        ]);
    }
}
