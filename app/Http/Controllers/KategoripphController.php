<?php

namespace App\Http\Controllers;

use App\Models\Kategoripph;
use App\Http\Requests\StoreKategoripphRequest;
use App\Http\Requests\UpdateKategoripphRequest;

class KategoripphController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pph.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $pph = 0)
    {
        return view('pph.create', compact('tipe', 'pph'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoripphRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view('pph.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategoripph $kategoripph)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoripphRequest $request, Kategoripph $kategoripph)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategoripph $kategoripph)
    {
        //
    }
}
