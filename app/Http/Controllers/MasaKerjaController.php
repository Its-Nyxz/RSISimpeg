<?php

namespace App\Http\Controllers;

use App\Models\MasaKerja;
use App\Http\Requests\StoreMasaKerjaRequest;
use App\Http\Requests\UpdateMasaKerjaRequest;

class MasaKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(view: "masakerja.index");
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
    public function store(StoreMasaKerjaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MasaKerja $masaKerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasaKerja $masaKerja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMasaKerjaRequest $request, MasaKerja $masaKerja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasaKerja $masaKerja)
    {
        //
    }
}
