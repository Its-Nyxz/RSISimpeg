<?php

namespace App\Http\Controllers;

use App\Models\MasterUmum;
use Illuminate\Http\Request;

class MasterUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('umum.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('umum.create');
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
    public function show(MasterUmum $masterUmum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $umum = MasterUmum::findOrFail($id);

        return view('umum.edit', compact('umum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterUmum $master_umum)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $master_umum->update([
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        return redirect()->route('tunjangan.index')->with('success', 'Umum berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterUmum $masterUmum)
    {
        //
    }
}
