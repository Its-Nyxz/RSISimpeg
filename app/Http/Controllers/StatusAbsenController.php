<?php

namespace App\Http\Controllers;

use App\Models\StatusAbsen;
use App\Http\Requests\StoreStatusAbsenRequest;
use App\Http\Requests\UpdateStatusAbsenRequest;

class StatusAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('status.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusAbsenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusAbsen $statusAbsen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $status = StatusAbsen::findOrFail($id);

        return view('status.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusAbsenRequest $request, StatusAbsen $status_absen)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
        ]);

        $status_absen->update([
            'nama' => $validatedData['nama'],
        ]);

        return redirect()->route('absensi.index')->with('success', 'Status berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusAbsen $statusAbsen)
    {
        //
    }
}
