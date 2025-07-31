<?php

namespace App\Livewire;

use Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use Livewire\Component;
use App\Models\Holidays;
use Illuminate\Support\Str;
use App\Models\JadwalAbsensi;
use App\Exports\AbsensiExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
            $this->selectedUserId = auth()->id();
        }
        $this->loadData();
    }

    // public function loadData()
    // {
    //     $this->items = []; // Kosongkan data sebelumnya

    //     $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

    //     for ($day = 1; $day <= $daysInMonth; $day++) {
    //         $date = Carbon::create($this->year, $this->month, $day)->format('Y-m-d');

    //         // Ambil data jadwal di tanggal tersebut
    //         $jadwal = JadwalAbsensi::where('user_id', $this->selectedUserId)
    //             ->whereDate('tanggal_jadwal', $date)
    //             ->with(['absensi', 'shift'])
    //             ->get();

    //         // Jika jadwal tidak ditemukan, kita bisa setkan nilai default
    //         if (!$jadwal) {
    //             $shiftStart = null;
    //             $shiftEnd = null;
    //             $absensi = null;
    //         } else {
    //             $shift = $jadwal->shift; // Ambil shift dari jadwal absensi
    //             $absensi = $jadwal->absensi->first(); // Ambil satu absensi pertama

    //             // Ambil jam masuk dan keluar dari shift
    //             $shiftStart = $shift ? Carbon::parse($shift->jam_masuk) : null;
    //             $shiftEnd = $shift ? Carbon::parse($shift->jam_keluar) : null;
    //         }

    //         // Ambil jam masuk dan keluar dari absensi
    //         $timeIn = $absensi?->time_in ? Carbon::parse($absensi->time_in) : null;
    //         $timeOut = $absensi?->time_out ? Carbon::parse($absensi->time_out) : null;

    //         // Default values
    //         $duration = '00.00.00';
    //         $overtime = '00.00.00';
    //         // $keterangan = $absensi?->statusAbsen->nama ?? '-';

    //         // Jika ada absensi dan jadwal
    //         if ($jadwal) {
    //             $absensiItems = Absen::where('jadwal_id', $jadwal->id)
    //                 ->where('user_id', $this->selectedUserId)
    //                 ->get();

    //             $totalOvertime = 0; // Inisialisasi total overtime
    //             $totalWorkDuration = 0; // Inisialisasi total jam kerja

    //             // Iterasi semua absensi yang ada
    //             foreach ($absensiItems as $item) {
    //                 // Cek apakah absensi ini lembur atau tidak
    //                 if ($item->is_lembur) {
    //                     // Hitung durasi lembur
    //                     $timeInLembur = Carbon::parse($item->time_in);
    //                     $timeOutLembur = Carbon::parse($item->time_out);

    //                     if ($timeInLembur && $timeOutLembur) {
    //                         $overtimeSeconds = $timeInLembur->diffInSeconds($timeOutLembur);
    //                         $totalOvertime += $overtimeSeconds; // Tambah durasi lembur ke total
    //                     }
    //                 } else {
    //                     // Hitung jam kerja untuk absensi yang bukan lembur
    //                     $timeInAbsensi = Carbon::parse($item->time_in);
    //                     $timeOutAbsensi = Carbon::parse($item->time_out);

    //                     if ($timeInAbsensi && $timeOutAbsensi && $shiftStart && $shiftEnd) {
    //                         // Hitung durasi kerja berdasarkan absensi
    //                         $workSeconds = $timeInAbsensi->diffInSeconds($timeOutAbsensi);
    //                         // $shiftDuration = $shiftStart->diffInSeconds($shiftEnd); // Durasi shift dalam detik

    //                         // // Jika durasi absensi lebih lama dari durasi shift, sisanya dianggap lembur
    //                         // if ($workSeconds > $shiftDuration) {
    //                         //     $overtimeSeconds = $workSeconds - $shiftDuration;
    //                         //     $totalOvertime += $overtimeSeconds; // Tambah lembur ke total overtime
    //                         // }
    //                         //     $workSeconds = $shiftDuration; // Jam kerja tetap dibatasi durasi shift

    //                         $totalWorkDuration += $workSeconds; // Tambah durasi kerja ke total
    //                     }
    //                 }
    //             }

    //             // Format durasi lembur dan jam kerja
    //             $overtime = gmdate('H:i:s', $totalOvertime);
    //             $duration = gmdate('H:i:s', $totalWorkDuration);

    //             $deskripsiLembur = $absensiItems->isNotEmpty()
    //                 ? $absensiItems
    //                 ->where('is_lembur', true)
    //                 ->pluck('deskripsi_lembur')
    //                 ->filter()
    //                 ->implode('<br>')
    //                 : '-';
    //         } else {
    //             $deskripsiLembur = '-'; // ✅ Nilai default jika tidak ada data lembur
    //             $totalOvertime = null; // ✅ Nilai default jika tidak ada data lembur
    //         }


    //         // Simpan data ke array
    //         $this->items[] = [
    //             'id' => $absensi ? $absensi->id : null,
    //             'hari' => Carbon::parse($date)->locale('id')->isoFormat('dddd'),
    //             'tanggal' => Carbon::parse($date)->translatedFormat('d F Y'),
    //             'jam_kerja' => $duration, // jam kerja normal (jika is_lembur = false)
    //             'jam_lembur' => $overtime, // lembur (jika is_lembur = true)
    //             'rencana_kerja' => $absensi?->deskripsi_in ?? '-',
    //             'laporan_kerja' => $absensi?->deskripsi_out ?? '-',
    //             'laporan_lembur' =>  $absensi?->$deskripsiLembur ?? '-',
    //             'feedback' => $absensi?->feedback ?? '-',
    //             'is_holiday' => $this->isHoliday($date),
    //             // 'keterangan' => $keterangan,
    //             'is_lembur' => $totalOvertime > 0,
    //             'is_dinas' => $absensi?->is_dinas,
    //             'late' => $absensi?->late,
    //             'real_masuk' => optional($absensi)->time_in
    //                 ? Carbon::parse($absensi->time_in)->setTimezone('Asia/Jakarta')->format('H:i:s')
    //                 : null,

    //             'real_selesai' => optional($absensi)->time_out
    //                 ? Carbon::parse($absensi->time_out)->setTimezone('Asia/Jakarta')->format('H:i:s')
    //                 : null,

    //         ];
    //     }
    // }

    public function loadData()
    {
        $this->items = [];
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->format('Y-m-d');

            $jadwalList = JadwalAbsensi::with(['shift', 'absensi'])
                ->where('user_id', $this->selectedUserId)
                ->whereDate('tanggal_jadwal', $date)
                ->get()
                ->sortBy(fn($j) => $j->shift->jam_masuk ?? '00:00:00');

            if ($jadwalList->isEmpty()) {
                $this->items[] = $this->defaultItem($date);
                continue;
            }

            // Init akumulasi
            $jamKerjaList = [];
            $jamLemburList = [];
            $rencanaKerjaList = [];
            $laporanKerjaList = [];
            $laporanLemburList = [];
            $feedbackList = [];
            $idList = [];

            $isLembur = false;
            $isDinas = false;
            $isLate = false;

            foreach ($jadwalList as $jadwal) {
                $shift = $jadwal->shift;
                $absensiItems = $jadwal->absensi;

                $workSeconds = 0;
                $lemburSeconds = 0;

                foreach ($absensiItems as $absen) {
                    $in = $absen->time_in ? Carbon::parse($absen->time_in) : null;
                    $out = $absen->time_out ? Carbon::parse($absen->time_out) : null;

                    if ($in && $out) {
                        $duration = $in->diffInSeconds($out);
                        if ($absen->is_lembur) {
                            $lemburSeconds += $duration;
                            $isLembur = true;
                        } else {
                            $workSeconds += $duration;
                        }

                        if ($absen->is_dinas) $isDinas = true;
                        if ($absen->late) $isLate = true;
                    }

                    // Gabung field
                    if ($absen->deskripsi_in) $rencanaKerjaList[] = $absen->deskripsi_in;
                    if ($absen->deskripsi_out) $laporanKerjaList[] = $absen->deskripsi_out;
                    if ($absen->feedback) $feedbackList[] = $absen->feedback;
                    if ($absen->is_lembur && $absen->deskripsi_lembur)
                        $laporanLemburList[] = $absen->deskripsi_lembur;

                    if ($absen->id) $idList[] = $absen->id;
                }

                $jamKerjaList[] = gmdate('H:i:s', $workSeconds);
                $jamLemburList[] = gmdate('H:i:s', $lemburSeconds);
            }

            $this->items[] = [
                'id' => implode(',', $idList),
                'hari' => Carbon::parse($date)->locale('id')->isoFormat('dddd'),
                'tanggal' => Carbon::parse($date)->translatedFormat('d F Y'),
                'jam_kerja' => implode('<br>', $jamKerjaList),
                'jam_lembur' => implode('<br>', $jamLemburList),
                'rencana_kerja' => implode('<br>', $rencanaKerjaList) ?: '-',
                'laporan_kerja' => implode('<br>', $laporanKerjaList) ?: '-',
                'laporan_lembur' => implode('<br>', $laporanLemburList) ?: '-',
                'feedback' => implode('<br>', $feedbackList) ?: '-',
                'is_holiday' => $this->isHoliday($date),
                'is_lembur' => $isLembur,
                'is_dinas' => $isDinas,
                'late' => $isLate,
                'real_masuk' => null, // Jika ingin ditampilkan gabungan jam, bisa buat juga
                'real_selesai' => null,
            ];
        }
    }


    private function defaultItem($date)
    {
        return [
            'id' => null,
            'hari' => Carbon::parse($date)->locale('id')->isoFormat('dddd'),
            'tanggal' => Carbon::parse($date)->translatedFormat('d F Y'),
            'jam_kerja' => '00:00:00',
            'jam_lembur' => '00:00:00',
            'rencana_kerja' => '-',
            'laporan_kerja' => '-',
            'laporan_lembur' => '-',
            'feedback' => '-',
            'is_holiday' => $this->isHoliday($date),
            'is_lembur' => false,
            'is_dinas' => false,
            'late' => false,
            'real_masuk' => null,
            'real_selesai' => null,
        ];
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
            "Laporan Absensi {$user->name} Bulan {$month} Tahun {$year}.pdf"
        );
    }

    public function exportExcelHistory()
    {
        $month = Carbon::createFromFormat('m', $this->month)->locale('id')->translatedFormat('F');
        $year = $this->year;
        $user = User::find($this->selectedUserId);
        $title = "Bulan $month Tahun $year";
        $items = $this->items;

        return Excel::download(new AbsensiExport($items, $user, $title), "Laporan_Absensi_{$user->name}_{$month}_{$year}.xlsx");
    }

    public function render()
    {
        return view('livewire.aktivitas-absensi', [
            'bulanOptions' => range(1, 12),
            'tahunOptions' => range(now()->year - 5, now()->year)
        ]);
    }
}
