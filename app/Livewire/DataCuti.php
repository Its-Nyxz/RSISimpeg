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

    public function mount()
    {
        $user = auth()->user();

        // Ambil ID unit KEPEGAWAIAN sekali saja
        $this->unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');

        // Tandai apakah user berasal dari unit KEPEGAWAIAN
        $this->isKepegawaian = $user->unit_id == $this->unitKepegawaianId;
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

    public function loadData()
    {
        $user = auth()->user();

        // ✅ Pastikan user punya izin approval-cuti
        if (!$user->can('approval-cuti')) {
            logger("User {$user->name} tidak punya izin approval-cuti");
            return CutiKaryawan::whereNull('id')->paginate(10);
        }

        // Base query
        $query = CutiKaryawan::with(['user.unitkerja', 'jenisCuti', 'statusCuti'])
            ->whereIn('status_cuti_id', [3, 4]) // Menunggu / Disetujui sementara
            ->where('user_id', '!=', $user->id);

        // 🔹 KEPEGAWAIAN → lihat semua data
        if ($this->isKepegawaian) {
            logger("User {$user->name} dari KEPEGAWAIAN, lihat semua data");
            return $query->orderByDesc('id')->paginate(10);
        }

        // 🔹 WADIR → hanya lihat cuti dari Manager
        if ($user->jabatan_id == 2) {
            $query = CutiKaryawan::with(['user.unitkerja', 'jenisCuti', 'statusCuti'])
                ->whereIn('status_cuti_id', [3, 4])
                ->whereHas('user', fn($q) => $q->where('jabatan_id', 3)) // Manager
                ->where('user_id', '!=', $user->id);

            logger("User {$user->name} (Wadir) hanya melihat cuti dari Manager.");
            return $query->orderByDesc('id')->paginate(10);
        }

        // 🔹 DIREKTUR → lihat cuti dari Wadir & Manager
        if ($user->jabatan_id == 1) {
            $query = CutiKaryawan::with(['user.unitkerja', 'jenisCuti', 'statusCuti'])
                ->whereIn('status_cuti_id', [3, 4])
                ->whereHas('user', fn($q) => $q->whereIn('jabatan_id', [2, 3])) // Wadir & Manager
                ->where('user_id', '!=', $user->id);

            logger("User {$user->name} (Direktur) melihat cuti dari Wadir & Manager.");
            return $query->orderByDesc('id')->paginate(10);
        }

        // 🔹 MANAGER → lihat Kepala Seksi + unit sendiri + Endah tambahan
        if ($user->hasRole('Manager')) {
            $query->where(function ($q) use ($user) {
                // Kepala Seksi
                $q->whereHas('user.roles', fn($r) => $r->whereIn('name', [
                    'Kepala Seksi',
                    'Kepala Seksi Kepegawaian',
                    'Kepala Seksi Keuangan'
                ]));

                // Staf unit sendiri
                $q->orWhereHas('user', fn($r) => $r->where('unit_id', $user->unit_id));

                // Endah khusus → Akuntansi & Keuangan
                if ($user->email === 'endah.lestari.d@gmail.com') {
                    $q->orWhereHas('user.unitkerja', fn($u) => $u->where(function ($q2) {
                        $q2->where('nama', 'like', '%Akuntansi%')
                            ->orWhere('nama', 'like', '%Keuangan%');
                    }));
                }
            });

            logger("User {$user->name} (Manager) melihat cuti Kepala Seksi, staf unitnya, dan tambahan khusus Endah.");
            return $query->orderByDesc('id')->paginate(10);
        }

        // 🔹 Kepala Instalasi → hanya Kepala Ruang di bawah unitnya
        if ($user->hasRole('Kepala Instalasi')) {
            $query->where('status_cuti_id', 3)
                ->whereHas('user.roles', fn($r) => $r->where('name', 'Kepala Ruang'))
                ->whereHas('user.unitkerja', fn($u) => $u->where('parent_id', $user->unit_id));

            logger("User {$user->name} (Kepala Instalasi) melihat cuti dari Kepala Ruang di bawah unitnya.");
            return $query->orderByDesc('id')->paginate(10);
        }

        // 🔹 Kepala Seksi Keuangan → hanya lihat staf di Keuangan dan Akuntansi (bukan pejabat tinggi)
        if ($user->hasRole('Kepala Seksi Keuangan')) {
            $query->where(function ($q) {
                $q->whereHas('user.unitkerja', function ($u) {
                    $u->where('nama', 'like', '%Keuangan%')
                        ->orWhere('nama', 'like', '%Akuntansi%')
                        ->orWhere('nama', 'like', '%Kasir%');

                })
                    // Pastikan bukan dari Manager / pejabat ke atas
                    ->whereHas('user', function ($usr) {
                        $usr->whereNotIn('jabatan_id', [1, 2, 3, 4]);
                        // 1 = Direktur, 2 = Wadir, 3 = Manager, 4 = misalnya Kepala Seksi
                        // (sesuaikan dengan ID jabatan di tabel kamu)
                    });
            });

            logger("User {$user->name} (Kepala Seksi Keuangan) melihat cuti dari staf Keuangan & Akuntansi saja (tanpa Manager).");
            return $query->orderByDesc('id')->paginate(10);
        }


        // 🔹 Kepala Seksi / unit biasa → hanya lihat staf di unit & child unit
        $unitIds = $this->getAllChildUnitIds($user->unit_id);

        $query->whereHas('user', fn($q) => $q->whereIn('unit_id', $unitIds));

        logger("User {$user->name} (unit {$user->unitkerja->nama}) melihat cuti staf unit: " . json_encode($unitIds));

        return $query->orderByDesc('id')->paginate(10);
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

        $userRole = $user->roles->first()->name ?? '';
        $isFinalApprover = (
            strcasecmp($userRole, 'Kepala Seksi Kepegawaian') === 0 ||
            $user->unit_id == $this->unitKepegawaianId
        );

        // 🔹 Jika MANAGER mengajukan cuti → diteruskan ke Direktur (unit_id = 59)
        if (optional($cuti->user->roles->first())->name === 'Manager') {
            $direktur = User::where('unit_id', 59)->first();

            if ($direktur) {
                $cuti->update(['status_cuti_id' => 4]);

                Notification::send($direktur, new UserNotification(
                    "Pengajuan cuti dari {$cuti->user->name} menunggu persetujuan Anda sebagai Direktur.",
                    "/approvalcuti"
                ));

                Notification::send($targetUser, new UserNotification(
                    "Pengajuan cuti Anda telah diteruskan ke Direktur untuk disetujui.",
                    "/pengajuan/cuti"
                ));

                RiwayatApproval::create([
                    'cuti_id' => $cutiId,
                    'approver_id' => $user->id,
                    'status_approval' => 'diteruskan_ke_direktur',
                    'approve_at' => now(),
                    'catatan' => 'Diteruskan ke Direktur',
                ]);

                return redirect()->route('approvalcuti.index')
                    ->with('success', 'Cuti Manager diteruskan ke Direktur untuk persetujuan.');
            }
        }

        // 🔹 Jika user adalah Wadir (jabatan_id = 2) atau Direktur (jabatan_id = 1)
        // dan cuti dari Manager → langsung final approve
        if (in_array($user->jabatan_id, [1, 2]) && $cuti->user->jabatan_id == 3) {
            $cutiStatus = 1; // Disetujui Final

            // 🔥 Validasi sisa cuti tahunan
            if (
                $cuti->jenisCuti &&
                strtolower($cuti->jenisCuti->nama_cuti) == 'cuti tahunan'
            ) {
                $userCuti = SisaCutiTahunan::where('user_id', $cuti->user_id)
                    ->where('tahun', now('Asia/Jakarta')->year)
                    ->first();

                if ($userCuti) {
                    if ($userCuti->sisa_cuti >= $cuti->jumlah_hari) {
                        $userCuti->decrement('sisa_cuti', $cuti->jumlah_hari);
                    } else {
                        $cuti->update(['status_cuti_id' => 2]); // Ditolak
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

            // ✅ Final update
            $cuti->update(['status_cuti_id' => $cutiStatus]);

            // 🔔 Notifikasi ke pemohon
            $pesan = "Pengajuan cuti Anda telah <span class='text-success-600 font-bold'>Disetujui Final</span> oleh {$user->name} ({$user->jabatan->nama}).";
            Notification::send($targetUser, new UserNotification($pesan, "/pengajuan/cuti"));

            // 🧾 Riwayat approval
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

        // 🔹 Logika existing lainnya (Manager, Kepala Instalasi, Kepegawaian, dst)
        $isFinalApprover = (
            strcasecmp($userRole, 'Kepala Seksi Kepegawaian') === 0 ||
            $user->unit_id == $this->unitKepegawaianId
        );

        if (
            $user->hasRole('Manager') &&
            in_array(optional($targetUser->roles->first())->name, [
                'Kepala Seksi',
                'Kepala Seksi Keuangan',
                'Kepala Seksi Kepegawaian'
            ])
        ) {
            $isFinalApprover = true;
        }

        if (stripos($user->name, 'endah') !== false) {
            $isFinalApprover = true;
        }

        $cutiStatus = $isFinalApprover ? 1 : 4;

        // 🔥 Validasi sisa cuti (final approval)
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

        // 🔔 Notifikasi ke pemohon
        $statusText = $isFinalApprover ? 'Disetujui Final' : 'Disetujui (Menunggu Final)';
        Notification::send($targetUser, new UserNotification(
            "Pengajuan cuti anda telah <span class='text-success-600 font-bold'>{$statusText}</span> oleh {$user->name}",
            "/pengajuan/cuti"
        ));

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




    public function rejectCuti($cutiId, $userId, $reason = null)
    {
        $cuti = CutiKaryawan::find($cutiId);
        $user = auth()->user();

        if ($cuti) {
            $cuti->update(['status_cuti_id' => 2]);
            $targetUser = User::find($userId);

            $message = 'Pengajuan Cuti anda (' . $targetUser->name .
                ') mulai <span class="font-bold">' . $cuti->tanggal_mulai . ' sampai ' . $cuti->tanggal_selesai .
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

    public function render()
    {
        $cutiData = $this->loadData();

        return view('livewire.data-cuti', [
            'users' => $cutiData,
            'isKepegawaian' => $this->isKepegawaian,
        ]);
    }
}