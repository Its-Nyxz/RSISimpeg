<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KenaikanBerkalaGolController extends Controller
{
    public function index()
    {
        return view(view: "kenaikan_berkala_golongan.index");
    }
}
