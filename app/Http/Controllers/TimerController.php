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

        // Ambil jadwal hari ini dan kemarin (antisipasi shift malam)
        $jadwals = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', '>=', $now->copy()->subDay()->toDateString())
            ->whereDate('tanggal_jadwal', '<=', $now->toDateString())
            ->with('shift')
            ->get();

        // Filter jadwal aktif
        $jadwals = $jadwals->filter(function ($jadwal) use ($now, $user) {
            if (!$jadwal->shift) return false;

            $tanggalJadwal = Carbon::parse($jadwal->tanggal_jadwal, 'Asia/Jakarta');
            $jamMasuk = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
            $jamKeluar = Carbon::parse($tanggalJadwal->format('Y-m-d') . ' ' . $jadwal->shift->jam_keluar, 'Asia/Jakarta');

            // Jika jam keluar < jam masuk → shift malam
            if ($jamKeluar->lessThan($jamMasuk)) {
                $jamKeluar->addDay(); // shift melewati tengah malam
            }

            // Tambahkan toleransi 3 jam setelah jam keluar
            // $jamKeluarPlusToleransi = $jamKeluar->copy()->addHours(3);
            $jamKeluarPlusToleransi = $jamKeluar->copy()->addHour();

            // ✅ Cek absensi aktif
            $absenAktif = Absen::where('jadwal_id', $jadwal->id)
                ->where('user_id', $user->id)
                ->whereNull('time_out')
                ->exists();

            // ✅ Jika masih ada absensi aktif → jadwal tetap aktif
            if ($absenAktif) {
                logger('🚀 Jadwal aktif karena absensi masih berjalan', ['jadwal_id' => $jadwal->id]);
                return true;
            }

            // ✅ Jika sekarang masih dalam rentang shift (termasuk jam keluar + toleransi)
            if ($now->between($jamMasuk, $jamKeluarPlusToleransi)) {
                logger('✅ Jadwal masih dalam rentang jam shift', ['jadwal_id' => $jadwal->id]);
                return true;
            }

            // ✅ Jika shift malam, tetap aktif sampai jam_keluar + toleransi
            if ($jadwal->shift->jam_keluar < $jadwal->shift->jam_masuk) {
                if ($now->lessThanOrEqualTo($jamKeluarPlusToleransi)) {
                    logger('🌙 Shift malam masih aktif hingga pagi', ['jadwal_id' => $jadwal->id]);
                    return true;
                }
            }

            return false;
        });

        // 🧩 OPSIONAL FALLBACK:
        // Jika tidak ada jadwal aktif, tapi user masih punya absensi aktif, tetap tampilkan timer
        if ($jadwals->isEmpty()) {
            $absenAktif = \App\Models\Absen::where('user_id', $user->id)
                ->whereNull('time_out')
                ->latest('time_in')
                ->with('jadwal.shift')
                ->first();

            if ($absenAktif && $absenAktif->jadwal) {
                logger('🧩 Fallback ke jadwal dari absensi aktif', ['jadwal_id' => $absenAktif->jadwal_id]);

                // Bungkus jadwal dari absen aktif agar tetap bisa dikirim ke view
                $jadwals = collect([$absenAktif->jadwal]);
            }
        }

        // Pilih jadwal aktif atau dari dropdown manual
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
