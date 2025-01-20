<?php

namespace App\Http\Controllers;

use App\Models\OpsiAbsen;
use App\Http\Requests\StoreOpsiAbsenRequest;
use App\Http\Requests\UpdateOpsiAbsenRequest;

class OpsiAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('opsiabsen.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('opsiabsen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOpsiAbsenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OpsiAbsen $opsiAbsen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $opsi = OpsiAbsen::findOrFail($id);

        return view('opsiabsen.edit', compact('opsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOpsiAbsenRequest $request, OpsiAbsen $opsi_absen)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $opsi_absen->update([
            'name' => $validatedData['name'],
        ]);

        return redirect()->route('absensi.index')->with('success', 'Opsi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OpsiAbsen $opsiAbsen)
    {
        //
    }
}
