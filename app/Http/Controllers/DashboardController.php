<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SourceFile;
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
        $jumlahKaryawanShift = User::where('type_shift', 1)
            ->orWhere('type_shift', null)
            ->where('id', '!=', 1) // Kecuali user dengan id = 1
            ->count();
        $jumlahKaryawanNonShift = User::where('type_shift', 0)
            ->where('id', '!=', 1) // Kecuali user dengan id = 1
            ->count();
        $totalKaryawanAktif = User::where('status', 0)
            ->where('id', '!=', 1) // Kecuali user dengan id = 1
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
                $item->nama = $item->jenis->nama ?? ' ';
                return $item;
            });

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


        return view('dashboard.index', compact(
            'jadwal_id',
            'totalKaryawan',
            'jumlahKaryawanShift',
            'jumlahKaryawanNonShift',
            'jumlahKaryawan',
            'masaBerlakuSipStr'
        ));
    }
}
