<?php

namespace App\Http\Controllers;

use App\Models\ProposionalitasPoint;
use App\Http\Requests\StoreProposionalitasPointRequest;
use App\Http\Requests\UpdateProposionalitasPointRequest;
use App\Models\MasterFungsi;
use App\Models\MasterUmum;
use App\Models\UnitKerja;
use Request;

class ProposionalitasPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('proposionalitas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proposionalitas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProposionalitasPointRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProposionalitasPoint $proposionalitasPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proposionalitasPoint = ProposionalitasPoint::findOrFail($id);
        $proposables = MasterFungsi::with('kategorijabatan')->get()->merge(
            MasterUmum::with('kategorijabatan')->get()
        );
        $unitkerjas = UnitKerja::all();
    
        return view('proposionalitas.edit', compact('proposionalitasPoint', 'proposables', 'unitkerjas'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProposionalitasPoint $proposionalitasPoint)
    {
        $validatedData = $request->validate([
            'proposable_id' => 'required', 
            'unit_id' => 'nullable|exists:unit_kerjas,id',
            'point' => 'required|numeric',
        ]);
    
        $proposionalitasPoint->update([
            'proposable_id' => $validatedData['proposable_id'],
            'proposable_type' => get_class(MasterFungsi::find($validatedData['proposable_id']) ?? MasterUmum::find($validatedData['proposable_id'])), 
            'unit_id' => $validatedData['unit_id'],
            'point' => $validatedData['point'],
        ]);
    
        return redirect()->route('proposionalitas.index')->with('success', 'Proposionalitas berhasil diperbarui!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposionalitasPoint $proposionalitasPoint)
    {
        //
    }
}
