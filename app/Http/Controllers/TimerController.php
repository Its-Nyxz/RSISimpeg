<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


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

        // Filter jadwal agar hanya menampilkan jadwal yang masih aktif
        $jadwals = $jadwals->filter(function ($jadwal) use ($now, $user) {
            if (!$jadwal->shift) return false;

            $tanggalJadwal = Carbon::parse($jadwal->tanggal_jadwal);
            $jamMasuk = Carbon::parse($tanggalJadwal->toDateString() . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
            $jamKeluar = Carbon::parse($tanggalJadwal->toDateString() . ' ' . $jadwal->shift->jam_keluar, 'Asia/Jakarta');

            // Jika jam keluar lebih kecil dari jam masuk → shift malam
            if ($jamKeluar->lessThan($jamMasuk)) {
                $jamKeluar->addDay();
            }

            // Tambahkan toleransi 3 jam setelah jam keluar
            $toleransiSelesaiShiftJam = 3;
            $jamKeluarPlusToleransi = $jamKeluar->copy()->addHours($toleransiSelesaiShiftJam);

            // ✅ Tambahkan prioritas untuk absen aktif (time_out masih null)
            $absenAktif = Absen::where('jadwal_id', $jadwal->id)
                ->where('user_id', $user->id)
                ->whereNull('time_out')
                ->exists();

            // 🪵 Log detail
            logger('🕒 [TimerController] Cek Jadwal Aktif', [
                'user_id' => $user->id,
                'jadwal_id' => $jadwal->id,
                'shift' => $jadwal->shift->nama_shift ?? '-',
                'now' => $now->format('Y-m-d H:i:s'),
                'jam_masuk' => $jamMasuk->format('Y-m-d H:i:s'),
                'jam_keluar' => $jamKeluar->format('Y-m-d H:i:s'),
                'jam_keluar_plus_toleransi' => $jamKeluarPlusToleransi->format('Y-m-d H:i:s'),
                'absen_aktif' => $absenAktif,
            ]);

            // Jika ada absensi aktif (belum time_out), prioritas tertinggi
            if ($absenAktif) {
                logger('🚀 Jadwal dipilih karena absensi masih aktif', ['jadwal_id' => $jadwal->id]);
                return true;
            }

            // Jika waktu sekarang masih dalam rentang shift + toleransi
            if ($now->between($jamMasuk, $jamKeluarPlusToleransi)) {
                logger('✅ Jadwal masih aktif dalam rentang jam', ['jadwal_id' => $jadwal->id]);
                return true;
            }

            // Kasus khusus: shift malam (jam keluar < jam masuk)
            if ($jamKeluar->lessThan($jamMasuk)) {
                $jamKeluar->addDay(); // shift melewati tengah malam
            }

            // Tetap aktif jika sekarang < jamKeluar + toleransi
            if ($now->lessThanOrEqualTo($jamKeluarPlusToleransi)) {
                if ($now->greaterThanOrEqualTo($jamMasuk->copy()->subHours(6))) { // jaga jaga jam mulai shift malam
                    logger('🌙 Shift malam aktif hingga pagi hari', ['jadwal_id' => $jadwal->id]);
                    return true;
                }
            }

            logger('⏰ Cek waktu', [
                'now' => $now,
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => $jamKeluar,
                'jam_keluar_plus_toleransi' => $jamKeluarPlusToleransi,
            ]);

            logger('❌ Jadwal tidak aktif', ['jadwal_id' => $jadwal->id]);
            return false;
        });

        // Pilih jadwal aktif atau yang dipilih manual lewat dropdown
        $selectedJadwal = $request->get('jadwal_id')
            ? $jadwals->firstWhere('id', $request->get('jadwal_id'))
            : $jadwals->first();

        $jadwal_id = $selectedJadwal?->id;

        // Log hasil akhir
        logger('🧭 Jadwal Terpilih', [
            'user_id' => $user->id,
            'jadwal_id' => $jadwal_id,
            'now' => $now->format('Y-m-d H:i:s'),
        ]);

        // dd($jadwal_id);

        return view('timer.index', compact('jadwals', 'jadwal_id'));
    }
}
