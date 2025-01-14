<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KenaikanGolonganController extends Controller
{
    public function index()
    {
        return view("kenaikangolongan.index");
    }
}
