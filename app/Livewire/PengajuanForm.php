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
use Livewire\WithFileUploads;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class PengajuanForm extends Component
{
    use WithFileUploads;
    public $tipe;
    public $judul;
    public $deskripsi;

    public $tanggal_mulai;
    public $tanggal_selesai;
    public $tanggal;
    public $keterangan;
    public $bukti_izin;

    public $jenis_cuti_id;
    public $jenis_cutis;
    public $durasi_default;
    public $jenis_izins_id;
    public $jenis_izins;

    public $shift_id;
    public $shifts;


    // Untuk menerima tipe dari controller
    public function mount($tipe)
    {
        $this->tipe = $tipe;

        if ($tipe === 'cuti') {
            $this->judul = 'Pengajuan Cuti';
            $this->deskripsi = 'Silakan isi form untuk mengajukan cuti.';
            $this->jenis_cutis = JenisCuti::all();
        } elseif ($tipe === 'ijin') {
            $this->judul = 'Pengajuan Izin';
            $this->deskripsi = 'Silakan isi form untuk mengajukan izin.';
            $this->jenis_izins = JenisIzin::all();
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

        // Langsung cek nilainya
        return $user->masa_kerja >= 12;
    }

    // ✅ Jika jenis cuti dipilih, ambil durasi default dan hitung tanggal selesai
    public function updatedJenisCutiId()
    {
        $cuti = JenisCuti::find($this->jenis_cuti_id);

        if ($cuti) {
            // ✅ Jika cuti tahunan → Abaikan durasi default
            if ($cuti->nama_cuti === 'Cuti Tahunan') {
                $this->durasi_default = null;
                $this->tanggal_selesai = null; // Jangan hitung otomatis
            } else {
                // ✅ Hitung otomatis jika bukan cuti tahunan
                $this->durasi_default = $cuti->durasi_default;
                $this->hitungTanggalSelesai();
            }
        }
    }

    // ✅ Jika tanggal mulai diubah, hitung tanggal selesai (kecuali cuti tahunan)
    public function updatedTanggalMulai()
    {
        $cuti = JenisCuti::find($this->jenis_cuti_id);
        if ($cuti && $cuti->nama_cuti !== 'Cuti Tahunan') {
            $this->hitungTanggalSelesai();
        }
    }

    private function hitungTanggalSelesai()
    {
        if ($this->tanggal_mulai && $this->durasi_default) {
            // Hitung tanggal selesai berdasarkan durasi default
            $this->tanggal_selesai = date('Y-m-d', strtotime($this->tanggal_mulai . " +{$this->durasi_default} days -1"));
        }
    }

    // ✅ Jika tanggal diubah, ambil shift berdasarkan unit user
    public function updatedTanggal()
    {
        if ($this->tipe === 'tukar_jadwal') {
            $this->ambilShiftsBerdasarkanUnitUser();
        }
    }

    // ✅ Ambil shift berdasarkan unit user
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

        // 🔹 Case khusus Staf Kepegawaian → langsung final
        if (stripos($targetUserRole, 'Staf Kepegawaian') !== false) {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Kepala Seksi Kepegawaian');
            })->get();
        }

        // 🔹 Case khusus Staf Keuangan → Ka Seksi Keuangan kalau ada, kalau tidak → final
        if (stripos($targetUserRole, 'Staf Keuangan') !== false) {
            $ksKeu = User::where('unit_id', $unitId) 
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Kepala Seksi Keuangan');
                })->get();

            return $ksKeu->count() > 0 ? $ksKeu : User::whereHas('roles', function ($q) {
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

    public function save()
    {
        $user = auth()->user();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $kepegawaianUsers = User::where('unit_id', $unitKepegawaianId)->get();

        if ($this->tipe === 'cuti') {
            // 🔹 Cek apakah ada cuti di tanggal yang sama
            $cekCutiSama = CutiKaryawan::where('user_id', auth()->id())
                ->where('tanggal_mulai', $this->tanggal_mulai)
                ->exists();

            if ($user->jenis_id === 3 && !$this->cekMasaKerjaBolehCuti($user)) {
                $this->dispatch('swal:modal', icon: 'error', title: 'Pengajuan Ditolak', text: 'Karyawan kontrak dengan masa kerja kurang dari 12 bulan belum berhak mengajukan cuti.');
                return;
            }

            if ($cekCutiSama) {
                $this->dispatch('swal:modal', icon: 'error', title: 'Pengajuan Gagal', text: 'Anda sudah mengajukan cuti dengan tanggal mulai yang sama.');
                return;
            }

            // 🔹 Validasi
            $this->validate([
                'jenis_cuti_id' => 'required|exists:jenis_cutis,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'keterangan' => 'nullable|string|max:255',
            ]);

            // 🔹 Hitung jumlah hari
            $jumlah_hari = (strtotime($this->tanggal_selesai) - strtotime($this->tanggal_mulai)) / 86400 + 1;

            // 🔹 Simpan ke DB
            $cutikaryawan = CutiKaryawan::create([
                'user_id' => auth()->id(),
                'jenis_cuti_id' => $this->jenis_cuti_id,
                'status_cuti_id' => 3, // Status default: Menunggu
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $this->keterangan,
            ]);

            $userRole = $user->roles->first()->name ?? 'Staf';

            // 🔹 Cari approver pertama dengan auto-skip
            $nextApprovers = $this->findNextApproversWithSkip($user);

            // Fallback: kalau kosong, langsung ke Kepala Seksi Kepegawaian
            if ($nextApprovers->isEmpty()) {
                $nextApprovers = User::whereHas('roles', function ($q) {
                    $q->where('name', 'Kepala Seksi Kepegawaian');
                })->get();
            }

            $jenis_cuti = JenisCuti::find($this->jenis_cuti_id);

            // 🔹 Buat pesan notifikasi
            $roleNames = $nextApprovers->pluck('roles.*.name')->flatten()->unique()->implode(', ');

            $message = 'Pengajuan Cuti ' . $user->name .
                ' mulai <span class="font-bold">' . $this->tanggal_mulai . ' sampai ' .  $this->tanggal_selesai .
                '</span> ' . ($jenis_cuti ? $jenis_cuti->nama_cuti : 'Tidak Diketahui') .
                ' dengan keterangan "' . $this->keterangan .
                '" membutuhkan persetujuan dari <span class="font-bold">' . $roleNames . '</span>.';

            // 🔹 Kirim notifikasi
            if ($nextApprovers->count() > 0) {
                Notification::send($nextApprovers, new UserNotification($message, "/approvalcuti"));
            }

            session()->flash('message', 'Pengajuan cuti berhasil diajukan.');
            return redirect()->route('pengajuan.index', 'cuti')->with('success', 'Pengajuan Cuti berhasil diajukan!');
        } elseif ($this->tipe === 'ijin') {
            // ✅ Validasi untuk ijin
            $this->validate([
                'jenis_izins_id' => 'required|exists:jenis_izins,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'keterangan' => 'nullable|string|max:255',
                'bukti_izin' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $jumlah_hari = (strtotime($this->tanggal_selesai) - strtotime($this->tanggal_mulai)) / 86400 + 1;
            // ✅ Simpan pengajuan ijin ke database (jika ada model khusus untuk ijin)
            if ($this->bukti_izin) {
                $fileName = $this->bukti_izin->store('photos/bukti-izin', 'public');
                $this->bukti_izin = basename($fileName);
            }
            $izinkaryawan = IzinKaryawan::create([
                'user_id' => auth()->id(),
                'jenis_izin_id' => $this->jenis_izins_id,
                'status_izin_id' => 3,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $this->keterangan,
                'bukti_izin' => $this->bukti_izin ?? null,
            ]);

            $user = auth()->user();
            // Ambil nama shift berdasarkan shift_id
            $jenis_izin = JenisIzin::find($this->jenis_izins_id)->first();
            $nextUser = User::where('unit_id', $user->unit_id)->whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Kepala%'))->first();
            $message = 'Pengajuan Izin ' . auth()->user()->name .
                ' mulai <span class="font-bold">' . $this->tanggal_mulai . ' sampai ' .  $this->tanggal_selesai .
                '</span> ' .
                ($jenis_izin ? $jenis_izin->nama_izin : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan . ' membutuhkan persetujuan Anda.';


            $url = "/approvalizin";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
            }
            return redirect()->route('pengajuan.index', 'ijin')->with('success', 'Pengajuan Izin berhasil di ajukan!');
        } elseif ($this->tipe === 'tukar_jadwal') {
            // ✅ Validasi untuk tukar jadwal
            $this->validate([
                'tanggal' => 'required|date|after_or_equal:today',
                'shift_id' => 'required|exists:shifts,id',
                'keterangan' => 'nullable|string|max:255',

            ]);

            // ✅ Simpan pengajuan tukar jadwal ke database (jika ada model khusus untuk tukar jadwal)
            // Contoh:
            $tukarJadwal = TukarJadwal::create([
                'user_id' => auth()->id(),
                'shift_id' => $this->shift_id,
                'tanggal' => $this->tanggal,
                'keterangan' => $this->keterangan,
            ]);

            $user = auth()->user();
            // Ambil nama shift berdasarkan shift_id
            $nama_shift = Shift::where('unit_id', $user->unit_id)
                ->where('id', $this->shift_id)
                ->first();

            // Cari kepala unit berdasarkan unit_id
            $nextUser = User::where('unit_id', $user->unit_id)
                ->whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Kepala%'))
                ->first();

            $message = 'Pengajuan Tukar Jadwal atau Shift ' . auth()->user()->name .
                ' <span class="font-bold">' . $this->tanggal .
                '</span> ke ' .
                ($nama_shift ? $nama_shift->nama_shift : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan . ' membutuhkan persetujuan Anda.';
            $messageKepegawaian = 'Pengajuan Tukar Jadwal atau Shift ' . auth()->user()->name .
                ' mulai <span class="font-bold">' . $this->tanggal_mulai . ' sampai ' .  $this->tanggal_selesai .
                '</span> ' .
                ($nama_shift ? $nama_shift->nama_shift : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan . ' memerlukan perhatian Anda.';

            $url = "/approvaltukar";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
                Notification::send($kepegawaianUsers, new UserNotification($messageKepegawaian, $url));
            }

            session()->flash('message', 'Pengajuan tukar jadwal berhasil diajukan.');
        }

        // ✅ Reset input setelah pengajuan berhasil
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
