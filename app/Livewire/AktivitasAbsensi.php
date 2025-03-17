<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Holidays;
use Livewire\Component;
use App\Models\JadwalAbsensi;

class AktivitasAbsensi extends Component
{
    public $search = '';
    public $items = [];
    public $month;
    public $year;
    public $isParent = false;
    public $selectedUserId;
    public $subordinates = [];

    public function mount()
    {
        // Default ke bulan dan tahun saat ini
        $this->month = now()->month;
        $this->year = now()->year;
        // Cari apakah ada user lain dengan unit_id yang sama → Menentukan sebagai parent
        $this->isParent = User::where('unit_id', auth()->user()->unit_id)
            ->where('id', '!=', auth()->id())
            ->exists();

        if ($this->isParent) {
            // Jika user adalah parent, ambil daftar user bawahannya berdasarkan unit_id
            $this->subordinates = User::where('unit_id', auth()->user()->unit_id)
                ->pluck('name', 'id');

            // Default pilih user pertama jika ada
            $this->selectedUserId = $this->subordinates->keys()->first();
        } else {
            // Jika bukan parent, gunakan ID user yang login
            $this->selectedUserId = auth()->id();
        }

        $this->loadData();
    }

    public function loadData()
    {
        $this->items = []; // Kosongkan dulu data sebelumnya

        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->format('Y-m-d');

            // Ambil data jadwal di tanggal tersebut
            $jadwal = JadwalAbsensi::where('user_id', $this->selectedUserId)
                ->whereDate('tanggal_jadwal', $date)
                ->with(['absensi', 'shift'])
                ->first();

            $shift = $jadwal?->shift; // Ambil shift dari jadwal absensi
            $absensi = $jadwal?->absensi->first(); // Ambil satu absensi pertama

            // Ambil jam masuk dan keluar dari shift
            $shiftStart = $shift ? Carbon::parse($shift->jam_masuk) : null;
            $shiftEnd = $shift ? Carbon::parse($shift->jam_keluar) : null;

            // Ambil jam masuk dan keluar dari absensi
            $timeIn = $absensi?->time_in ? Carbon::parse($absensi->time_in) : null;
            $timeOut = $absensi?->time_out ? Carbon::parse($absensi->time_out) : null;

            // Default values
            $duration = '00.00.00';
            $overtime = '00.00.00';
            $keterangan = $absensi?->statusAbsen->nama ?? '-';

            if ($timeIn && $timeOut && $shiftStart && $shiftEnd) {
                // Hitung total jam kerja berdasarkan shift (dalam detik)
                $shiftDuration = $shiftStart->diffInSeconds($shiftEnd);
                // Debug: Tetapkan shift duration ke 5 detik untuk pengujian cepat
                // $shiftDuration = 5; // 5 detik


                // Hitung total waktu kerja dari absensi (dalam detik)
                $totalSeconds = $timeIn->diffInSeconds($timeOut);

                // Jika total kerja melebihi shift, selisih dianggap lembur
                if ($totalSeconds > $shiftDuration) {
                    $workSeconds = $shiftDuration;
                    $overtimeSeconds = $totalSeconds - $shiftDuration;
                } else {
                    $workSeconds = $totalSeconds;
                    $overtimeSeconds = 0;
                }

                // Format hasil dalam HH:mm:ss (termasuk detik)
                $duration = gmdate('H:i:s', $workSeconds);
                $overtime = $overtimeSeconds > 0
                    ? gmdate('H:i:s', $overtimeSeconds)
                    : '00:00:00'; // Tetapkan default jika null
            }

            // ✅ Hitung Keterlambatan
            $lateSeconds = 0;
            $lateMinutes = 0;
            $lateHours = 0;

            if ($timeIn && $shiftStart) {
                // Ambil selisih waktu (termasuk detik)
                $diff = $shiftStart->diff($timeIn);

                // Jika waktu masuk setelah shift start → Terlambat
                if ($diff->invert == 0) {
                    $lateSeconds = $diff->s; // Detik
                    $lateMinutes = $diff->i; // Menit
                    $lateHours = $diff->h;   // Jam

                    // Gabungkan keterlambatan ke dalam keterangan
                    if ($lateHours > 0 || $lateMinutes > 0 || $lateSeconds > 0) {
                        $keterangan = "Terlambat {$lateHours} jam {$lateMinutes} menit {$lateSeconds} detik";
                    }
                } elseif ($timeIn->equalTo($shiftStart)) {
                    $keterangan = "Tepat Waktu";
                }
            }


            // Simpan data ke array
            $this->items[] = [
                'id' => $absensi ? $absensi->id : null,
                'hari' => Carbon::parse($date)->locale('id')->isoFormat('dddd'),
                'tanggal' => Carbon::parse($date)->translatedFormat('d F Y'),
                'jam_kerja' => $duration,
                'jam_lembur' => $overtime,
                'rencana_kerja' => $absensi?->deskripsi_in ?? '-',
                'laporan_kerja' => $absensi?->deskripsi_out ?? '-',
                'laporan_lembur' => $absensi?->deskripsi_lembur ?? '-',
                'feedback' => $absensi?->feedback ?? '-',
                'is_holiday' => $this->isHoliday($date),
                'keterangan' => $keterangan,
            ];
        }
    }

    // Fungsi untuk menandai tanggal merah (libur nasional atau Minggu)
    public function isHoliday($date)
    {
        $carbonDate = Carbon::parse($date);

        // Tandai merah jika hari Minggu
        if ($carbonDate->isSunday()) {
            return true;
        }

        // Cek di database jika tanggal termasuk libur nasional
        $holiday = Holidays::where('date', $carbonDate->format('Y-m-d'))->exists();

        if ($holiday) {
            return true;
        }

        return false;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year', 'selectedUserId'])) {
            $this->loadData(); // Panggil ulang loadData jika bulan/tahun berubah
        }
    }
    public function render()
    {
        return view('livewire.aktivitas-absensi', [
            'bulanOptions' => range(1, 12),
            'tahunOptions' => range(now()->year - 5, now()->year)
        ]);
    }
}
