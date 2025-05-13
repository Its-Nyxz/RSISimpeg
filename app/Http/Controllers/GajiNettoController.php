<?php

namespace App\Http\Controllers;

use App\Models\GajiNetto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GajiNettoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('slipgaji.index');
    }

    public function download($id)
    {
        $slip = GajiNetto::with(['bruto.potongan.masterPotongan', 'bruto.user.jenis'])->findOrFail($id);
        $user = $slip->bruto?->user;

        $pdf = Pdf::loadView('exports.slip-gaji', compact('slip'));

        $bulan = str_pad($slip->bruto->bulan_penggajian, 2, '0', STR_PAD_LEFT);
        $tahun = $slip->bruto->tahun_penggajian;
        $nama = str_replace(' ', '_', strtolower($user->name ?? 'pegawai'));

        $filename = "slip_gaji_{$nama}_{$bulan}_{$tahun}.pdf";

        return $pdf->download($filename);
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
    public function show(GajiNetto $gajiNetto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GajiNetto $gajiNetto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GajiNetto $gajiNetto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GajiNetto $gajiNetto)
    {
        //
    }
}
