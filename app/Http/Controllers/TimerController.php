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
        $jadwals = $jadwals->filter(function ($jadwal) use ($now) {
            if (!$jadwal->shift) return false;

            $tanggalJadwal = Carbon::parse($jadwal->tanggal_jadwal);
            $jamMasuk = Carbon::parse($tanggalJadwal->toDateString() . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
            $jamKeluar = Carbon::parse($tanggalJadwal->toDateString() . ' ' . $jadwal->shift->jam_keluar, 'Asia/Jakarta');

            // Jika jam keluar lebih kecil dari jam masuk, berarti shift malam
            if ($jamKeluar->lessThan($jamMasuk)) {
                $jamKeluar->addDay(); // tambahkan 1 hari
            }

            // 💡 Tambahkan toleransi 2 jam setelah jam keluar
            $toleransiSelesaiShiftJam = 2;
            $jamKeluarPlusToleransi = $jamKeluar->copy()->addHours($toleransiSelesaiShiftJam);

            // Jika waktu sekarang masih antara jam masuk dan jam keluar + toleransi
            return $now->between($jamMasuk, $jamKeluarPlusToleransi);
        });

        // Pilih jadwal aktif atau yang dipilih manual lewat dropdown
        $selectedJadwal = $request->get('jadwal_id')
            ? $jadwals->firstWhere('id', $request->get('jadwal_id'))
            : $jadwals->first();

        $jadwal_id = $selectedJadwal?->id;
        // dd($jadwal_id);

        return view('timer.index', compact('jadwals', 'jadwal_id'));
    }
}
