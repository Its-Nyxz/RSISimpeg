<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JadwalAbsensi;
use App\Models\User;
use App\Models\Shift;
use App\Models\OpsiAbsen;

class EditJadwal extends Component
{
    public $jadwal_id;
    public $user_id;
    public $shift_id;
    public $opsi_id;
    public $tanggal_jadwal;
    public $keterangan_absen;

    public $users = [];
    public $shifts = [];
    public $opsis = [];

    public function mount($jadwalId)
    {
        $jadwal = JadwalAbsensi::findOrFail($jadwalId);

        $this->jadwal_id = $jadwal->id;
        $this->user_id = $jadwal->user_id;
        $this->shift_id = $jadwal->shift_id;
        $this->opsi_id = $jadwal->opsi_id;
        $this->tanggal_jadwal = $jadwal->tanggal_jadwal;
        $this->keterangan_absen = $jadwal->keterangan_absen;

        // Ambil data dari model untuk dropdown
        $this->users = User::all();
        $this->shifts = Shift::all();
        $this->opsis = OpsiAbsen::all();
    }

    public function updateJadwal()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'opsi_id' => 'required|exists:opsi_absens,id',
            'tanggal_jadwal' => 'required|date',
            'keterangan_absen' => 'nullable|in:Cuti,Libur,Tugas,Ijin,Sakit',
        ]);

        $jadwal = JadwalAbsensi::findOrFail($this->jadwal_id);
        $jadwal->update([
            'user_id' => $this->user_id,
            'shift_id' => $this->shift_id,
            'opsi_id' => $this->opsi_id,
            'tanggal_jadwal' => $this->tanggal_jadwal,
            'keterangan_absen' => $this->keterangan_absen,
        ]);

        session()->flash('success', 'Jadwal absensi berhasil diperbarui!');
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
        return view('livewire.edit-jadwal', [
            'users' => $this->users,
            'shifts' => $this->shifts,
            'opsis' => $this->opsis,
        ]);
    }
}
