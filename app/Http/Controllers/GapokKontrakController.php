<?php

namespace App\Http\Controllers;

use App\Models\GapokKontrak;
use App\Http\Requests\StoreGapokKontrakRequest;
use App\Http\Requests\UpdateGapokKontrakRequest;

class GapokKontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gapokkontrak.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $kontrak = 0)
    {
        return view('gapokkontrak.create', compact('tipe', 'kontrak'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGapokKontrakRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GapokKontrak $gapokKontrak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GapokKontrak $gapokKontrak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGapokKontrakRequest $request, GapokKontrak $gapokKontrak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GapokKontrak $gapokKontrak)
    {
        //
    }
}
