<?php

namespace App\Http\Controllers;

use App\Models\LevelUnit;
use App\Http\Requests\StoreLevelUnitRequest;
use App\Http\Requests\UpdateLevelUnitRequest;
use App\Models\UnitKerja;
use App\Models\LevelPoint;

class LevelUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(view: "levelunit.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(view: "levelunit.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLevelUnitRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LevelUnit $levelUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $levelunit = LevelUnit::findOrFail($id);
        $unit = UnitKerja::all();
        $level = LevelPoint::all();

        return view('levelunit.edit', compact('levelunit', 'unit', 'level'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLevelUnitRequest $request, LevelUnit $levelUnit)
    {
        $validatedData = $request->validate([
            'unit_id' => 'required|exists:unit_kerjas,id',
            'level_id' => 'required|exists:level_points,id',
        ]);

        $levelUnit->update([
            'unit_id' => $validatedData['unit_id'],
            'level_id' => $validatedData['level_id'],
        ]);

        return redirect()->route('tukin.index')->with('success', 'Level Unit berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LevelUnit $levelUnit)
    {
        //
    }
}