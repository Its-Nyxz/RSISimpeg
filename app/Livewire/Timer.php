<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\JadwalAbsensi;
use App\Models\Shift;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Timer extends Component
{
    public $jadwal_id;
    public $time = 0;
    public $isRunning = false;
    public $timeIn;
    public $timeOut;
    public $late = false;
    public $keterangan = null;
    public $deskripsi_in;
    public $deskripsi_out;
    public $deskripsi_dinas;
    public $deskripsi_lembur = null; // Deskripsi lembur
    public $showDinasModal = false;
    public $showStartModal = false;
    public $showStopModal = false;
    public $showOvertimeModal = false; // Modal lembur

    public function mount($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;

        $absensi = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($absensi) {
            $this->timeIn = $absensi->time_in;
            $this->timeOut = $absensi->time_out;
            $this->late = $absensi->late;
            $this->keterangan = $absensi->keterangan;
            $this->deskripsi_in = $absensi->deskripsi_in;
            $this->deskripsi_out = $absensi->deskripsi_out;
            
            // Jika timer masih berjalan
            $this->isRunning = $this->timeIn && !$this->timeOut;
        }
    }

    public function openStartModal()
    {
        $this->showStartModal = true;
    }

    public function startTimer()
    {
        if (!$this->isRunning) {
            $this->isRunning = true;
            $this->timeIn = now();

            $jadwal = JadwalAbsensi::find($this->jadwal_id);
            if (!$jadwal) {
                $this->dispatch('alert-error', message: 'Jadwal tidak ditemukan.');
                return;
            }

            $shift = Shift::find($jadwal->shift_id);
            if (!$shift) {
                $this->dispatch('alert-error', message: 'Shift tidak ditemukan.');
                return;
            }

            $startShift = Carbon::parse($shift->jam_masuk);
            $canStart = $this->timeIn->greaterThanOrEqualTo($startShift->subMinutes(15));

            if (!$canStart) {
                $this->dispatch('alert-error', message: 'Anda hanya bisa memulai timer 15 menit sebelum waktu shift dimulai.');
                $this->isRunning = false;
                return;
            }

            $selisih = $startShift->diffInSeconds($this->timeIn, false);
            $this->late = $selisih > 0;
            $this->keterangan = $this->late 
                ? "Terlambat " . gmdate('H:i:s', abs($selisih)) 
                : "Masuk tepat waktu";

            Absen::updateOrCreate(
                [
                    'jadwal_id' => $this->jadwal_id,
                    'user_id' => Auth::id(),
                ],
                [
                    'time_in' => $this->timeIn,
                    'deskripsi_in' => $this->deskripsi_in,
                    'late' => $this->late,
                    'keterangan' => $this->keterangan,
                    'present' => 1,
                    'status_absen_id' => $this->late ? 2 : 1
                ]
            );

            $this->dispatch('timer-started', now()->timestamp);
            $this->showStartModal = false;
        }
    }

    public function openWorkReportModal()
    {
        if ($this->isRunning) {
            $this->timeOut = now();
            $this->isRunning = false;

            $selisih = Carbon::parse($this->timeIn)->diffInSeconds($this->timeOut);
            $jamKerja = $selisih / 3600;

            if ($jamKerja > 8) {
                $this->showOvertimeModal = true;
                return;
            }

            $this->completeWorkReport();
        }
    }

    public function completeWorkReport()
    {
        if (!$this->timeOut) return;

        $selisih = Carbon::parse($this->timeIn)->diffInSeconds($this->timeOut);
        $jamKerja = $selisih / 3600;

        Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->update([
                'time_out' => $this->timeOut,
                'deskripsi_out' => $this->deskripsi_out,
                'keterangan' => "Total waktu bekerja: " . gmdate('H:i:s', $selisih),
                'deskripsi_lembur' => $jamKerja > 8 ? $this->deskripsi_lembur : null
            ]);

        $this->dispatch('timer-stopped');
        $this->showStopModal = false;
        $this->showOvertimeModal = false;
        $this->deskripsi_out = null;
        $this->deskripsi_lembur = null;

        return redirect()->to('/timer');
    }

    public function dinasKeluar()
    {
        $user = auth()->user();
        $jadwal = JadwalAbsensi::find($this->jadwal_id);

        if (!$jadwal) {
            session()->flash('error', 'Jadwal tidak ditemukan.');
            return;
        }

        $shift = Shift::find($jadwal->shift_id);

        if (!$shift) {
            session()->flash('error', 'Shift tidak ditemukan.');
            return;
        }

        Absen::updateOrCreate(
            [
                'jadwal_id' => $this->jadwal_id,
                'user_id' => $user->id,
            ],
            [
                'time_in' => Carbon::parse($shift->jam_masuk),
                'time_out' => Carbon::parse($shift->jam_keluar),
                'deskripsi_in' => $this->deskripsi_dinas,
                'status_absen_id' => 1,
                'present' => 1,
                'keterangan' => "Dinas Keluar Terhitung Hadir dan 8 Jam kerja",
            ]
        );

        $this->deskripsi_dinas = null;
        $this->showDinasModal = false;
        session()->flash('message', 'Data dinas keluar berhasil disimpan.');
    }

    public function updateTimer()
    {
        if ($this->isRunning) {
            $this->time = now()->diffInSeconds(Carbon::parse($this->timeIn));
        }
    }

    public function render()
    {
        return view('livewire.timer');
    }
}
