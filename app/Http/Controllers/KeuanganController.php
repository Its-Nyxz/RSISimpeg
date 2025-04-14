<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
