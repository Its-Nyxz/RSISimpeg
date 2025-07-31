<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


class TimerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $jadwals = $user->jadwalabsensi()
            ->whereDate('tanggal_jadwal', now()->toDateString())
            ->with('shift')
            ->get();

        $selectedJadwal = $request->get('jadwal_id')
            ? $jadwals->firstWhere('id', $request->get('jadwal_id'))
            : $jadwals->first();

        $jadwal_id = $selectedJadwal?->id;
        // dd($jadwal_id);

        return view('timer.index', compact('jadwals', 'jadwal_id'));
    }
}
