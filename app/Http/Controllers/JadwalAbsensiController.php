<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\OpsiAbsen;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JadwalAbsensi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;
use App\Http\Requests\StoreJadwalAbsensiRequest;
use App\Http\Requests\UpdateJadwalAbsensiRequest;

class JadwalAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jadwalAbsensi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $id = 0)
    {
        return view('jadwalAbsensi.create', compact('tipe', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalAbsensiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalAbsensi $jadwalAbsensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwalAbsensi = JadwalAbsensi::findOrFail($id);
        $user = User::all();
        $shift = Shift::all();
        $opsi = OpsiAbsen::all();

        return view('jadwalAbsensi.edit', compact('jadwalAbsensi', 'user', 'shift', 'opsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJadwalAbsensiRequest $request, JadwalAbsensi $jadwalAbsensi)
    {
        // Validasi input data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Validasi user
            'shift_id' => 'required|exists:shifts,id', // Validasi shift
            'opsi_id' => 'required|exists:opsi_absens,id', // Validasi opsi absensi
            'tanggal_jadwal' => 'required|date', // Validasi tanggal
            'keterangan_absen' => 'nullable|in:Cuti,Libur,Tugas,Ijin,Sakit', // Validasi keterangan
        ]);

        // Update data Jadwal Absensi
        $jadwalAbsensi->update([
            'user_id' => $validatedData['user_id'],
            'shift_id' => $validatedData['shift_id'],
            'opsi_id' => $validatedData['opsi_id'],
            'tanggal_jadwal' => $validatedData['tanggal_jadwal'],
            'keterangan_absen' => $validatedData['keterangan_absen'],
        ]);

        // Flash message untuk notifikasi
        return redirect()->route('absensi.index')->with('success', 'Jadwal berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalAbsensi $jadwalAbsensi)
    {
        //
    }

    public function export(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;
        $unitId = $request->get('unit_id');

        // Validasi Unit
        $unit = UnitKerja::find($unitId);
        if (!$unit) {
            return back()->with('error', 'Unit tidak ditemukan.');
        }

        // Validasi shift harus ada
        $shifts = Shift::where('unit_id', $unit->id)->get();
        if ($shifts->isEmpty()) {
            return back()->with('error', 'Data shift kosong. Silakan tambahkan shift terlebih dahulu.');
        }

        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');
        $fileName = 'jadwal_template_' . $unit->nama . '_' . $monthName . '_' . $year . '.xlsx';

        return Excel::download(new JadwalTemplateExport($month, $year, $unit->id), $fileName);
    }
}
