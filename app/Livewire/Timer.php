<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\Shift;
use Livewire\Component;
use App\Models\StatusAbsen;
use App\Models\JadwalAbsensi;
use App\Models\OverrideLokasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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
    public $routeIsDashboard;
    public $isLokasiDanIpTidakValid = false;

    public $latitude;
    public $longitude;

    public $akanKembali = false;

    public function mount($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;

        $this->routeIsDashboard = Request::routeIs('dashboard');

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
        if (!$this->validasiLokasiAtauIp()) return;

        if (!$this->isRunning) {
            $this->isRunning = true;
            $this->timeIn = now()->timestamp;

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
            $currentTime = Carbon::createFromTimestamp($this->timeIn);

            $canStart = $currentTime->greaterThanOrEqualTo($startShift->subMinutes(15));

            if (!$canStart) {
                $this->dispatch('alert-error', message: 'Anda hanya bisa memulai timer 15 menit sebelum waktu shift dimulai.');
                $this->isRunning = false;
                return;
            }

            $selisih = $startShift->diffInSeconds($currentTime, false);
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
            return $this->routeIsDashboard
                ? redirect()->route('dashboard') // Jika diakses dari dashboard
                : redirect()->to('/timer'); // Jika diakses dari route lain
        }
    }

    public function openWorkReportModal()
    {
        if (!$this->validasiLokasiAtauIp()) return;

        if ($this->isRunning) {
            $this->timeOut = now()->timestamp;
            $this->isRunning = false;

            $timeIn = Carbon::createFromTimestamp($this->timeIn);
            $timeOut = Carbon::createFromTimestamp($this->timeOut);

            // ✅ Hitung selisih waktu dalam detik
            $selisih = $timeIn->diffInSeconds($timeOut);
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
            // $isOvertime = $jamKerja > $shiftHours;

            // if ($isOvertime) {
            //     // ✅ Jika lembur → Tampilkan modal konfirmasi
            //     $this->showOvertimeModal = true;
            //     return;
            // }

            // ✅ Jika tidak lembur → Langsung simpan hasil kerja
            $this->completeWorkReport();
        } else {
            $this->dispatch('alert-error', message: 'Timer belum berjalan atau sudah berhenti.');
        }
    }


    public function completeWorkReport()
    {
        if (!$this->validasiLokasiAtauIp()) return;

        if (!$this->timeOut) return;

        $timeIn = Carbon::createFromTimestamp($this->timeIn);
        $timeOut = Carbon::createFromTimestamp($this->timeOut);

        if ($timeOut->lessThan($timeIn)) {
            $timeOut->addDay(); // Tambahkan satu hari jika waktu keluar lebih kecil dari waktu masuk
        }

        $selisih = $timeIn->diffInSeconds($timeOut);
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
        // $isOvertime = $jamKerja > $shiftHours;

        // ✅ Simpan data ke database
        $absensi->update([
            'time_out' => $this->timeOut,
            'deskripsi_out' => $this->deskripsi_out,
            'keterangan' => "Total waktu bekerja: " . gmdate('H:i:s', $selisih),
            // 'deskripsi_lembur' => $isOvertime ? $this->deskripsi_lembur : null,
            'status_absen_id' => $statusAbsenId // ✅ Simpan status absen ke database
        ]);

        // ✅ Reset modal dan nilai setelah menyimpan
        $this->dispatch('timer-stopped');
        $this->showStopModal = false;
        $this->showOvertimeModal = false;
        $this->deskripsi_out = null;
        // $this->deskripsi_lembur = null;

        // ✅ Redirect ke halaman utama
        return $this->routeIsDashboard
            ? redirect()->route('dashboard') // Jika diakses dari dashboard
            : redirect()->to('/timer'); // Jika diakses dari route lain
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
        return $this->routeIsDashboard
            ? redirect()->route('dashboard') // Jika diakses dari dashboard
            : redirect()->to('/timer'); // Jika diakses dari route lain
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

        if ($this->akanKembali) {
            // ✅ Jika akan kembali → hanya update keterangan (tidak isi time_out)
            Absen::updateOrCreate(
                [
                    'jadwal_id' => $this->jadwal_id,
                    'user_id' => $user->id,
                    'is_lembur' => false
                ],
                [
                    'deskripsi_in' =>  'Dinas keluar: ' . ($this->deskripsi_dinas ?? '-'),
                    'late' => false,
                    'present' => 1,
                    'status_absen_id' => 1,
                    'is_dinas' => true,
                ]
            );
        } else {
            // ✅ Tidak kembali → isi time_in & time_out sesuai shift
            Absen::updateOrCreate(
                [
                    'jadwal_id' => $this->jadwal_id,
                    'user_id' => $user->id,
                    'is_lembur' => false
                ],
                [
                    'time_in' => Carbon::parse($shift->jam_masuk),
                    'time_out' => Carbon::parse($shift->jam_keluar),
                    'deskripsi_out' => $this->deskripsi_dinas,
                    'status_absen_id' => 1,
                    'present' => 1,
                    'is_dinas' => true,
                    'keterangan' => "Dinas Keluar Terhitung Hadir dan 8 Jam kerja",
                ]
            );
        }

        $this->deskripsi_dinas = null;
        $this->showDinasModal = false;
        return $this->routeIsDashboard
            ? redirect()->route('dashboard') // Jika diakses dari dashboard
            : redirect()->to('/timer'); // Jika diakses dari route lain
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
        $this->timeInLembur = now()->timestamp;
        $this->isLemburRunning = true;

        // ✅ Simpan data lembur sebagai record baru
        Absen::create([
            'jadwal_id' => $this->jadwal_id,
            'user_id' => Auth::id(),
            'time_in' => $this->timeInLembur,
            'deskripsi_in' => 'Mulai lembur: ' .  Carbon::createFromTimestamp($this->timeInLembur)->format('H:i:s'),
            'deskripsi_lembur' => $this->deskripsi_lembur ?: '-',
            'status_absen_id' => StatusAbsen::where('nama', 'Lembur')->value('id') ?? null,
            'present' => 1,
            'is_lembur' => true // ✅ Tandai ini sebagai absen lembur
        ]);

        $this->showLemburModal = false;

        $this->dispatch('timer-lembur-started', now()->timestamp);
        $this->dispatch('alert-success', message: 'Lembur telah dimulai.');
        return $this->routeIsDashboard
            ? redirect()->route('dashboard') // Jika diakses dari dashboard
            : redirect()->to('/timer'); // Jika diakses dari route lain
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

        if ($waktuSelesaiLembur < $waktuMulaiLembur) {
            // ✅ Jika waktu selesai lebih kecil → berarti sudah melewati tengah malam
            $waktuSelesaiLembur += 86400; // Tambahkan 1 hari dalam detik (24 jam * 60 menit * 60 detik)
        }

        // Menghitung durasi lembur dalam detik
        $durasiLembur = $waktuSelesaiLembur - $waktuMulaiLembur; // Durasi dalam detik

        if ($durasiLembur <= 0) {
            $this->dispatch('alert-error', message: 'Durasi lembur tidak valid.');
            return;
        }

        $time_out_lembur = now()->timestamp;

        // ✅ Perbarui absen lembur terakhir dengan `is_lembur = true`
        $lembur = Absen::where('jadwal_id', $this->jadwal_id)
            ->where('user_id', Auth::id())
            ->where('is_lembur', true)
            ->latest()
            ->first();

        if ($lembur) {
            $lembur->update([
                'time_out' => $time_out_lembur,
                'deskripsi_out' => 'Selesai lembur: ' . Carbon::createFromTimestamp($time_out_lembur)->format('H:i:s'),
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
        return $this->routeIsDashboard
            ? redirect()->route('dashboard') // Jika diakses dari dashboard
            : redirect()->to('/timer'); // Jika diakses dari route lain
    }

    private function isMobileDevice(): bool
    {
        $agent = strtolower(request()->header('User-Agent'));

        return preg_match('/android|iphone|ipad|ipod|mobile|blackberry|windows phone/i', $agent);
    }


    private function validasiLokasiAtauIp(): bool
    {
        // ✅ Jika override aktif, lewati semua validasi lokasi
        // if (session()->pull('override_lokasi_rsi')) {
        //     return true;
        // }

        // -7.402330130327286, 109.6156227212665

        // lat long akunbiz : -7.548218078368806, 110.81261315327455
        // lat long rsi banjar:-7.4021325122156405, 109.61549352397789

        // $ipUser = request()->ip();
        // $ipUser = '192.168.100.121';
        // $ipKantor = '127.0.0.1'; // IP jaringan kantor

        // ✅ Daftar prefix IP lokal yang diizinkan (misalnya WiFi kantor dengan IP dinamis)
        // $ipPrefixWhitelist = [
        //     '180.246.120',
        //     '180.246.121',
        //     '180.247',       // Ini sudah mencakup 180.247.0.0 – 180.247.255.255
        //     '180.246',       // Ini lebih luas, semua 180.246.x.x
        //     '192.168.100', // artinya IP seperti 192.168.100.xxx akan lolos
        //     '192.168.31', // artinya IP seperti 192.168.31.xxx akan lolos
        //     '192.168.1',    // cadangan jika router di-reset
        //     '10.0.0',       // jika pakai Biznet
        //     '192.168.8',   // Orbit
        //     '1.1',        // artinya IP seperti 1.1.1.1 atau 1.1.200.5
        //     '1.11',       // untuk 1.11.x.x
        // ];

        // $ipPrefix = implode('.', array_slice(explode('.', $ipUser), 0, 2)); // hasil: 192.168.100

        $lokasiKantor = [
            'lat' => -7.402330130327286,
            'lng' => 109.6156227212665
        ];

        $polygon = [
            [-7.401462324660784, 109.61574443318705],
            [-7.40206468637885, 109.61591235565817],
            [-7.401966177920016, 109.61618451323585],
            [-7.402782968146411, 109.6164214758092],
            [-7.403165037042953, 109.61580592184652],
            [-7.403230824029308, 109.61515910978147],
            [-7.4017712054383935, 109.61499327224521],
            [-7.40146214270284, 109.6157440761346] // titik akhir = awal
        ];

        // Jika tidak ada lokasi, tetap izinkan jika IP cocok
        // if (!$this->latitude || !$this->longitude) {
        // if ($ipUser === $ipKantor) {
        //     if (in_array($ipPrefix, $ipPrefixWhitelist)) {
        //         return true;
        //     } else {
        //         $this->dispatch('alert-error', message: 'Lokasi terlalu jauh atau Anda bukan dari jaringan RSI Banjarnegara.');
        //         return false;
        //     }
        // }

        // $jarak = $this->hitungJarakMeter(
        //     $this->latitude,
        //     $this->longitude,
        //     $lokasiKantor['lat'],
        //     $lokasiKantor['lng']
        // );
        // dd($this->latitude, $this->longitude, $jarak, in_array($ipPrefix, $ipPrefixWhitelist), $ipUser, $ipPrefix, $ipPrefixWhitelist, !in_array($ipPrefix, $ipPrefixWhitelist), ($jarak > 100 || !in_array($ipPrefix, $ipPrefixWhitelist)));

        // if ($jarak > 100 || $ipUser !== $ipKantor) {
        // if ($jarak > 100 || !in_array($ipPrefix, $ipPrefixWhitelist)) {
        //     $this->dispatch('alert-error', message: 'Anda tidak berada di lokasi atau jaringan RSI Banjarnegara.');
        //     // $this->dispatch('alert-error', message: 'Anda tidak berada di lokasi RSI Banjarnegara.');
        //     return false;
        // }

        $lokasiValid = $this->isPointInPolygon($this->latitude, $this->longitude, $polygon);

        // if (!$lokasiValid && !in_array($ipPrefix, $ipPrefixWhitelist)) {
        if (!$lokasiValid) {
            $this->dispatch('alert-error', message: 'Anda tidak berada di area RSI Banjarnegara.');
            return false;
        }


        $user = auth()->user()->load(['kategorijabatan', 'kategorifungsional']);

        // Tambahkan deteksi perangkat
        if (!$this->isMobileDevice()) {
            $this->dispatch('alert-error', message: 'Absensi hanya diperbolehkan dari perangkat mobile.');
            return false;
        }


        $polygons = [
            'RSI' => [
                [-7.401462324660784, 109.61574443318705],
                [-7.40206468637885, 109.61591235565817],
                [-7.401966177920016, 109.61618451323585],
                [-7.402782968146411, 109.6164214758092],
                [-7.403165037042953, 109.61580592184652],
                [-7.403230824029308, 109.61515910978147],
                [-7.4017712054383935, 109.61499327224521],
                [-7.40146214270284, 109.6157440761346]
            ],
            'Poliklinik' => [
                [-7.401821225185401, 109.61501131827964],
                [-7.402030471704805, 109.61503914309628],
                [-7.401977585231165, 109.61537304089137],
                [-7.401747643968704, 109.61530347885127],
                [-7.401821225185401, 109.61501131827964]
            ],
            'Assalam' => [
                [-7.402324796309088, 109.61547042774959],
                [-7.402485754994245, 109.61550289003463],
                [-7.402446665033025, 109.61564433285128],
                [-7.402306401026351, 109.61560723309623],
                [-7.402324796309088, 109.61547042774959]
            ],
            'Al Zaitun' => [
                [-7.402653611845423, 109.615097111463],
                [-7.4028467621173775, 109.61511334260632],
                [-7.4028145704119765, 109.61525246668793],
                [-7.402623719534873, 109.61521304819797],
                [-7.402653611845423, 109.615097111463]
            ],
            'Al Amin' => [
                [-7.402980127731013, 109.6153057975863],
                [-7.403101996273804, 109.61532898493294],
                [-7.403028415270782, 109.61549129636131],
                [-7.402936439000513, 109.61543564672803],
                [-7.402980127731013, 109.6153057975863]
            ],
            'As Syfa' => [
                [-7.402885852043113, 109.61561187056475],
                [-7.403049109930095, 109.61560259562634],
                [-7.403039912303285, 109.61578577566792],
                [-7.402883552635544, 109.61576258831974],
                [-7.402885852043113, 109.61561187056475]
            ]
        ];

        // Cek apakah user adalah Dokter Spesialis part-time

        $isDokterSpesialisParttime =
            (
                optional($user->kategorijabatan)->nama === 'Dokter Spesialis' ||
                optional($user->kategorifungsional)->nama === 'Dokter Spesialis'
            )
            && $user->jenis->nama === 'Part Time';

        // Tentukan area mana yang boleh digunakan user
        $allowedAreas = $isDokterSpesialisParttime
            ? array_keys($polygons) // semua area
            : ['RSI']; // default hanya RSI

        // Cek apakah user berada dalam area yang diizinkan
        foreach ($allowedAreas as $areaName) {
            if ($this->isPointInPolygon($this->latitude, $this->longitude, $polygons[$areaName])) {
                return true; // lokasi valid
            }
        }

        $this->dispatch('alert-error', message: 'Anda tidak berada di area absensi yang diizinkan.');
        return false;
    }

    private function hitungJarakMeter($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function isPointInPolygon($lat, $lng, array $polygon): bool
    {
        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $i++) {
            $lat_i = $polygon[$i][0];
            $lng_i = $polygon[$i][1];
            $lat_j = $polygon[$j][0];
            $lng_j = $polygon[$j][1];

            $intersect = (($lng_i > $lng) != ($lng_j > $lng)) &&
                ($lat < ($lat_j - $lat_i) * ($lng - $lng_i) / ($lng_j - $lng_i + 1e-10) + $lat_i);

            if ($intersect) {
                $inside = !$inside;
            }

            $j = $i;
        }

        return $inside;
    }


    public function render()
    {
        return view('livewire.timer');
    }
}
