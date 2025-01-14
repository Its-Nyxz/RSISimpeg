<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterAbsensiController extends Controller
{
    public function index()
    {
        return view("absensi.index");
    }
}
