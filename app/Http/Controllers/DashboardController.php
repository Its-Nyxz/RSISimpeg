<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Ambil user yang sedang login

        // Ambil jadwal milik user berdasarkan tanggal hari ini
        $jadwal = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', now()->toDateString()) // Cari berdasarkan tanggal hari ini
            ->first();

        // Jika tidak ada jadwal, jadwal_id akan bernilai null
        $jadwal_id = $jadwal ? $jadwal->id : null;
        // dd($jadwal_id);
        $totalKaryawan = User::count();
        $totalKaryawanAktif = User::where('status', 0)
            ->where('id', '!=', 1) // Kecuali user dengan id = 1
            ->where('role', '!=', 'superadmin') // Kecuali superadmin
            ->count();
        $totalKaryawanNonAktif = User::where('status', 1)->count();
        // Hitung jumlah karyawan berdasarkan jenis_id
        $jumlahKaryawan = User::with('jenis')
            ->select('jenis_id', DB::raw('count(*) as total'))
            ->where('id', '!=', 1) // Kecualikan user dengan id = 1
            ->where('role', '!=', 'superadmin') // Kecualikan user dengan role superadmin (jika kolom role ada)
            ->groupBy('jenis_id')
            ->get()
            ->map(function ($item) {
                $item->nama = $item->jenis->nama ?? '-';
                return $item;
            });

        return view('dashboard.index', compact('jadwal_id', 'totalKaryawan', 'totalKaryawanAktif', 'totalKaryawanNonAktif', 'jumlahKaryawan'));
    }
}
