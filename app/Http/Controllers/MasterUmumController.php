<?php

namespace App\Http\Controllers;

use App\Models\MasterUmum;
use Illuminate\Http\Request;

class MasterUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('umum.index');
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
    public function show(MasterUmum $masterUmum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterUmum $masterUmum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterUmum $masterUmum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterUmum $masterUmum)
    {
        //
    }
}
