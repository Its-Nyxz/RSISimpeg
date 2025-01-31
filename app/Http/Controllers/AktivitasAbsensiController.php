<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AktivitasAbsensiController extends Controller
{
    public function index()
    {
        return view('aktivitasabsensi.index');
    }
}
