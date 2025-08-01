<?php

namespace App\Http\Controllers;

use App\Exports\ExportKeuangan;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;
use App\Exports\PotonganTemplateExport;

class KeuanganController extends Controller
{
    public function index()
    {
        return view("keuangan.index");
    }
    public function potongan(User $user, $bulan, $tahun)
    {
        return view("keuangan.potongan", compact('user', 'bulan', 'tahun'));
    }

    public function export(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);
        $unitId = $request->query('unit');
        $jenisId = $request->query('jenis');
        $keyword = $request->query('keyword');

        $monthName = Carbon::createFromDate($tahun, $bulan, 1)->format('F');
        $filename = "data_keuangan_{$monthName}_{$tahun}.xlsx";

        return Excel::download(
            new ExportKeuangan($bulan, $tahun, $unitId, $jenisId, $keyword),
            $filename
        );
    }
}
