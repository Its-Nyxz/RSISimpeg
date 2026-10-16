<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Shift;
use Livewire\Component;
use App\Models\JenisCuti;
use App\Models\JenisIzin;
use App\Models\UnitKerja;
use App\Models\TukarJadwal;
use App\Models\CutiKaryawan;
use App\Models\IzinKaryawan;
use App\Models\MasterJatahCuti;
use App\Models\SisaCutiTahunan;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class PengajuanForm extends Component
{
    use WithFileUploads;

    public $id;
    public $tipe;
    public $judul;
    public $deskripsi;
    public $kategori_cuti = 'tahunan';
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $tanggal;
    public $keterangan;

    /** @var TemporaryUploadedFile|null */
    public $bukti_izin;

    public $jenis_cuti_id;
    public $jenis_cutis;
    public $durasi_default;
    public $jenis_izins_id;
    public $jenis_izins;
    public $shift_id;
    public $shifts;
    public $userid;
    public $karyawans = [];

    // Untuk menerima tipe dari controller
    public function mount($tipe, $userid = null)
    {
        $this->tipe = $tipe;
        $this->userid = $userid;

        if ($tipe === 'cuti') {
            $this->judul = 'Pengajuan Cuti';
            $this->deskripsi = 'Silakan isi form untuk mengajukan cuti.';

            // Hanya mengambil jenis cuti yang aktif
            $this->jenis_cutis = JenisCuti::where('aktif', 1)->get();

            if ($this->userid !== null) {
                $this->karyawans = User::find($this->userid);
            } else {
                $this->userid = auth()->id();
                $this->karyawans = auth()->user();
            }
        } elseif ($tipe === 'ijin') {
            $this->judul = 'Pengajuan Izin';
            $this->deskripsi = 'Silakan isi form untuk mengajukan izin.';

            // Hanya mengambil jenis izin yang aktif
            $this->jenis_izins = JenisIzin::where('aktif', 1)->get();
        } elseif ($tipe === 'tukar_jadwal') {
            $this->judul = 'Pengajuan Tukar Jadwal';
            $this->deskripsi = 'Silakan isi form untuk menukar jadwal.';
        }
    }

    private function cekMasaKerjaBolehCuti($user)
    {
        // Kalau data masa_kerja kosong/null, default dianggap belum boleh cuti
        if (!$user->masa_kerja) {
            return false;
        }

        return $user->masa_kerja >= 12;
    }

    // Jika jenis cuti dipilih, ambil durasi default dan hitung tanggal selesai
    public function updatedJenisCutiId()
    {
        $cuti = JenisCuti::where('aktif', 1)
            ->find($this->jenis_cuti_id);

        if ($cuti) {
            // Jika cuti tahunan, abaikan durasi default
            if ($cuti->nama_cuti === 'Cuti Tahunan') {
                $this->durasi_default = null;
                $this->tanggal_selesai = null;
            } else {
                $this->durasi_default = $cuti->durasi_default;
                $this->hitungTanggalSelesai();
            }
        } else {
            $this->jenis_cuti_id = null;
            $this->durasi_default = null;
            $this->tanggal_selesai = null;
        }
    }

    // Jika tanggal mulai diubah, hitung tanggal selesai (kecuali cuti tahunan)
    public function updatedTanggalMulai()
    {
        $cuti = JenisCuti::where('aktif', 1)
            ->find($this->jenis_cuti_id);

        if ($cuti && $cuti->nama_cuti !== 'Cuti Tahunan') {
            $this->hitungTanggalSelesai();
        }
    }

    private function hitungTanggalSelesai()
    {
        if ($this->tanggal_mulai && $this->durasi_default) {
            $this->tanggal_selesai = date(
                'Y-m-d',
                strtotime(
                    $this->tanggal_mulai .
                    " +{$this->durasi_default} days -1"
                )
            );
        }
    }

    // Jika tanggal diubah, ambil shift berdasarkan unit user
    public function updatedTanggal()
    {
        if ($this->tipe === 'tukar_jadwal') {
            $this->ambilShiftsBerdasarkanUnitUser();
        }
    }

    // Ambil shift berdasarkan unit user
    private function ambilShiftsBerdasarkanUnitUser()
    {
        $user = auth()->user();

        if ($user) {
            $this->shifts = Shift::where('unit_id', $user->unit_id)
                ->get();
        }
    }

    private function findNextApproversWithSkip($targetUser)
    {
        $targetUserRole = $targetUser->roles->first()->name ?? 'Staf';
        $unitId = $targetUser->unit_id;
        $parentUnitId = optional($targetUser->unitkerja)->parent_id;

        // Khusus Manager: langsung ke Wadir dan Direktur
        if ($targetUser->jabatan_id == 3) {
            $wadir = User::where('jabatan_id', 2)->get();
            $direktur = User::where('jabatan_id', 1)->get();

            return $wadir->merge($direktur);
        }

        // Khusus Wadir: langsung ke Direktur
        if ($targetUser->jabatan_id == 2) {
            return User::where('jabatan_id', 1)->get();
        }

        // Kepala Seksi Kepegawaian dan Keuangan ke Manager
        if (in_array($targetUserRole, [
            'Kepala Seksi Kepegawaian',
            'Kepala Seksi Keuangan',
            'Kepala Seksi',
        ])) {
            return User::whereHas(
                'roles',
                fn($q) => $q->where('name', 'Manager')
            )->get();
        }

        // Staf Kepegawaian ke Kepala Seksi Kepegawaian
        if (stripos($targetUserRole, 'Staf Kepegawaian') !== false) {
            return User::whereHas(
                'roles',
                fn($q) => $q->where('name', 'Kepala Seksi Kepegawaian')
            )->get();
        }

        // Staf Keuangan ke Kepala Seksi Keuangan
        if (stripos($targetUserRole, 'Staf Keuangan') !== false) {
            return User::whereHas(
                'roles',
                fn($q) => $q->where('name', 'Kepala Seksi Keuangan')
            )->get();
        }

        // Hierarki umum
        $approvalMap = [
            'Staf' => [
                'Kepala Ruang',
                'Kepala Instalasi',
                'Kepala Unit',
                'Kepala Seksi',
                'Manager',
                'Wadir',
                'Direktur',
            ],
            'Kepala Ruang' => [
                'Kepala Instalasi',
                'Kepala Unit',
                'Kepala Seksi',
            ],
            'Kepala Instalasi' => ['Kepala Seksi'],
            'Kepala Unit' => ['Kepala Seksi'],
            'Kepala Seksi' => ['Manager'],
            'Manager' => ['Wadir', 'Direktur'],
            'Wadir' => ['Direktur'],
            'Direktur' => [],
        ];

        $nextRoles = $approvalMap[$targetUserRole] ?? [];
        $nextApprovers = collect();

        foreach ($nextRoles as $role) {
            $query = User::query();

            if (in_array($role, [
                'Kepala Ruang',
                'Kepala Instalasi',
                'Kepala Unit',
                'Kepala Seksi',
            ])) {
                $query->where('unit_id', $unitId);
            }

            $users = $query->whereHas(
                'roles',
                fn($q) => $q->where('name', $role)
            )->get();

            if ($targetUserRole === 'Staf' && $role === 'Kepala Ruang') {
                if ($users->isNotEmpty()) {
                    $nextApprovers = $users;
                    break;
                } elseif ($parentUnitId) {
                    $users = User::where('unit_id', $parentUnitId)
                        ->whereHas(
                            'roles',
                            fn($q) => $q->where(
                                'name',
                                'Kepala Instalasi'
                            )
                        )
                        ->get();

                    if ($users->isNotEmpty()) {
                        $nextApprovers = $users;
                        break;
                    }
                }
            }

            if (
                $targetUserRole === 'Kepala Ruang' &&
                $role === 'Kepala Instalasi'
            ) {
                if ($parentUnitId) {
                    $users = User::where('unit_id', $parentUnitId)
                        ->whereHas(
                            'roles',
                            fn($q) => $q->where(
                                'name',
                                'Kepala Instalasi'
                            )
                        )
                        ->get();

                    if ($users->isNotEmpty()) {
                        $nextApprovers = $users;
                        break;
                    }
                }
            }

            if ($users->isNotEmpty()) {
                $nextApprovers = $users;
                break;
            }
        }

        // Fallback akhir: Kepegawaian
        if ($nextApprovers->isEmpty()) {
            $nextApprovers = User::whereHas(
                'roles',
                fn($q) => $q->where(
                    'name',
                    'Kepala Seksi Kepegawaian'
                )
            )->get();
        }

        return $nextApprovers;
    }

    public function save()
    {
        $user = $this->userid
            ? User::find($this->userid)
            : auth()->user();

        $unitKepegawaianId = UnitKerja::where(
            'nama',
            'KEPEGAWAIAN'
        )->value('id');

        $kepegawaianUsers = User::where(
            'unit_id',
            $unitKepegawaianId
        )->get();

        if ($this->tipe === 'cuti') {
            $this->validate([
                'jenis_cuti_id' => [
                    'required',
                    Rule::exists('jenis_cutis', 'id')
                        ->where('aktif', 1),
                ],
                'kategori_cuti' => 'required|in:tahunan,tambahan',
                'tanggal_mulai' => $this->userid
                    ? 'required|date'
                    : 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'keterangan' => 'nullable|string|max:255',
            ]);

            $user = $this->userid
                ? User::find($this->userid)
                : auth()->user();

            $unitKepegawaianId = 87;

            // Validasi cuti ganda
            $cekCutiSama = CutiKaryawan::where('user_id', $user->id)
                ->where('tanggal_mulai', $this->tanggal_mulai)
                ->exists();

            if ($cekCutiSama) {
                $this->dispatch(
                    'swal:modal',
                    icon: 'error',
                    title: 'Pengajuan Gagal',
                    text: 'Anda sudah mengajukan cuti di tanggal tersebut.'
                );

                return;
            }

            if ($this->jenis_cuti_id == 1) {
                if ($user->jenis_id != 1 || empty($user->jenis_id)) {
                    // Jika semua data kosong
                    if (
                        empty($user->jenis_id) &&
                        empty($user->tmt_masuk) &&
                        empty($user->tmt)
                    ) {
                        $this->dispatch(
                            'swal:modal',
                            icon: 'error',
                            title: 'Pengajuan Ditolak',
                            text: 'Data Jenis Karyawan & TMT belum terisi'
                        );

                        return;
                    }

                    // Jika jenis karyawan kosong
                    if (empty($user->jenis_id)) {
                        $this->dispatch(
                            'swal:modal',
                            icon: 'error',
                            title: 'Pengajuan Ditolak',
                            text: 'Data Jenis Karyawan belum terisi'
                        );

                        return;
                    }

                    // Jika TMT masuk kosong
                    if (empty($user->tmt_masuk)) {
                        $this->dispatch(
                            'swal:modal',
                            icon: 'error',
                            title: 'Pengajuan Ditolak',
                            text: 'Data TMT masuk belum terisi'
                        );

                        return;
                    }

                    // Jika TMT kosong
                    if (empty($user->tmt)) {
                        $this->dispatch(
                            'swal:modal',
                            icon: 'error',
                            title: 'Pengajuan Ditolak',
                            text: 'Data TMT belum terisi'
                        );

                        return;
                    }

                    // Cek masa kerja karyawan kontrak
                    $now = now('Asia/Jakarta');
                    $tmt = Carbon::parse($user->tmt, 'Asia/Jakarta');
                    $targetTanggal = $tmt->copy()->addMonths(12);

                    if ($user->jenis_id === 3) {
                        if (!$now->greaterThanOrEqualTo($targetTanggal)) {
                            $sisaBulan = ceil(
                                $now->floatDiffInMonths($targetTanggal)
                            );

                            $this->dispatch(
                                'swal:modal',
                                icon: 'error',
                                title: 'Pengajuan Ditolak',
                                text: "Maaf, karyawan kontrak baru bisa cuti setelah 12 bulan. Sisa masa tunggu: $sisaBulan bulan lagi."
                            );

                            return;
                        } else {
                            $currentYear = now('Asia/Jakarta')->year;

                            // Ambil jatah cuti dari master, default 12
                            $jatahCuti = MasterJatahCuti::where(
                                'tahun',
                                $currentYear
                            )->value('jumlah_cuti') ?? 12;

                            // Buat sisa cuti jika belum ada
                            SisaCutiTahunan::firstOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'tahun' => $currentYear,
                                ],
                                [
                                    'sisa_cuti' => $jatahCuti,
                                ]
                            );
                        }
                    }
                }

                // Validasi masa kerja karyawan kontrak
                if (
                    $user->jenis_id === 3 &&
                    !$this->cekMasaKerjaBolehCuti($user)
                ) {
                    $this->dispatch(
                        'swal:modal',
                        icon: 'error',
                        title: 'Pengajuan Ditolak',
                        text: 'Karyawan kontrak belum memenuhi masa kerja minimal 12 bulan.'
                    );

                    return;
                }
            }

            // Hitung jumlah hari cuti
            $jumlah_hari = (
                strtotime($this->tanggal_selesai) -
                strtotime($this->tanggal_mulai)
            ) / 86400 + 1;

            // Tentukan status default
            $statusCuti = 3;

            // Kepala Instalasi langsung ke Menunggu Kepegawaian
            if ($user->hasRole('Kepala Instalasi')) {
                $statusCuti = 4;
            }

            // Simpan pengajuan cuti
            $cuti = CutiKaryawan::create([
                'user_id' => $user->id,
                'jenis_cuti_id' => $this->jenis_cuti_id,
                'status_cuti_id' => $statusCuti,
                'kategori_cuti' => $this->kategori_cuti,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $this->keterangan,
            ]);

            // Cari approver berdasarkan hierarki
            $approvers = $this->findNextApproversWithSkip($user);

            // Keluarkan pemohon dan cek permission approver
            $approvers = $approvers->filter(
                function (User $u) use ($user, $unitKepegawaianId) {
                    if ($u->id == $user->id) {
                        return false;
                    }

                    if (
                        $u->unit_id == $unitKepegawaianId &&
                        !$u->can('approval-cuti')
                    ) {
                        return false;
                    }

                    return $u->can('approval-cuti');
                }
            );

            // Fallback ke Kepegawaian jika approver kosong
            if ($approvers->isEmpty()) {
                $approvers = User::where(
                    'unit_id',
                    $unitKepegawaianId
                )
                    ->orWhereHas(
                        'roles',
                        fn($q) => $q->where(
                            'name',
                            'Kepala Seksi Kepegawaian'
                        )
                    )
                    ->get()
                    ->filter(
                        fn(User $u) =>
                            $u->id != $user->id &&
                            $u->can('approval-cuti')
                    );
            }

            // Buat pesan notifikasi
            $jenis_cuti = JenisCuti::find($this->jenis_cuti_id);

            $message = 'Pengajuan Cuti <b>' . e($user->name) . '</b> ' .
                'mulai <b>' . e($this->tanggal_mulai) .
                '</b> sampai <b>' . e($this->tanggal_selesai) . '</b> ' .
                '(' . e(optional($jenis_cuti)->nama_cuti ?? '-') . ') ' .
                'dengan keterangan: "' . e($this->keterangan) .
                '" membutuhkan persetujuan Anda.';

            // Kirim notifikasi ke approver
            if ($approvers->count() > 0) {
                Notification::send(
                    $approvers,
                    new UserNotification($message, '/approvalcuti')
                );
            }

            session()->flash(
                'success',
                'Pengajuan cuti berhasil diajukan.'
            );

            return redirect()->route('pengajuan.index', 'cuti');
        } elseif ($this->tipe === 'ijin') {
            // Validasi pengajuan izin
            $this->validate([
                'jenis_izins_id' => [
                    'required',
                    Rule::exists('jenis_izins', 'id')
                        ->where('aktif', 1),
                ],
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'keterangan' => 'nullable|string|max:255',

                // Mendukung JPG, JPEG, PNG, dan PDF maksimal 2 MB
                'bukti_izin' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $jumlah_hari = (
                strtotime($this->tanggal_selesai) -
                strtotime($this->tanggal_mulai)
            ) / 86400 + 1;

            // Simpan file hanya jika berupa TemporaryUploadedFile
            $buktiIzin = null;

            if ($this->bukti_izin instanceof TemporaryUploadedFile) {
                $fileName = $this->bukti_izin->store(
                    'photos/bukti-izin',
                    'public'
                );

                $buktiIzin = basename($fileName);
            }

            IzinKaryawan::create([
                'user_id' => auth()->id(),
                'jenis_izin_id' => $this->jenis_izins_id,
                'status_izin_id' => 3,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $this->keterangan,
                'bukti_izin' => $buktiIzin,
            ]);

            $user = auth()->user();

            // Ambil jenis izin
            $jenis_izin = JenisIzin::find($this->jenis_izins_id);

            $nextUser = User::where('unit_id', $user->unit_id)
                ->whereHas(
                    'roles',
                    fn($q) => $q->where('name', 'LIKE', '%Kepala%')
                )
                ->first();

            $message = 'Pengajuan Izin ' . auth()->user()->name .
                ' mulai <span class="font-bold">' . $this->tanggal_mulai .
                ' sampai ' . $this->tanggal_selesai .
                '</span> ' .
                ($jenis_izin
                    ? $jenis_izin->nama_izin
                    : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan .
                ' membutuhkan persetujuan Anda.';

            $url = '/approvalizin';

            if ($nextUser) {
                Notification::send(
                    $nextUser,
                    new UserNotification($message, $url)
                );
            }

            return redirect()
                ->route('pengajuan.index', 'ijin')
                ->with('success', 'Pengajuan Izin berhasil di ajukan!');
        } elseif ($this->tipe === 'tukar_jadwal') {
            // Validasi tukar jadwal
            $this->validate([
                'tanggal' => 'required|date|after_or_equal:today',
                'shift_id' => 'required|exists:shifts,id',
                'keterangan' => 'nullable|string|max:255',
            ]);

            // Simpan pengajuan tukar jadwal
            $tukarJadwal = TukarJadwal::create([
                'user_id' => auth()->id(),
                'shift_id' => $this->shift_id,
                'tanggal' => $this->tanggal,
                'keterangan' => $this->keterangan,
            ]);

            $user = auth()->user();

            // Ambil nama shift
            $nama_shift = Shift::where('unit_id', $user->unit_id)
                ->where('id', $this->shift_id)
                ->first();

            // Cari kepala unit
            $nextUser = User::where('unit_id', $user->unit_id)
                ->whereHas(
                    'roles',
                    fn($q) => $q->where('name', 'LIKE', '%Kepala%')
                )
                ->first();

            $message = 'Pengajuan Tukar Jadwal atau Shift ' .
                auth()->user()->name .
                ' <span class="font-bold">' . $this->tanggal .
                '</span> ke ' .
                ($nama_shift
                    ? $nama_shift->nama_shift
                    : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan .
                ' membutuhkan persetujuan Anda.';

            $messageKepegawaian = 'Pengajuan Tukar Jadwal atau Shift ' .
                auth()->user()->name .
                ' pada tanggal <span class="font-bold">' .
                $this->tanggal . '</span> ' .
                ($nama_shift
                    ? $nama_shift->nama_shift
                    : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan .
                ' memerlukan perhatian Anda.';

            $url = '/approvaltukar';

            if ($nextUser) {
                Notification::send(
                    $nextUser,
                    new UserNotification($message, $url)
                );

                Notification::send(
                    $kepegawaianUsers,
                    new UserNotification($messageKepegawaian, $url)
                );
            }

            session()->flash(
                'message',
                'Pengajuan tukar jadwal berhasil diajukan.'
            );
        }

        // Reset input setelah pengajuan berhasil
        $this->reset([
            'jenis_cuti_id',
            'jenis_izins_id',
            'tanggal_mulai',
            'tanggal_selesai',
            'tanggal',
            'keterangan',
            'shift_id',
        ]);
    }

    public function render()
    {
        return view('livewire.pengajuan-form');
    }
}
