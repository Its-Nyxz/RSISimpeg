<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MasterPendidikan;

class MasterPendidikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = User::find(2);
        // dd($user->pendAwal->maximGolongan);
        return view("pendidikan.index");
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
    public function show(MasterPendidikan $masterPendidikan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterPendidikan $masterPendidikan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterPendidikan $masterPendidikan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPendidikan $masterPendidikan)
    {
        //
    }
}
