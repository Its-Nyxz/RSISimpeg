<?php

namespace App\Http\Controllers;

use App\Models\KategoriJabatan;
use App\Http\Requests\StoreKategoriJabatanRequest;
use App\Http\Requests\UpdateKategoriJabatanRequest;

class KategoriJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('kategorijabatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategorijabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriJabatanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriJabatan $kategoriJabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $katjab = KategoriJabatan::findOrFail($id);

        return view('kategorijabatan.edit', compact('katjab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriJabatanRequest $request, KategoriJabatan $kategoriJabatan)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tunjangan' => 'required|in:jabatan,fungsi,umum',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $kategoriJabatan->update([
            'nama' => $validatedData['nama'],
            'tunjangan' => $validatedData['tunjangan'],
            'keterangan' => $validatedData['keterangan'],
        ]);

        return redirect()->route('katjab.index')->with('success', 'Kategori Jabatan berhasil diperbarui!');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriJabatan $kategoriJabatan)
    {
        //
    }
}
