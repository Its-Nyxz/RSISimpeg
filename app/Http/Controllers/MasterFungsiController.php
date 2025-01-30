<?php

namespace App\Http\Controllers;

use App\Models\MasterFungsi;
use Illuminate\Http\Request;

class MasterFungsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('fungsional.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fungsional.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterFungsi $masterFungsi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fungsi = MasterFungsi::findOrFail($id);

        return view('fungsional.edit', compact('fungsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterFungsi $master_fungsi)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $master_fungsi->update([
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        return redirect()->route('fungsional.index')->with('success', 'Fungsional berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterFungsi $masterFungsi)
    {
        //
    }
}
