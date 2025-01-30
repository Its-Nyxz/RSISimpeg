<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Shift;
use App\Models\OpsiAbsen;
use App\Models\JadwalAbsensi;

class CreateJadwal extends Component
{
    public $nama; // Properti untuk menyimpan pilihan nama
    public $shift_id; // Properti untuk menyimpan pilihan shift
    public $opsi_id; // Properti untuk menyimpan pilihan opsi absensi
    public $tanggal; // Properti untuk tanggal jadwal
    public $keterangan; // Properti untuk keterangan absensi
    public $users = []; // Properti untuk daftar user
    public $shifts = []; // Properti untuk daftar shift
    public $opsis = []; // Properti untuk daftar opsi absen

    // Validasi input
    protected $rules = [
        'nama' => 'required|exists:users,id', // Validasi nama (user)
        'shift_id' => 'required|exists:shifts,id', // Validasi shift
        'opsi_id' => 'required|exists:opsi_absens,id', // Validasi opsi absensi
        'tanggal' => 'required|date', // Validasi tanggal
        'keterangan' => 'nullable|string', // Keterangan opsional
    ];

    public function mount()
    {
        // Mengambil data untuk dropdown
        $this->users = User::all(); // Daftar user
        $this->shifts = Shift::all(); // Daftar shift
        $this->opsis = OpsiAbsen::all(); // Daftar opsi absensi
    }

    public function store()
    {
        $this->validate(); // Validasi input

        // Simpan jadwal absensi ke dalam tabel jadwal_absensis
        JadwalAbsensi::create([
            'user_id' => $this->nama, // Menggunakan ID user yang dipilih
            'shift_id' => $this->shift_id, // Menggunakan ID shift yang dipilih
            'opsi_id' => $this->opsi_id, // Menggunakan ID opsi absensi yang dipilih
            'tanggal_jadwal' => $this->tanggal,
            'keterangan_absen' => $this->keterangan,
        ]);

        // Flash message dan redirect
        session()->flash('success', 'Jadwal Absensi berhasil ditambahkan!');
        return redirect()->route('jadwal.index'); // Redirect ke halaman jadwal absensi
    }

    public function render()
    {
        return view('livewire.create-jadwal', [
            'users' => $this->users,
            'shifts' => $this->shifts,
            'opsis' => $this->opsis,
        ]);
    }
}
