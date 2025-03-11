<?php

namespace App\Http\Controllers;

use App\Models\Holidays;
use App\Http\Requests\StoreHolidaysRequest;
use App\Http\Requests\UpdateHolidaysRequest;

class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('liburnasional.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create($tipe, $holiday = 0)
    {
        return view('liburnasional.create', compact('tipe', 'holiday'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidaysRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Holidays $holidays)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holidays $holidays)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidaysRequest $request, Holidays $holidays)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holidays $holidays)
    {
        //
    }
}
