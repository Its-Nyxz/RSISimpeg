<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JadwalAbsen;
use App\Models\Shift;
use App\Models\OpsiAbsen;
use App\Models\User;

class CreateJadwalAbsensi extends Component
{
    public $nama_user;
    public $shift;
    public $opsi_absen;
    public $tanggal;
    public $keterangan;

    protected $rules = [
        'nama_user' => 'required|exists:users,name',
        'shift' => 'required|exists:shifts,name',
        'opsi_absen' => 'required|exists:opsi_absens,name',
        'tanggal' => 'required|date',
        'keterangan' => 'required|in:Cuti,Libur,Tugas,Ijin,Sakit',
    ];

    public function mount()
    {
        // Load data awal untuk dropdown
        $this->users = User::all();
        $this->shifts = Shift::all();
        $this->opsiAbsens = OpsiAbsen::all();
    }

    public function save()
    {
        $this->validate([
            'nama_user' => 'required|exists:users,name',
            'shift' => 'required|exists:shifts,name',
            'opsi_absen' => 'required|exists:opsi_absens,name',
            'tanggal' => 'required|date',
            'keterangan' => 'required|in:Cuti,Libur,Tugas,Ijin,Sakit',
        ]);

        JadwalAbsen::create([
            'nama_user' => $this->nama_user,
            'shift' => $this->shift,
            'opsi_absen' => $this->opsi_absen,
            'tanggal' => $this->tanggal,
            'keterangan' => $this->keterangan,
        ]);

        $this->reset('nama_user');
        $this->reset('shift');
        $this->reset('opsi_absen');
        $this->reset('tanggal');
        $this->reset('keterangan');

        return redirect()->route('jadwalAbsen.index')->with('success', 'Data Jadwal Absensi baru berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.create-jadwal-absensi');
    }
}
