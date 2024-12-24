<?php

namespace App\Http\Controllers;

use App\Models\MasterGapok;
use Illuminate\Http\Request;

class MasterGapokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gapok.index');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterGapok $master_gapok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterGapok $master_gapok)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterGapok $master_gapok)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterGapok $master_gapok)
    {
        //
    }
}
