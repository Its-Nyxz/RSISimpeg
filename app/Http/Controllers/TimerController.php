<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Http\Request;
use App\Services\Logger\TimerLogger;

class TimerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $now = now()->setTimezone('Asia/Jakarta');

        // 🔍 Ambil absen aktif dalam 48 jam terakhir
        $absenAktif = Absen::where('user_id', $user->id)
            ->whereNull('time_out')
            ->where('time_in', '>=', $now->copy()->subHours(48))
            ->with(['jadwal.shift'])
            ->latest('time_in')
            ->first();

        if ($absenAktif && $absenAktif->jadwal && $absenAktif->jadwal->shift) {
            $jadwal = $absenAktif->jadwal;

            if ($this->inShiftRange($jadwal, $now)) {
                TimerLogger::info('🧩 Absensi aktif valid ditemukan', [
                    'user_id' => $user->id,
                    'jadwal_id' => $jadwal->id,
                    'time_in' => $absenAktif->time_in,
                ]);

                $jadwals = collect([$jadwal]);
                $jadwal_id = $jadwal->id;

                return view('timer.index', [
                    'jadwals' => $jadwals,
                    'jadwal_id' => $jadwal_id,
                    'timeIn' => $absenAktif?->time_in, // TAMBAHAN
                ]);
            }
        }

        // 🕒 Ambil jadwal hari ini & kemarin (antisipasi shift malam)
        $jadwals = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', '>=', $now->copy()->subDay()->toDateString())
            ->whereDate('tanggal_jadwal', '<=', $now->toDateString())
            ->with('shift')
            ->orderBy('tanggal_jadwal', 'desc')
            ->get();

        if ($jadwals->isEmpty()) {
            TimerLogger::warning('⚠️ Tidak ada jadwal ditemukan', ['user_id' => $user->id]);
            return view('timer.index', ['jadwals' => collect(), 'jadwal_id' => null]);
        }

        // 📋 Ambil semua jadwal_id yang punya absen aktif
        $absenAktifIds = Absen::where('user_id', $user->id)
            ->whereNull('time_out')
            ->pluck('jadwal_id')
            ->toArray();

        // 🔎 Filter jadwal yang masih aktif
        $jadwals = $jadwals->filter(function ($jadwal) use ($now, $user, $absenAktifIds) {
            if (!$jadwal->shift)
                return false;

            // Jika absen aktif masih berjalan
            if (in_array($jadwal->id, $absenAktifIds)) {
                TimerLogger::info('🚀 Jadwal aktif karena absensi masih berjalan', [
                    'jadwal_id' => $jadwal->id,
                    'user_id' => $user->id
                ]);
                return true;
            }

            // Cek apakah masih dalam range shift
            if ($this->inShiftRange($jadwal, $now)) {
                TimerLogger::info('✅ Jadwal masih dalam rentang jam shift', [
                    'jadwal_id' => $jadwal->id,
                    'user_id' => $user->id
                ]);
                return true;
            }

            return false;
        });

        // 🎯 Pilih jadwal aktif
        $selectedJadwal = $jadwals->first(fn($j) => $this->inShiftRange($j, $now));

        // ⏮️ Jika belum ada, coba fallback ke shift malam kemarin
        if (!$selectedJadwal) {
            $kemarin = $now->copy()->subDay()->toDateString();
            $jadwalKemarin = $user->jadwalabsensi()
                ->whereDate('tanggal_jadwal', $kemarin)
                ->with('shift')
                ->first();

            if ($jadwalKemarin && $this->inShiftRange($jadwalKemarin, $now)) {
                $selectedJadwal = $jadwalKemarin;
                TimerLogger::info('🌙 Menggunakan jadwal shift malam kemarin', [
                    'jadwal_id' => $jadwalKemarin->id,
                    'user_id' => $user->id
                ]);
            }
        }

        $jadwal_id = $selectedJadwal?->id;

        TimerLogger::info('🧭 Jadwal Terpilih', [
            'user_id' => $user->id,
            'jadwal_id' => $jadwal_id,
            'now' => $now->format('Y-m-d H:i:s'),
        ]);

        return view('timer.index', [
            'jadwals' => $jadwals,
            'jadwal_id' => $jadwal_id,
            'timeIn' => $absenAktif?->time_in, // TAMBAHAN
        ]);
    }

    /**
     * 🧮 Helper: Cek apakah waktu sekarang masih dalam range shift (termasuk malam)
     */
    private function inShiftRange($jadwal, $now): bool
    {
        if (!$jadwal->shift)
            return false;

        $tanggalJadwal = Carbon::parse($jadwal->tanggal_jadwal, 'Asia/Jakarta');
        $jamMasuk = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
        $jamKeluar = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $jadwal->shift->jam_keluar, 'Asia/Jakarta');

        // Shift malam
        if ($jamKeluar->lessThan($jamMasuk)) {
            $jamKeluar->addDay();
        }

        $jamKeluarPlusToleransi = $jamKeluar->copy()->addHours(3);

        return $now->between($jamMasuk, $jamKeluarPlusToleransi);
    }
}
