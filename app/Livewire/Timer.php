<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\Shift;
use Livewire\Component;
use App\Models\StatusAbsen;
use App\Models\JadwalAbsensi;
use Illuminate\Support\Facades\DB;
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
    public $showLemburModal = false; // Modal lembur mandiri
    public $timeInLembur;
    public $timeOutLembur;
    public $timeElapsedLembur;
    public $deskripsiLembur;
    public $absensiTanpaLembur;
    public $isLemburRunning = false;

    public function mount($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;

        // ✅ Ambil semua data absensi berdasarkan jadwal_id
        $absensi = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->get();

        // Filter data absensi yang is_lembur false
        $absensiTanpaLembur = $absensi->filter(function ($item) {
            return !$item->is_lembur; // Hanya ambil yang is_lembur = false
        });

        // Kirimkan data absensi yang is_lembur false ke Blade
        $this->absensiTanpaLembur = $absensiTanpaLembur;

        if ($absensi->count() > 0) {
            if ($absensi->count() === 1) {
                // ✅ Jika hanya ada satu data → Gunakan data langsung
                $data = $absensi->first();
                $this->timeIn = $data->time_in;
                $this->timeOut = $data->time_out;
                $this->late = $data->late;
                $this->keterangan = $data->keterangan;
                $this->deskripsi_in = $data->deskripsi_in;
                $this->deskripsi_out = $data->deskripsi_out;
            } else {
                // ✅ Jika ada lebih dari satu data → Lakukan penjumlahan
                $totalTimeIn = $absensi->sum('time_in');
                $totalTimeOut = $absensi->sum('time_out');

                $this->timeIn = $totalTimeIn;
                $this->timeOut = $totalTimeOut;


                $this->keterangan = "Total waktu kerja: " . gmdate('H:i:s', $totalTimeOut - $totalTimeIn);

                // Loop untuk menampilkan deskripsi_in, deskripsi_out atau deskripsi_lembur
                $this->deskripsiLembur = [];
                foreach ($absensi as $item) {
                    if ($item->is_lembur) { // Cek apakah lembur
                        $this->deskripsiLembur[] = [
                            'deskripsi_in' => $item->deskripsi_in,
                            'deskripsi_out' => $item->deskripsi_out,
                            'deskripsi_lembur' => $item->deskripsi_lembur // Deskripsi lembur
                        ];
                    }
                }
            }

            // ✅ Jika timer masih berjalan
            $this->isRunning = $this->timeIn && !$this->timeOut;
            $this->isLemburRunning = $this->checkIfLemburRunning();
        }

        // Jika lembur sedang berjalan, hitung durasi lembur dari waktu_in lembur terakhir
        if ($this->isLemburRunning) {
            $this->timeInLembur = $this->getLastLemburTimeIn();
            $this->calculateLemburDuration();
        }
    }

    private function checkIfLemburRunning()
    {
        // Check if the overtime timer is still running in the database or based on time
        $dataLembur = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->where('is_lembur', true)
            ->whereNull('time_out') // Check if there's no time_out, meaning it's still running
            ->first();

        return $dataLembur ? true : false; // If there's data, it's running
    }

    private function getLastLemburTimeIn()
    {
        // Ambil `time_in` dari lembur terakhir yang belum selesai
        $lastLembur = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->where('is_lembur', true)
            ->whereNull('time_out') // Lembur yang belum ada `time_out`
            ->latest()
            ->first();

        // Jika ada lembur yang sedang berjalan, parse `time_in` dan kembalikan timestamp-nya
        if ($lastLembur) {
            $timeInLembur = Carbon::parse($lastLembur->time_in); // Mengubah `time_in` menjadi objek Carbon
            return $timeInLembur->timestamp; // Mengambil timestamp dalam detik
        }

        return null; // Jika tidak ada lembur, kembalikan null
    }
    private function calculateLemburDuration()
    {
        // Pastikan $this->timeInLembur adalah objek Carbon
        if ($this->timeInLembur) {
            // Jika timeInLembur adalah timestamp (angka), maka parse menjadi objek Carbon
            $timeInLembur = Carbon::parse($this->timeInLembur);

            // Dapatkan waktu saat ini
            $currentTime = Carbon::now();

            // Hitung durasi lembur dalam detik
            $durationInSeconds = $timeInLembur->diffInSeconds($currentTime);

            // Set durasi lembur
            $this->timeElapsedLembur = $durationInSeconds;
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
            return redirect()->to('/timer');
        }
    }

    public function openWorkReportModal()
    {
        if ($this->isRunning) {
            $this->timeOut = now();
            $this->isRunning = false;

            $selisih = Carbon::parse($this->timeIn)->diffInSeconds($this->timeOut);
            $jamKerja = $selisih / 3600;

            $absensi = Absen::where('jadwal_id', $this->jadwal_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$absensi) {
                $this->dispatch('alert-error', message: 'Data absensi tidak ditemukan.');
                return;
            }

            // ✅ Ambil shift berdasarkan `jadwal_id`
            $jadwal = JadwalAbsensi::find($this->jadwal_id);
            if (!$jadwal) return;

            $shift = Shift::find($jadwal->shift_id);
            if (!$shift) return;

            // ✅ Hitung durasi shift dalam jam
            $shiftDuration = Carbon::parse($shift->jam_masuk)->diffInSeconds(Carbon::parse($shift->jam_keluar));
            $shiftHours = $shiftDuration / 3600;
            // $shiftHours = 5 / 3600;

            // ✅ Tentukan apakah terjadi lembur
            $isOvertime = $jamKerja > $shiftHours;

            if ($isOvertime) {
                // ✅ Jika lembur → Tampilkan modal konfirmasi
                $this->showOvertimeModal = true;
                return;
            }

            // ✅ Jika tidak lembur → Langsung simpan hasil kerja
            $this->completeWorkReport();
        } else {
            $this->dispatch('alert-error', message: 'Timer belum berjalan atau sudah berhenti.');
        }
    }


    public function completeWorkReport()
    {
        if (!$this->timeOut) return;

        $selisih = Carbon::parse($this->timeIn)->diffInSeconds($this->timeOut);
        $jamKerja = $selisih / 3600;

        $absensi = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$absensi) return;

        // ✅ Ambil shift berdasarkan `jadwal_id`
        $jadwal = JadwalAbsensi::find($this->jadwal_id);
        if (!$jadwal) return;

        $shift = Shift::find($jadwal->shift_id);
        if (!$shift) return;

        // ✅ Hitung durasi shift dalam jam
        $shiftDuration = Carbon::parse($shift->jam_masuk)->diffInSeconds(Carbon::parse($shift->jam_keluar));
        $shiftHours = $shiftDuration / 3600;

        // ✅ Ambil ID dari tabel `status_absens`
        $statusPulangAwal = StatusAbsen::where('nama', 'Pulang Awal')->value('id');
        $statusTepatWaktu = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
        $statusTerlambat = StatusAbsen::where('nama', 'Keterlambatan')->value('id');

        // ✅ Tentukan status absen berdasarkan hasil kerja
        if ($jamKerja < $shiftHours) {
            // Jika pulang lebih awal
            $statusAbsenId = $statusPulangAwal;
        } elseif ($this->late) {
            // Jika masuk terlambat
            $statusAbsenId = $statusTerlambat;
        } else {
            // Jika tepat waktu
            $statusAbsenId = $statusTepatWaktu;
        }

        // ✅ Cek apakah lembur terjadi
        $isOvertime = $jamKerja > $shiftHours;

        // ✅ Simpan data ke database
        $absensi->update([
            'time_out' => $this->timeOut,
            'deskripsi_out' => $this->deskripsi_out,
            'keterangan' => "Total waktu bekerja: " . gmdate('H:i:s', $selisih),
            'deskripsi_lembur' => $isOvertime ? $this->deskripsi_lembur : null,
            'status_absen_id' => $statusAbsenId // ✅ Simpan status absen ke database
        ]);

        // ✅ Reset modal dan nilai setelah menyimpan
        $this->dispatch('timer-stopped');
        $this->showStopModal = false;
        $this->showOvertimeModal = false;
        $this->deskripsi_out = null;
        $this->deskripsi_lembur = null;

        // ✅ Redirect ke halaman utama
        return redirect()->to('/timer');
    }


    public function saveOvertime()
    {
        if (!$this->timeOut) {
            $this->dispatch('alert-error', message: 'Waktu selesai belum ditetapkan.');
            return;
        }

        $selisih = Carbon::parse($this->timeIn)->diffInSeconds($this->timeOut);

        // Simpan data lembur ke database
        Absen::updateOrCreate(
            [
                'jadwal_id' => $this->jadwal_id,
                'user_id' => Auth::id(),
            ],
            [
                'time_out' => $this->timeOut,
                'deskripsi_out' => $this->deskripsi_out,
                'keterangan' => "Total waktu bekerja: " . gmdate('H:i:s', $selisih),
                'deskripsi_lembur' => $this->deskripsi_lembur,
            ]
        );
        // Tutup modal lembur
        $this->showOvertimeModal = false;

        $this->dispatch('timer-stopped');
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
        return redirect()->to('/timer');
    }

    public function openLemburModal()
    {
        $this->deskripsi_lembur = null;
        $this->showLemburModal = true;
    }

    public function startLemburMandiri()
    {
        if ($this->isLemburRunning) {
            $this->dispatch('alert-error', message: 'Lembur sudah berjalan.');
            return;
        }
        $this->timeInLembur = now();
        $this->isLemburRunning = true;

        // ✅ Simpan data lembur sebagai record baru
        Absen::create([
            'jadwal_id' => $this->jadwal_id,
            'user_id' => Auth::id(),
            'time_in' => $this->timeInLembur,
            'deskripsi_in' => 'Mulai lembur: ' . $this->timeInLembur->format('H:i:s'),
            'status_absen_id' => StatusAbsen::where('nama', 'Lembur')->value('id') ?? null,
            'present' => 1,
            'is_lembur' => true // ✅ Tandai ini sebagai absen lembur
        ]);

        $this->showLemburModal = false;

        $this->dispatch('timer-lembur-started', now()->timestamp);
        $this->dispatch('alert-success', message: 'Lembur telah dimulai.');
        return redirect()->to('/timer');
    }


    public function stopLemburMandiri()
    {
        if (!$this->isLemburRunning) {
            $this->dispatch('alert-error', message: 'Lembur belum dimulai.');
            return;
        }
        // Waktu mulai lembur (timestamp) dan waktu selesai lembur (timestamp saat ini)
        $waktuMulaiLembur = $this->timeInLembur;  // time_in lembur sebagai timestamp
        $waktuSelesaiLembur = Carbon::now()->timestamp;  // Waktu selesai lembur menggunakan timestamp saat ini
        // Menghitung durasi lembur dalam detik
        $durasiLembur = $waktuSelesaiLembur - $waktuMulaiLembur; // Durasi dalam detik

        if ($durasiLembur <= 0) {
            $this->dispatch('alert-error', message: 'Durasi lembur tidak valid.');
            return;
        }

        $time_out_lembur = now();

        // ✅ Perbarui absen lembur terakhir dengan `is_lembur = true`
        $lembur = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->where('is_lembur', true)
            ->latest()
            ->first();

        if ($lembur) {
            $lembur->update([
                'time_out' => $time_out_lembur,
                'deskripsi_out' => 'Selesai lembur: ' . $time_out_lembur->format('H:i:s'),
                'deskripsi_lembur' => $this->deskripsi_lembur ?: '-',
                'keterangan' => "Total lembur: " . gmdate('H:i:s', $durasiLembur),
                'status_absen_id' => StatusAbsen::where('nama', 'Lembur')->value('id'),
            ]);
        }

        // ✅ Update total jam kerja utama dengan tambahan lembur
        $absenUtama = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->where('is_lembur', false)
            ->first();

        if ($absenUtama) {
            // Menghitung durasi kerja utama dalam detik dengan menggunakan timestamp
            $durasiKerjaSaatIni = $absenUtama->time_out - $absenUtama->time_in;

            // Menambahkan durasi lembur ke durasi kerja utama
            $totalDurasi = $durasiKerjaSaatIni + $durasiLembur;

            // Mengupdate keterangan dengan total waktu kerja + lembur
            $absenUtama->update([
                'keterangan' => "Total waktu kerja + lembur: " . gmdate('H:i:s', $totalDurasi),
            ]);
        }

        $this->isLemburRunning = false;
        $this->deskripsi_lembur = null;

        $this->dispatch('timer-lembur-stopped');
        $this->dispatch('alert-success', message: 'Lembur berhasil dicatat.');
        return redirect()->to('/timer');
    }
    public function render()
    {
        return view('livewire.timer');
    }
}
