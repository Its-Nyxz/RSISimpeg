<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TunjanganKinerjaController extends Controller
{
    public function index()
    {
        return view(view: "tukin.index");
    }
}
