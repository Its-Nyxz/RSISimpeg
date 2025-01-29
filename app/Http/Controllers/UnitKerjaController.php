<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Http\Requests\StoreUnitKerjaRequest;
use App\Http\Requests\UpdateUnitKerjaRequest;

class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("unitkerja.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("unitkerja.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitKerjaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitKerja $unitKerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $unitkerja = UnitKerja::findOrFail($id);

        return view('unitkerja.edit', compact('unitkerja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitKerjaRequest $request, UnitKerja $unitKerja)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'kode' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $unitKerja->update([
            'nama' => $validatedData['nama'],
            'kode' => $validatedData['kode'],
            'keterangan' => $validatedData['keterangan'],
        ]);

        return redirect()->route('unitkerja.index')->with('success', 'Ddata Unit Kerja berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitKerja $unitKerja)
    {
        //
    }
}
