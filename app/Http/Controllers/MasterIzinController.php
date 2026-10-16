<?php

namespace App\Http\Controllers;

use App\Models\JenisIzin;
use Illuminate\Http\Request;

class MasterIzinController extends Controller
{
    public function index()
    {
        $jenisIzins = JenisIzin::orderBy('id')->get();

        return view('masterizin.index', compact('jenisIzins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_izin' => 'required|string|max:255',
            'durasi_default' => 'required|integer|min:1',
            'dibayar' => 'required|boolean',
            'hanya_untuk_karyawan_tetap' => 'required|boolean',
        ]);

        $validated['aktif'] = 1;

        JenisIzin::create($validated);

        return redirect()->route('masterizin.index')
            ->with('success', 'Jenis izin berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        $validated = $request->validate([
            'nama_izin' => 'required|string|max:255',
            'durasi_default' => 'required|integer|min:1',
            'dibayar' => 'required|boolean',
            'hanya_untuk_karyawan_tetap' => 'required|boolean',
        ]);

        $jenisIzin->update($validated);

        return redirect()->route('masterizin.index')
            ->with('success', 'Jenis izin berhasil diperbarui.');
    }

    public function updateStatus(string $id)
    {
        $jenisIzin = JenisIzin::findOrFail($id);

        $jenisIzin->aktif = $jenisIzin->aktif ? 0 : 1;
        $jenisIzin->save();

        return redirect()->route('masterizin.index')
            ->with('success', 'Status jenis izin berhasil diubah.');
    }
}
