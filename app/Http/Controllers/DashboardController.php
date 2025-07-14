<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\SourceFile;
use App\Models\StatusAbsen;
use App\Models\IzinKaryawan;
use Illuminate\Http\Request;
use App\Models\JadwalAbsensi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); // Ambil user yang sedang login
        $today = now();
        $bulanIni = $today->month;
        $tahunIni = $today->year;

        // Tambahkan ini supaya setiap buka dashboard langsung cek dan buatkan absen tidak masuk kalau perlu
        // $this->buatAbsenTidakMasukHariIni($user->id);


        // Ambil jadwal milik user berdasarkan tanggal hari ini
        $jadwals = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', now()->toDateString())
            ->with('shift')
            ->get();

        // Pilih salah satu jadwal
        $selectedJadwal = $request->get('jadwal_id')
            ? $jadwals->firstWhere('id', $request->get('jadwal_id'))
            : $jadwals->first();

        $jadwal_id = $selectedJadwal?->id;


        // Menyaring Sertifikat SIP/STR
        if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja?->nama === 'KEPEGAWAIAN') {
            // Super Admin atau Kepegawaian melihat semua SIP/STR
            $masaBerlakuSipStr = SourceFile::whereHas('jenisFile', function ($query) {
                $query->where('name', 'like', '%sip%')
                    ->orWhere('name', 'like', '%str%');
            })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>=', now()) // Validasi jika selesai setelah tanggal hari ini
                ->with('user', 'jenisFile')
                ->get();

            // Super Admin atau Kepegawaian melihat semua sertifikat pelatihan
            $masaBerlakuPelatihan = SourceFile::whereHas('jenisFile', function ($query) {
                $query->where('name', 'like', '%pelatihan%'); // Sertifikat pelatihan lainnya
            })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>=', now()) // Validasi jika selesai setelah tanggal hari ini
                ->with('user', 'jenisFile')
                ->get();

            // Menghitung total jumlah jam untuk pelatihan dari semua user
            $totalJumlahJamPelatihan = SourceFile::whereHas('jenisFile', function ($query) {
                $query->where('name', 'like', '%pelatihan%'); // Sertifikat pelatihan lainnya
            })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>=', now())
                ->sum('jumlah_jam'); // Menghitung total jumlah jam pelatihan
        } else {
            // Untuk user biasa, hanya melihat SIP/STR dan Sertifikat Pelatihan milik mereka sendiri
            $masaBerlakuSipStr = SourceFile::where('user_id', auth()->id())
                ->whereHas('jenisFile', function ($query) {
                    $query->where('name', 'like', '%sip%')
                        ->orWhere('name', 'like', '%str%');
                })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>=', now()) // Validasi jika selesai setelah tanggal hari ini
                ->with('jenisFile')
                ->get();

            // Sertifikat Pelatihan milik user sendiri
            $masaBerlakuPelatihan = SourceFile::where('user_id', auth()->id())
                ->whereHas('jenisFile', function ($query) {
                    $query->where('name', 'like', '%pelatihan%');
                })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>=', now()) // Validasi jika selesai setelah tanggal hari ini
                ->with('jenisFile')
                ->get();

            // Menghitung total jumlah jam untuk pelatihan milik user sendiri
            $totalJumlahJamPelatihan = SourceFile::where('user_id', auth()->id())
                ->whereHas('jenisFile', function ($query) {
                    $query->where('name', 'like', '%pelatihan%');
                })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>=', now())
                ->sum('jumlah_jam'); // Menghitung total jumlah jam pelatihan
        }

        // Ambil sisa cuti tahunan user berdasarkan tahun sekarang
        $sisaCutiTahunan = $user->sisaCutiTahunan()
            ->where('tahun', now()->year)
            ->first();

        // Jika ketemu, ambil jumlah cutinya, kalau tidak ketemu set 0
        $sisaCutiTahunan = $sisaCutiTahunan ? $sisaCutiTahunan->sisa_cuti : 0;

        $jumlahKeterlambatan = $user->absen()
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->where('late', 1)
            ->count();
        // Jumlah Izin per kategori
        $jumlahIzin = [
            'sakit' => $this->hitungIzin($user->id, [1], $bulanIni, $tahunIni),
            'tugas' => $this->hitungIzin($user->id, [2], $bulanIni, $tahunIni),
            'keluarga' => $this->hitungIzin($user->id, [3, 4, 5, 6, 7], $bulanIni, $tahunIni),
        ];

        $jumlahTanpaKeterangan = $this->hitungTanpaKeterangan($user->id, $bulanIni, $tahunIni);

        return view('dashboard.index', compact(
            'jadwals',
            'jadwal_id',
            'selectedJadwal',
            'masaBerlakuSipStr',
            'masaBerlakuPelatihan',
            'totalJumlahJamPelatihan',
            'sisaCutiTahunan',
            'jumlahKeterlambatan',
            'jumlahIzin',
            'jumlahTanpaKeterangan'
        ));
    }
    private function hitungIzin($userId, $jenisIzinIds, $bulan, $tahun)
    {
        return IzinKaryawan::where('user_id', $userId)
            ->whereMonth('tanggal_mulai', $bulan)
            ->whereYear('tanggal_mulai', $tahun)
            ->whereIn('jenis_izin_id', $jenisIzinIds)
            ->where('status_izin_id', 1) // Tambahkan hanya status APPROVED
            ->count();
    }

    private function hitungTanpaKeterangan($userId, $bulan, $tahun)
    {
        return Absen::where('user_id', $userId)
            ->where('absent', 1) // cari yang absent = true
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();
    }

    // private function shiftTelahBerakhir($shift, $tanggalJadwal)
    // {
    //     $jamMasuk = Carbon::parse($shift->jam_masuk);
    //     $jamKeluar = Carbon::parse($shift->jam_keluar);

    //     $isShiftMalam = $jamKeluar->lessThan($jamMasuk);

    //     $shiftEnd = Carbon::parse($tanggalJadwal)
    //         ->addDays($isShiftMalam ? 1 : 0)
    //         ->setTimeFrom($jamKeluar);

    //     return now()->greaterThanOrEqualTo($shiftEnd);
    // }

    // private function buatAbsenTidakMasukHariIni($userId)
    // {
    //     $today = now()->toDateString();

    //     $jadwalUserHariIni = JadwalAbsensi::where('user_id', $userId)
    //         ->whereDate('tanggal_jadwal', $today)
    //         ->get();

    //     $statusTidakAbsen = StatusAbsen::where('nama', 'Tidak Absen')->first();
    //     $statusLibur = StatusAbsen::where('nama', 'Libur')->first(); // tambahkan ini jika kamu punya status "Libur"

    //     foreach ($jadwalUserHariIni as $jadwal) {
    //         $shift = $jadwal->shift;

    //         if (!$shift) {
    //             continue;
    //         }

    //         // Jika shift sudah selesai (termasuk shift malam)
    //         if (!$this->shiftTelahBerakhir($shift, $jadwal->tanggal_jadwal)) {
    //             continue;
    //         }

    //         // Jika belum ada absen
    //         $sudahAbsen = Absen::where('jadwal_id', $jadwal->id)
    //             ->where('user_id', $userId)
    //             ->exists();

    //         if (!$sudahAbsen) {
    //             // Jika shift adalah libur (misal: shift L)
    //             if ($shift->nama_shift === 'L') {
    //                 Absen::create([
    //                     'jadwal_id' => $jadwal->id,
    //                     'user_id' => $userId,
    //                     'time_in' => Carbon::parse('00:00:00')->timestamp,
    //                     'time_out' => Carbon::parse('00:00:00')->timestamp,
    //                     'absent' => 0,
    //                     'deskripsi_out' => 'Libur',
    //                     'status_absen_id' => $statusLibur->id ?? null,
    //                     'keterangan' => $statusLibur->keterangan ?? 'Hari Libur',
    //                     'present' => 1,
    //                 ]);
    //             } else {
    //                 // Jika tidak hadir
    //                 Absen::create([
    //                     'jadwal_id' => $jadwal->id,
    //                     'user_id' => $userId,
    //                     'time_in' => Carbon::parse('00:00:00')->timestamp,
    //                     'time_out' => Carbon::parse('00:00:00')->timestamp,
    //                     'absent' => 1,
    //                     'deskripsi_out' => 'Alpha',
    //                     'status_absen_id' => $statusTidakAbsen->id ?? 4,
    //                     'keterangan' => $statusTidakAbsen->keterangan ?? 'Tidak melakukan absensi sama sekali',
    //                     'present' => 0,
    //                 ]);
    //             }
    //         }
    //     }
    // }
}
