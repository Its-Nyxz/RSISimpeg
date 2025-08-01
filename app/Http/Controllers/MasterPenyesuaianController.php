<?php

namespace App\Http\Controllers;

use App\Models\MasterPenyesuaian;
use Illuminate\Http\Request;

class MasterPenyesuaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penyesuaian.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create($tipe, $penyesuaian = 0)
    {
        return view('penyesuaian.create', compact('tipe', 'penyesuaian'));
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
    public function show(MasterPenyesuaian $masterPenyesuaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterPenyesuaian $masterPenyesuaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterPenyesuaian $masterPenyesuaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPenyesuaian $masterPenyesuaian)
    {
        //
    }
}
