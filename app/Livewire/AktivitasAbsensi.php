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

            // Ambil data jadwal di tanggal tersebut (kalau ada)
            $jadwal = JadwalAbsensi::where('user_id', $this->selectedUserId)
                ->whereDate('tanggal_jadwal', $date)
                ->with('absensi')
                ->first();

            $absensi = $jadwal?->absensi->first();
            $timeIn = $absensi?->time_in ? Carbon::parse($absensi->time_in) : null;
            $timeOut = $absensi?->time_out ? Carbon::parse($absensi->time_out) : null;
            $duration = $timeIn && $timeOut
                ? $timeIn->diff($timeOut)->format('%H:%I:%S')
                : '-';
            $this->items[] = [
                'hari' => Carbon::parse($date)->locale('id')->isoFormat('dddd'),
                'tanggal' => Carbon::parse($date)->translatedFormat('d F Y'),
                'jam_kerja' => $duration, // Jika tidak ada jadwal, tampilkan '-'
                'rencana_kerja' => $absensi?->deskripsi_in ?? '-',
                'laporan_kerja' => $absensi?->deskripsi_out ?? '-',
                'feedback' => $absensi?->feedback ?? '-',
                'is_holiday' => $this->isHoliday($date),
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
