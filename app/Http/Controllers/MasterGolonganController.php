<?php

namespace App\Http\Controllers;

use App\Models\MasterGolongan;
use Illuminate\Http\Request;

class MasterGolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('golongan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('golongan.create');
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
    public function show(MasterGolongan $masterGolongan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterGolongan $masterGolongan)
    {
        // Mencari golongan berdasarkan ID
        $golongan = MasterGolongan::findOrFail($golonganId);

        // Mengembalikan halaman edit dengan data golongan
        return view('golongan.edit', compact('golongan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterGolongan $masterGolongan)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterGolongan $masterGolongan)
    {
        //
    }
}
