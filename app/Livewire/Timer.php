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
    public $showDinasModal = false;
    public $showStartModal = false;
    public $showStopModal = false;

    public function mount($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;

        // Ambil data absensi berdasarkan jadwal_id dan user yang sedang login
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
            if ($this->timeIn && !$this->timeOut) {
                $this->isRunning = true;
            } else {
                $this->isRunning = false;
            }
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

            // Ambil shift berdasarkan jadwal
            $jadwal = JadwalAbsensi::where('id', $this->jadwal_id)->first();
            $shift = Shift::where('id', $jadwal->shift_id)->first();
            if ($shift) {
                $startShift = Carbon::parse($shift->jam_masuk);

                // Periksa apakah sudah masuk 15 menit sebelum shift
                $canStart = $this->timeIn->greaterThanOrEqualTo($startShift->subMinutes(15));

                if (!$canStart) {
                    $this->dispatch('alert-error', message: 'Anda hanya bisa memulai timer 15 menit sebelum waktu shift dimulai.');

                    $this->isRunning = false;
                    $this->deskripsi_in = null;
                    return;
                }

                $selisih = $startShift->diffInSeconds($this->timeIn, false);

                if ($selisih > 0) {
                    $this->late = true;
                    $this->keterangan = "Terlambat " . gmdate('H:i:s', abs($selisih)) . " dari waktu mulai shift";
                    $statusAbsen = 2; // Keterlambatan
                } else {
                    $this->late = false;
                    $this->keterangan = "Masuk tepat waktu";
                    $statusAbsen = 1; // Tepat Waktu
                }
            }

            // Gunakan firstOrCreate untuk menyimpan atau memperbarui data absensi
            Absen::firstOrCreate(
                [
                    'jadwal_id' => $this->jadwal_id,
                    'user_id' => Auth::id(),
                ],
                [
                    'time_in' => $this->timeIn,
                    'deskripsi_in' => $this->deskripsi_in,
                    'late' => $this->late ? 1 : 0,
                    'keterangan' => $this->keterangan,
                    'present' => 1,
                    'status_absen_id' => $statusAbsen
                ]
            );
            // Kirim sinyal ke frontend (Alpine.js) untuk mulai timer
            $this->dispatch('timer-started', now()->timestamp);
            $this->showStartModal = false;
            $this->deskripsi_in = null;
        }
    }

    public function stopTimer()
    {
        if ($this->isRunning) {
            $this->isRunning = false;
            $this->timeOut = now();

            // Hitung selisih waktu antara time_in dan time_out
            $selisih = Carbon::parse($this->timeIn)->diffInSeconds($this->timeOut);
            // $stop = (int) Carbon::parse($this->timeOut)->timestamp;
            // dd($stop);
            // Perbarui data di database
            Absen::where('jadwal_id', $this->jadwal_id)
                ->where('user_id', Auth::id())
                ->update([
                    'time_out' => $this->timeOut,
                    'deskripsi_out' => $this->deskripsi_out,
                    'keterangan' => "Total waktu bekerja: " . gmdate('H:i:s', $selisih)
                ]);
            // Kirim sinyal ke frontend untuk menghentikan timer

            $this->dispatch('timer-stopped');

            $this->showStopModal = false;
            $this->deskripsi_out = null;

            return redirect()->to('/timer');
        }
    }

    public function dinasKeluar()
    {
        $user = auth()->user();

        // Ambil jadwal shift berdasarkan user atau jadwal ID
        $jadwal = JadwalAbsensi::where('id', $this->jadwal_id)->first();

        if ($jadwal) {
            $shift = Shift::where('id', $jadwal->shift_id)->first();

            if ($shift) {
                // Ambil jam kerja dari shift
                $timeIn = Carbon::parse($shift->jam_masuk)->timestamp;
                $timeOut = Carbon::parse($shift->jam_keluar)->timestamp;

                // Simpan ke database
                Absen::updateOrCreate(
                    [
                        'jadwal_id' => $this->jadwal_id,
                        'user_id' => $user->id,
                    ],
                    [
                        'time_in' => $timeIn,
                        'time_out' => $timeOut,
                        'deskripsi_in' => $this->deskripsi_dinas, // Sama dengan deskripsi in
                        'status_absen_id' => 1,
                        'present' => 1,
                        'keterangan' => "Dinas Keluar Terhitung Hadir dan 8 Jam kerja",

                    ]
                );

                // Reset setelah simpan
                $this->deskripsi_dinas = null;
                $this->showDinasModal = false;

                session()->flash('message', 'Data dinas keluar berhasil disimpan.');
            } else {
                session()->flash('error', 'Shift tidak ditemukan.');
            }
        } else {
            session()->flash('error', 'Jadwal tidak ditemukan.');
        }
    }

    // public function updateTimer()
    // {
    //     if ($this->isRunning) {
    //         $this->time = now()->diffInSeconds(Carbon::parse($this->timeIn));
    //     }
    // }

    public function render()
    {
        return view('livewire.timer');
    }
}
