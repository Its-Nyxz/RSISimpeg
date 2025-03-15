<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Absen;


class AktivitasAbsensiController extends Controller
{
    public function index()
    {
        return view('aktivitasabsensi.index');
    }

    public function create()
    {
        return view('aktivitasabsensi.create');
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
