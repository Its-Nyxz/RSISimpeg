<?php

namespace App\Http\Controllers;

use App\Models\MasterGapok;
use App\Models\MasterGolongan;
use Illuminate\Http\Request;

class MasterGapokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gapok.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gapok.create');
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
    public function show(MasterGapok $master_gapok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
// MasterGapokController.php
public function edit($id)
{
    $gapok = MasterGapok::findOrFail($id);
    $golongan = MasterGolongan::all(); // Ambil semua golongan untuk dropdown

    return view('gapok.edit', compact('gapok', 'golongan'));
}

public function update(Request $request, MasterGapok $master_gapok)
{
    // Validasi input form
    $validatedData = $request->validate([
        'gol_id' => 'required|exists:master_golongan,id', 
        'nominal_gapok' => 'required|numeric|min:0',
    ]);

    // Update data Gapok
    $master_gapok->update([
        'gol_id' => $validatedData['golongan_id'], 
        'nominal_gapok' => $validatedData['nominal_gapok'],
    ]);

    return redirect()->route('gapok.index')->with('success', 'Gaji Pokok berhasil diperbarui!');
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterGapok $master_gapok)
    {
        //
    }
}
