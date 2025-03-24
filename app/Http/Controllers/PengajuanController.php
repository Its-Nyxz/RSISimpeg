<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tipe)
    {
        if (!in_array($tipe, ['cuti', 'ijin', 'tukar_jadwal'])) {
            abort(404);
        }

        return view('pengajuan.index', compact('tipe'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe)
    {
        if (!in_array($tipe, ['cuti', 'ijin', 'tukar_jadwal'])) {
            abort(404);
        }

        return view('pengajuan.create', compact('tipe'));
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
    public function show(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        //
    }
}
