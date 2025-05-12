<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;

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
}
