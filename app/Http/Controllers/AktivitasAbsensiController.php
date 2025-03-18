<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use Illuminate\Http\Request;
use App\Models\JadwalAbsensi;


class AktivitasAbsensiController extends Controller
{
    public function index()
    {
        return view('aktivitasabsensi.index');
    }

    public function create($user_id = null)
    {
        $user = null;
        $jadwal = null;

        if ($user_id) {
            $user = User::find($user_id);

            if ($user) {
                // Ambil jadwal terbaru berdasarkan user_id
                $jadwal = JadwalAbsensi::where('user_id', $user_id)
                    ->latest()
                    ->first();
            }
        }

        return view('aktivitasabsensi.create', [
            'user' => $user,
            'jadwal' => $jadwal,
        ]);
    }


    public function edit($id)
    {
        $absen = Absen::findOrFail($id);

        return view('aktivitasabsensi.edit', compact('absen'));
    }

    public function update(Request $request, Absen $absen)
    {
        $validatedData = $request->validate([
            'feedback' => 'required|string',
        ]);

        $absen->update([
            'feedback' => $validatedData['feedback'],
        ]);

        return redirect()->route('aktivitasabsensi.index')->with('success', 'Feedback absensi berhasil diperbarui.');
    }
}
