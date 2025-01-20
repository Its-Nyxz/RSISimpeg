<?php

namespace App\Http\Controllers;

use App\Models\MasterPendidikan;
use App\Models\MasterGolongan;
use Illuminate\Http\Request;

class MasterPendidikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pendidikan.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pendidikan.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Store logic here
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pendidikan = MasterPendidikan::findOrFail($id);
        $golongan_minimals = MasterGolongan::all();
        $golongan_maximals = MasterGolongan::all();
    
        return view('pendidikan.edit', compact('pendidikan', 'golongan_minimals', 'golongan_maximals'));
    }
    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterPendidikan $masterPendidikan)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'minim_golongan_id' => 'required|exists:master_golongan,id',
            'maxim_golongan_id' => 'required|exists:master_golongan,id',
            'deskripsi' => 'nullable|string',
        ]);

        $masterPendidikan->update([
            'nama' => $validatedData['nama'],
            'minim_gol' => $validatedData['minim_golongan_id'],
            'maxim_gol' => $validatedData['maxim_golongan_id'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        // Redirect back to the list page with a success message
        return redirect()->route('pendidikan.index')->with('success', 'Pendidikan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPendidikan $masterPendidikan)
    {
        // Destroy logic here
    }
}
