<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Absen;
use App\Models\JadwalAbsensi;
use Carbon\Carbon;
use App\Models\StatusAbsen;

class CreateAktivitasAbsensi extends Component
{
    public $user_id, $user_name, $jadwal_id, $tanggal, $time_in, $time_out, $deskripsi_in, $deskripsi_out, $deskripsi_lembur, $keterangan;

    public function mount($userId = null, $userName = null, $tanggal = null)
    {
        $this->user_id = $userId;
        $this->user_name = $userName;


        if ($tanggal) {
            $this->tanggal = $tanggal;
            $jadwal = JadwalAbsensi::where('user_id', $userId)
                ->where('tanggal_jadwal', $tanggal)
                ->first();

            if ($jadwal) {
                $this->jadwal_id = $jadwal->id;
            }
        } else {
            $this->tanggal = now()->format('Y-m-d');
            $jadwal = JadwalAbsensi::where('user_id', $userId)
                ->where('tanggal_jadwal', $this->tanggal)
                ->first();

            if ($jadwal) {
                $this->jadwal_id = $jadwal->id;
            }
        }
    }

    public function updatedTanggal($value)
    {
        if ($value) {
            $jadwal = JadwalAbsensi::where('user_id', $this->user_id)
                ->where('tanggal_jadwal', $value)
                ->first();

            if ($jadwal) {
                $this->jadwal_id = $jadwal->id;
            } else {
                $this->jadwal_id = null;
            }
        }
    }


    public function store()
    {
        // Ambil shift yang berlaku untuk user
        $jadwal = JadwalAbsensi::with('shift')
            ->where('user_id', $this->user_id)
            ->where('tanggal_jadwal', $this->tanggal)
            ->first();

        // ✅ Ambil ID status absen dari database di awal
        $statusTepatWaktu = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
        $statusTerlambat = StatusAbsen::where('nama', 'Keterlambatan')->value('id');
        $statusPulangAwal = StatusAbsen::where('nama', 'Pulang Awal')->value('id');
        $statusTidakAbsen = StatusAbsen::where('nama', 'Tidak Absen')->value('id');

        // ✅ Default ke "Tidak Absen"
        $status_absen_id = $statusTidakAbsen;

        // ✅ Ambil shift yang berlaku untuk user
        $jadwal = JadwalAbsensi::where('user_id', $this->user_id)
            ->where('tanggal_jadwal', $this->tanggal)
            ->first();

        if ($jadwal) {
            $shift_start = Carbon::parse($jadwal->shift->jam_masuk);
            $shift_end = Carbon::parse($jadwal->shift->jam_keluar);

            // ✅ Jika ada waktu masuk dan keluar
            if ($this->time_in && $this->time_out) {
                $timeIn = Carbon::parse($this->time_in);
                $timeOut = Carbon::parse($this->time_out);

                // ✅ Jika tepat waktu → Sama dengan shift_start atau dalam toleransi
                if (
                    $timeIn->equalTo($shift_start) || // Tepat sama
                    $timeIn->between($shift_start->copy()->subMinutes(5), $shift_start->copy()->addMinutes(5))
                ) {
                    $status_absen_id = $statusTepatWaktu;
                } elseif ($timeIn->greaterThan($shift_start)) {
                    // ✅ Jika terlambat
                    $status_absen_id = $statusTerlambat;
                }

                if ($timeOut->lessThan($shift_end)) {
                    $status_absen_id = $statusPulangAwal;
                }
            }

            // ✅ Jika hanya waktu masuk yang diisi
            elseif ($this->time_in) {
                $timeIn = Carbon::parse($this->time_in);

                if ($timeIn->between($shift_start->copy()->subMinutes(5), $shift_start->copy()->addMinutes(5))) {
                    $status_absen_id = $statusTepatWaktu;
                } elseif ($timeIn->greaterThan($shift_start)) {
                    $status_absen_id = $statusTerlambat;
                }
            }

            // ✅ Jika hanya waktu keluar yang diisi
            elseif ($this->time_out) {
                $status_absen_id = $statusPulangAwal;
            }
        }

        // ✅ Simpan data ke dalam tabel
        Absen::updateOrCreate(
            [
                'user_id' => $this->user_id,
                'jadwal_id' => $this->jadwal_id,
            ],
            [
                'time_in' => $this->time_in ? Carbon::parse($this->time_in)->format('Y-m-d H:i:s') : null,
                'time_out' => $this->time_out ? Carbon::parse($this->time_out)->format('Y-m-d H:i:s') : null,
                'deskripsi_in' => $this->deskripsi_in,
                'deskripsi_out' => $this->deskripsi_out,
                'deskripsi_lembur' => $this->deskripsi_lembur,
                'keterangan' => $this->keterangan,
                'status_absen_id' => $status_absen_id,
            ]
        );

        return redirect()->route('aktivitasabsensi.index')->with('success', 'Data absen berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.create-aktivitas-absensi');
    }
}
