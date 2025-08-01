<?php

namespace App\Http\Controllers;

use App\Models\OverrideLokasi;
use App\Http\Requests\StoreOverrideLokasiRequest;
use App\Http\Requests\UpdateOverrideLokasiRequest;

class OverrideLokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('overridelokasi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOverrideLokasiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OverrideLokasi $overrideLokasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OverrideLokasi $overrideLokasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOverrideLokasiRequest $request, OverrideLokasi $overrideLokasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OverrideLokasi $overrideLokasi)
    {
        //
    }
}
