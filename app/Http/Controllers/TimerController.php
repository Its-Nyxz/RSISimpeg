<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimerController extends Controller
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

        return view('timer.index', compact('jadwal_id'));
    }
}
