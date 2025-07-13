<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jadwals = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', now()->toDateString())
            ->with(['shift', 'absensi'])
            ->orderBy('shift_id')
            ->get();

        return view('timer.index', compact('jadwals'));
    }
}
