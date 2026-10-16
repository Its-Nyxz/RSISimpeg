<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use Illuminate\Http\Request;

class MasterCutiController extends Controller
{
    public function index()
    {
        $jenisCutis = JenisCuti::orderBy('id')->get();

        return view('mastercuti.index', compact('jenisCutis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cuti' => 'required|string|max:255',
            'durasi_default' => 'required|integer|min:1',
            'dibayar' => 'required|boolean',
            'hanya_untuk_karyawan_tetap' => 'required|boolean',
        ]);

        $validated['aktif'] = 1;

        JenisCuti::create($validated);

        return redirect()->route('mastercuti.index')
            ->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $jenisCuti = JenisCuti::findOrFail($id);

        $validated = $request->validate([
            'nama_cuti' => 'required|string|max:255',
            'durasi_default' => 'required|integer|min:1',
            'dibayar' => 'required|boolean',
            'hanya_untuk_karyawan_tetap' => 'required|boolean',
        ]);

        $jenisCuti->update($validated);

        return redirect()->route('mastercuti.index')
            ->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function updateStatus(string $id)
    {
        $jenisCuti = JenisCuti::findOrFail($id);

        $jenisCuti->aktif = $jenisCuti->aktif ? 0 : 1;
        $jenisCuti->save();

        return redirect()->route('mastercuti.index')
            ->with('success', 'Status jenis cuti berhasil diubah.');
    }
}
