<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Shift;
use Livewire\Component;
use App\Models\JenisCuti;
use App\Models\JenisIzin;
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


    public function save()
    {
        if ($this->tipe === 'cuti') {
            // ✅ Validasi untuk cuti
            $this->validate([
                'jenis_cuti_id' => 'required|exists:jenis_cutis,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'keterangan' => 'nullable|string|max:255',
            ]);

            // ✅ Hitung jumlah hari cuti
            $jumlah_hari = (strtotime($this->tanggal_selesai) - strtotime($this->tanggal_mulai)) / 86400 + 1;
            // ✅ Simpan pengajuan cuti ke database
            $cutikaryawan = CutiKaryawan::create([
                'user_id' => auth()->id(),
                'jenis_cuti_id' => $this->jenis_cuti_id,
                'status_cuti_id' => 3, // Status default: Menunggu
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $this->keterangan,
            ]);


            $user = auth()->user();
            // Ambil nama shift berdasarkan shift_id
            $jenis_cuti = JenisCuti::find($this->jenis_cuti_id)->first();

            // Cari kepala unit berdasarkan unit_id
            $nextUser = User::where('unit_id', $user->unit_id)
                ->whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Kepala%'))
                ->first();

            $message = 'Pengajuan Cuti ' . auth()->user()->name .
                'mulai <span class="font-bold">' . $this->tanggal_mulai . ' sampai ' .  $this->tanggal_selesai .
                '</span> ' .
                ($jenis_cuti ? $jenis_cuti->nama_cuti : 'Tidak Diketahui') .
                ' dengan keterangan ' . $this->keterangan . ' membutuhkan persetujuan Anda.';

            $url = "/approvalcuti"; //ganti approvel
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
            }

            session()->flash('message', 'Pengajuan cuti berhasil diajukan.');
            return redirect()->route('pengajuan.index', 'cuti')->with('success', 'Pengajuan Izin berhasil di ajukan!');
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

            $message = 'Pengajuan Tukar Jadwal atau Shift' . auth()->user()->name .
                ' <span class="font-bold">' . $this->tanggal .
                '</span> ke ' .
                ($nama_shift ? $nama_shift->nama_shift : 'Tidak Diketahui') .
                'dengan keterangan' . $this->keterangan . ' membutuhkan persetujuan Anda.';

            $url = "/pengajuan/tukarjadwal/{$tukarJadwal->id}";
            if ($nextUser) {
                Notification::send($nextUser, new UserNotification($message, $url));
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
