<?php

namespace App\Http\Controllers;

use App\Models\MasaKerja;
use App\Http\Requests\StoreMasaKerjaRequest;
use App\Http\Requests\UpdateMasaKerjaRequest;

class MasaKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(view: "masakerja.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(view: "masakerja.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMasaKerjaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MasaKerja $masaKerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $masakerja = MasaKerja::findOrFail($id);
        return view('masakerja.edit', compact('masakerja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMasaKerjaRequest $request, MasaKerja $masaKerja)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'point' => 'required',
        ]);

        $masaKerja->update([
            'nama' => $validatedData['nama'],
            'point' => $validatedData['point'],
        ]);

        return redirect()->route('masakerja.index')->with('success', 'Masa Kerja berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasaKerja $masaKerja)
    {
        //
    }
}
