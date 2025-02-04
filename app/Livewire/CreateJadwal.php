<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Shift;
use App\Models\OpsiAbsen;
use App\Models\JadwalAbsensi;

class CreateJadwal extends Component
{
    public $nama;          // Menyimpan nama user yang dipilih (hanya untuk tampilan)
    public $user_id;       // Menyimpan ID user untuk database
    public $shift_nama;    // Menyimpan nama shift yang dipilih (hanya untuk tampilan)
    public $shift_id;      // Menyimpan ID shift untuk database
    public $opsi_nama;     // Menyimpan nama opsi absensi (hanya untuk tampilan)
    public $opsi_id;       // Menyimpan ID opsi absensi untuk database
    public $tanggal;       // Tanggal jadwal
    public $keterangan;    // Keterangan absensi

    public $users = [];    // Daftar user
    public $shifts = [];   // Daftar shift
    public $opsis = [];    // Daftar opsi absensi

    // Aturan validasi
    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'shift_id' => 'required|exists:shifts,id',
        'opsi_id' => 'required|exists:opsi_absens,id',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->users = User::all();
        $this->shifts = Shift::all();
        $this->opsis = OpsiAbsen::all();
    }

    public function store()
    {
        $this->validate();

        JadwalAbsensi::create([
            'user_id' => $this->user_id,
            'shift_id' => $this->shift_id,
            'opsi_id' => $this->opsi_id,
            'tanggal_jadwal' => $this->tanggal,
            'keterangan_absen' => $this->keterangan,
        ]);

        session()->flash('success', 'Jadwal Absensi berhasil ditambahkan!');
        return redirect()->route('jadwal.index');
    }

    // Method untuk memilih user
    public function selectUser($id, $name)
    {
        $this->user_id = $id;
        $this->nama = $name;
    }

    // Method untuk memilih shift
    public function selectShift($id, $nama_shift)
    {
        $this->shift_id = $id;
        $this->shift_nama = $nama_shift;
    }

    // Method untuk memilih opsi absensi
    public function selectOpsi($id, $name)
    {
        $this->opsi_id = $id;
        $this->opsi_nama = $name;
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
