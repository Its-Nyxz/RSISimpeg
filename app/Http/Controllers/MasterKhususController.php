<?php

namespace App\Http\Controllers;

use App\Models\MasterKhusus;
use Illuminate\Http\Request;

class MasterKhususController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('khusus.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('khusus.create');
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
    public function show(MasterKhusus $masterKhusus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $khusus = MasterKhusus::findOrFail($id);
        return view('khusus.edit', compact('khusus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterKhusus $master_khusus)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'deskripsi' => 'required',
        ]);

        $master_khusus->update([
            'nama' => $validatedData['nama'],
            'nominal' => $validatedData['nominal'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        return redirect()->route('khusus.index')->with('success', 'Khusus berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterKhusus $masterKhusus)
    {
        //
    }
}
