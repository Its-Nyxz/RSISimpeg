<?php

namespace App\Http\Controllers;

use App\Models\ProposionalitasPoint;
use App\Http\Requests\StoreProposionalitasPointRequest;
use App\Http\Requests\UpdateProposionalitasPointRequest;

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
        //
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
    public function edit(ProposionalitasPoint $proposionalitasPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposionalitasPointRequest $request, ProposionalitasPoint $proposionalitasPoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposionalitasPoint $proposionalitasPoint)
    {
        //
    }
}
