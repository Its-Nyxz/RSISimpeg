<?php

namespace App\Http\Controllers;

use App\Models\MasterTrans;
use Illuminate\Http\Request;

class MasterTransController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('trans.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trans.create');
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
    public function show(MasterTrans $masterTrans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trans = MasterTrans::findOrFail($id);
        return view('trans.edit', compact('trans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterTrans $master_trans)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nom_makan' => 'required',
            'nom_transport' => 'required',
        ]);

        $master_trans->update([
            'nama' => $validatedData['nama'],
            'nom_makan' => $validatedData['nom_makan'],
            'nom_transport' => $validatedData['nom_transport'],
        ]);

        return redirect()->route('trans.index')->with('success', 'Trans berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterTrans $masterTrans)
    {
        //
    }
}
