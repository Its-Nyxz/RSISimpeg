<?php

namespace App\Http\Controllers;

use App\Models\PeringatanKaryawan;
use App\Http\Requests\StorePeringatanKaryawanRequest;
use App\Http\Requests\UpdatePeringatanKaryawanRequest;

class PeringatanKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('peringatan.index');
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
    public function store(StorePeringatanKaryawanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PeringatanKaryawan $peringatanKaryawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeringatanKaryawan $peringatanKaryawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePeringatanKaryawanRequest $request, PeringatanKaryawan $peringatanKaryawan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeringatanKaryawan $peringatanKaryawan)
    {
        //
    }
}
