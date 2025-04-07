<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use Livewire\Component;
use App\Models\Holidays;
use Illuminate\Support\Str;
use App\Models\JadwalAbsensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Request;

class AktivitasAbsensi extends Component
{
    public $search = '';
    public $items = [];
    public $month;
    public $year;
    public $isParent = false;
    public $selectedUserId;
    public $subordinates;

    public function mount()
    {
        // Default ke bulan dan tahun saat ini
        $this->month = now()->month;
        $this->year = now()->year;

        $this->isParent = User::where('unit_id', auth()->user()->unit_id)
            ->where('id', '!=', auth()->id())
            ->exists();
        $isKepala = collect(Auth::user()->roles()->pluck('name'))->filter(function ($name) {
            return str_starts_with($name, 'Kepala') ||
                str_starts_with($name, 'Super') ||
                str_starts_with($name, 'Administrator');
        })->count();
        // dd($isKepala);

        if ($isKepala) {
            // Jika user adalah parent, ambil daftar user bawahannya berdasarkan unit_id
            $this->subordinates = User::where('unit_id', auth()->user()->unit_id)
                ->pluck('name', 'id');
            // Default pilih user pertama jika ada
            $this->selectedUserId = $this->subordinates->keys()->first();
        } else {
            // Jika bukan parent, gunakan ID user yang login
            $this->selectedUserId = auth()->user()->id();
        }
        $this->loadData();
    }

    public function loadData()
    {
        $this->items = []; // Kosongkan data sebelumnya

        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->format('Y-m-d');

            // Ambil data jadwal di tanggal tersebut
            $jadwal = JadwalAbsensi::where('user_id', $this->selectedUserId)
                ->whereDate('tanggal_jadwal', $date)
                ->with(['absensi', 'shift'])
                ->first();

            // Jika jadwal tidak ditemukan, kita bisa setkan nilai default
            if (!$jadwal) {
                $shiftStart = null;
                $shiftEnd = null;
                $absensi = null;
            } else {
                $shift = $jadwal->shift; // Ambil shift dari jadwal absensi
                $absensi = $jadwal->absensi->first(); // Ambil satu absensi pertama

                // Ambil jam masuk dan keluar dari shift
                $shiftStart = $shift ? Carbon::parse($shift->jam_masuk) : null;
                $shiftEnd = $shift ? Carbon::parse($shift->jam_keluar) : null;
            }

            // Ambil jam masuk dan keluar dari absensi
            $timeIn = $absensi?->time_in ? Carbon::parse($absensi->time_in) : null;
            $timeOut = $absensi?->time_out ? Carbon::parse($absensi->time_out) : null;

            // Default values
            $duration = '00.00.00';
            $overtime = '00.00.00';
            // $keterangan = $absensi?->statusAbsen->nama ?? '-';

            // Jika ada absensi dan jadwal
            if ($jadwal) {
                $absensiItems = Absen::where('jadwal_id', $jadwal->id)
                    ->where('user_id', $this->selectedUserId)
                    ->get();

                $totalOvertime = 0; // Inisialisasi total overtime
                $totalWorkDuration = 0; // Inisialisasi total jam kerja

                // Iterasi semua absensi yang ada
                foreach ($absensiItems as $item) {
                    // Cek apakah absensi ini lembur atau tidak
                    if ($item->is_lembur) {
                        // Hitung durasi lembur
                        $timeInLembur = Carbon::parse($item->time_in);
                        $timeOutLembur = Carbon::parse($item->time_out);

                        if ($timeInLembur && $timeOutLembur) {
                            $overtimeSeconds = $timeInLembur->diffInSeconds($timeOutLembur);
                            $totalOvertime += $overtimeSeconds; // Tambah durasi lembur ke total
                        }
                    } else {
                        // Hitung jam kerja untuk absensi yang bukan lembur
                        $timeInAbsensi = Carbon::parse($item->time_in);
                        $timeOutAbsensi = Carbon::parse($item->time_out);

                        if ($timeInAbsensi && $timeOutAbsensi && $shiftStart && $shiftEnd) {
                            // Hitung durasi kerja berdasarkan absensi
                            $workSeconds = $timeInAbsensi->diffInSeconds($timeOutAbsensi);
                            // $shiftDuration = $shiftStart->diffInSeconds($shiftEnd); // Durasi shift dalam detik

                            // // Jika durasi absensi lebih lama dari durasi shift, sisanya dianggap lembur
                            // if ($workSeconds > $shiftDuration) {
                            //     $overtimeSeconds = $workSeconds - $shiftDuration;
                            //     $totalOvertime += $overtimeSeconds; // Tambah lembur ke total overtime
                            // }
                            //     $workSeconds = $shiftDuration; // Jam kerja tetap dibatasi durasi shift

                            $totalWorkDuration += $workSeconds; // Tambah durasi kerja ke total
                        }
                    }
                }

                // Format durasi lembur dan jam kerja
                $overtime = gmdate('H:i:s', $totalOvertime);
                $duration = gmdate('H:i:s', $totalWorkDuration);

                $deskripsiLembur = $absensiItems->isNotEmpty()
                    ? $absensiItems
                    ->where('is_lembur', true)
                    ->pluck('deskripsi_lembur')
                    ->filter()
                    ->implode('<br>')
                    : '-';
            } else {
                $deskripsiLembur = '-'; // ✅ Nilai default jika tidak ada data lembur
                $totalOvertime = null; // ✅ Nilai default jika tidak ada data lembur
            }


            // Simpan data ke array
            $this->items[] = [
                'id' => $absensi ? $absensi->id : null,
                'hari' => Carbon::parse($date)->locale('id')->isoFormat('dddd'),
                'tanggal' => Carbon::parse($date)->translatedFormat('d F Y'),
                'jam_kerja' => $duration, // jam kerja normal (jika is_lembur = false)
                'jam_lembur' => $overtime, // lembur (jika is_lembur = true)
                'rencana_kerja' => $absensi?->deskripsi_in ?? '-',
                'laporan_kerja' => $absensi?->deskripsi_out ?? '-',
                'laporan_lembur' => $deskripsiLembur ?? '-',
                'feedback' => $absensi?->feedback ?? '-',
                'is_holiday' => $this->isHoliday($date),
                // 'keterangan' => $keterangan,
                'is_lembur' => $totalOvertime > 0,
                'is_dinas' => $absensi?->is_dinas,
                'late' => $absensi?->late,
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

    public function exportPdfHistory()
    {
        $month = Carbon::createFromFormat('m', $this->month)->locale('id')->translatedFormat('F');
        $year = $this->year;
        $user = User::where('id', $this->selectedUserId)->first();
        $title = "Bulan " . $month . " Tahun " . $year;
        $items = $this->items;

        $pdf = Pdf::loadView('pdf.laporan-absensi-pdf', compact('items', 'title', 'user'));

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "laporan Absensi {$user->name} Bulan {$month} Tahun {$year}.pdf"
        );
    }
    public function render()
    {
        return view('livewire.aktivitas-absensi', [
            'bulanOptions' => range(1, 12),
            'tahunOptions' => range(now()->year - 5, now()->year)
        ]);
    }
}
