<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        return view('izin.index');
    }
}
