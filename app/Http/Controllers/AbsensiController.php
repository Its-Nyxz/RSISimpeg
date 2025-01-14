<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        return view(view: "absensi.index");
    }
}