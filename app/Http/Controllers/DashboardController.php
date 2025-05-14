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
    public function index()
    {
        $user = auth()->user(); // Ambil user yang sedang login
        $today = now();
        $bulanIni = $today->month;
        $tahunIni = $today->year;

        // Tambahkan ini supaya setiap buka dashboard langsung cek dan buatkan absen tidak masuk kalau perlu
        $this->buatAbsenTidakMasukHariIni($user->id);


        // Ambil jadwal milik user berdasarkan tanggal hari ini
        $jadwal = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', now()->toDateString()) // Cari berdasarkan tanggal hari ini
            ->first();

        // Jika tidak ada jadwal, jadwal_id akan bernilai null
        $jadwal_id = $jadwal ? $jadwal->id : null;
        // dd($jadwal_id);

        if (auth()->user()->unitKerja?->nama === 'KEPEGAWAIAN' || auth()->user()->hasRole('Super Admin')) {
            $masaBerlakuSipStr = SourceFile::whereHas('jenisFile', function ($query) {
                $query->where('name', 'like', '%sip%')
                    ->orWhere('name', 'like', '%str%');
            })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>', now())
                ->with('user', 'jenisFile')
                ->get();
        } else {
            $masaBerlakuSipStr = SourceFile::where('user_id', auth()->id())
                ->whereHas('jenisFile', function ($query) {
                    $query->where('name', 'like', '%sip%')
                        ->orWhere('name', 'like', '%str%');
                })
                ->whereNotNull('selesai')
                ->whereDate('selesai', '>', now())
                ->with('jenisFile')
                ->get();
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
            'jadwal_id',
            'masaBerlakuSipStr',
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

    private function buatAbsenTidakMasukHariIni($userId)
    {
        $today = now()->toDateString();

        $jadwalUserHariIni = JadwalAbsensi::where('user_id', $userId)
            ->whereDate('tanggal_jadwal', $today)
            ->get();

        $statusTidakAbsen = StatusAbsen::where('nama', 'Tidak Absen')->first();

        foreach ($jadwalUserHariIni as $jadwal) {
            $shift = $jadwal->shift;

            if (!$shift) {
                continue; // Jika tidak ada shift, skip
            }

            $jamMasuk = Carbon::parse($shift->jam_masuk);
            $jamKeluar = Carbon::parse($shift->jam_keluar);

            $isShiftMalam = $jamKeluar->lessThan($jamMasuk); // Apakah shift melewati tengah malam?

            // Hitung jam selesai shift
            if ($isShiftMalam) {
                // Shift malam: jam keluar di hari besok
                $shiftEnd = Carbon::parse($jadwal->tanggal_jadwal)
                    ->addDay() // tambah 1 hari
                    ->setTimeFrom($jamKeluar);
            } else {
                // Shift normal: jam keluar tetap di hari yang sama
                $shiftEnd = Carbon::parse($jadwal->tanggal_jadwal)
                    ->setTimeFrom($jamKeluar);
            }

            // Cek apakah sekarang sudah lewat jam shift selesai
            if (now()->lessThan($shiftEnd)) {
                continue; // belum saatnya buat absen tidak hadir
            }

            // Sudah lewat jam shift selesai, cek apakah user sudah absen
            $sudahAbsen = Absen::where('jadwal_id', $jadwal->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$sudahAbsen) {
                // Buat absen tidak hadir
                Absen::create([
                    'jadwal_id' => $jadwal->id,
                    'user_id' => $userId,
                    'time_in' => Carbon::parse('00:00:00')->timestamp,
                    'time_out' => Carbon::parse('00:00:00')->timestamp,
                    'absent' => 1,
                    'deskripsi_out' => 'Alpha',
                    'status_absen_id' => $statusTidakAbsen->id ?? 4,
                    'keterangan' => $statusTidakAbsen->keterangan ?? 'Tidak melakukan absensi sama sekali',
                    'present' => 0,
                ]);
            }
        }
    }
}
