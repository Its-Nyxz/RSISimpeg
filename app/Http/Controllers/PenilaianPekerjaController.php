<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenilaianPekerjaController extends Controller
{
    public function index()
    {
        return view("penilaianpekerja.index");
    }
}
