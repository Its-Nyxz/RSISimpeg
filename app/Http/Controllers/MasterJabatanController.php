<?php

namespace App\Http\Controllers;

use App\Models\MasterJabatan;
use Illuminate\Http\Request;

class MasterJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jabatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jabatan.create');
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
    public function show(MasterJabatan $masterJabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jabatan = MasterJabatan::findOrFail($id);

        return view('jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterJabatan $master_jabatan)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'kualifikasi' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $master_jabatan->update([
            'nama' => $validatedData['nama'],
            'kualifikasi' => $validatedData['kualifikasi'],
            'nominal' => $validatedData['nominal'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterJabatan $masterJabatan)
    {
        //
    }
}
