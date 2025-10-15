<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Http\Request;


class TimerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $now = now()->setTimezone('Asia/Jakarta');

        /**
         * 1️⃣ Cek apakah ada absensi aktif (time_out masih null) yang masih relevan
         * - Maksimum 24 jam terakhir
         * - Jadwal masih dalam rentang waktu shift + toleransi
         */
        $absenAktif = Absen::where('user_id', $user->id)
            ->whereNull('time_out')
            ->where('time_in', '>=', $now->copy()->subHours(24)) // hanya 24 jam terakhir
            ->with(['jadwal.shift'])
            ->latest('time_in')
            ->first();

        if ($absenAktif && $absenAktif->jadwal && $absenAktif->jadwal->shift) {
            $jadwal = $absenAktif->jadwal;
            $shift = $jadwal->shift;

            $tanggalJadwal = Carbon::parse($jadwal->tanggal_jadwal, 'Asia/Jakarta');
            $jamMasuk = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $shift->jam_masuk, 'Asia/Jakarta');
            $jamKeluar = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $shift->jam_keluar, 'Asia/Jakarta');

            if ($jamKeluar->lessThan($jamMasuk)) {
                $jamKeluar->addDay(); // shift malam
            }

            $jamKeluarPlusToleransi = $jamKeluar->copy()->addHours(3);

            // Jika absensi masih dalam jendela shift malam
            if ($now->lessThanOrEqualTo($jamKeluarPlusToleransi)) {
                logger('🧩 Absensi aktif valid ditemukan', [
                    'jadwal_id' => $absenAktif->jadwal_id,
                    'time_in' => $absenAktif->time_in,
                ]);

                $jadwals = collect([$jadwal]);
                $jadwal_id = $jadwal->id;

                return view('timer.index', compact('jadwals', 'jadwal_id'));
            }
        }

        /**
         * 2️⃣ Jika tidak ada absensi aktif, ambil jadwal hari ini & kemarin
         * (antisipasi shift malam)
         */
        $jadwals = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', '>=', $now->copy()->subDay()->toDateString())
            ->whereDate('tanggal_jadwal', '<=', $now->toDateString())
            ->with('shift')
            ->get();

        /**
         * 3️⃣ Filter jadwal yang aktif sekarang
         */
        $jadwals = $jadwals->filter(function ($jadwal) use ($now, $user) {
            if (!$jadwal->shift) return false;

            $tanggalJadwal = Carbon::parse($jadwal->tanggal_jadwal, 'Asia/Jakarta');
            $jamMasuk = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
            $jamKeluar = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $jadwal->shift->jam_keluar, 'Asia/Jakarta');

            if ($jamKeluar->lessThan($jamMasuk)) {
                $jamKeluar->addDay(); // shift malam
            }

            $jamKeluarPlusToleransi = $jamKeluar->copy()->addHours(3);

            // Jika absensi aktif di jadwal ini → tetap aktif
            $absenAktif = Absen::where('jadwal_id', $jadwal->id)
                ->where('user_id', $user->id)
                ->whereNull('time_out')
                ->exists();

            if ($absenAktif) {
                logger('🚀 Jadwal aktif karena absensi masih berjalan', ['jadwal_id' => $jadwal->id]);
                return true;
            }

            // Jika sekarang masih dalam jam shift (termasuk toleransi)
            if ($now->between($jamMasuk, $jamKeluarPlusToleransi)) {
                logger('✅ Jadwal masih dalam rentang jam shift', ['jadwal_id' => $jadwal->id]);
                return true;
            }

            return false;
        });

        /**
         * 4️⃣ Pilih jadwal aktif (atau dari dropdown)
         */
        $selectedJadwal = $request->get('jadwal_id')
            ? $jadwals->firstWhere('id', $request->get('jadwal_id'))
            : $jadwals->first();

        $jadwal_id = $selectedJadwal?->id;

        logger('🧭 Jadwal Terpilih', [
            'user_id' => $user->id,
            'jadwal_id' => $jadwal_id,
            'now' => $now->format('Y-m-d H:i:s'),
        ]);

        return view('timer.index', compact('jadwals', 'jadwal_id'));
    }
}
