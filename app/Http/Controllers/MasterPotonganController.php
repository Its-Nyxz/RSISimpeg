<?php

namespace App\Http\Controllers;

use App\Models\MasterPotongan;
use App\Models\MasterFungsi;
use Illuminate\Http\Request;

class MasterPotonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('potongan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('potongan.create');
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
    public function show(MasterPotongan $masterPotongan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $potongan = MasterPotongan::findOrFail($id);
        $fungsi = MasterFungsi::all();

        return view('potongan.edit', compact('potongan', 'fungsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterPotongan $masterPotongan)
    {
        $validatedData = $request->validate([
            'fungsi_id' => 'required|exists:master_fungsi,id',
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $masterPotongan->update([
            'fungsi_id' => $validatedData['fungsi_id'],
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        return redirect()->route('potongan.index')->with('success', 'Potongan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPotongan $masterPotongan)
    {
        //
    }
}
