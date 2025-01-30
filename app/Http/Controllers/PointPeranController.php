<?php

namespace App\Http\Controllers;

use App\Models\PointPeran;
use App\Http\Requests\StorePointPeranRequest;
use App\Http\Requests\UpdatePointPeranRequest;

class PointPeranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pointperan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pointperan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePointPeranRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PointPeran $pointPeran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointPeran $pointPeran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePointPeranRequest $request, PointPeran $pointPeran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointPeran $pointPeran)
    {
        //
    }
}
