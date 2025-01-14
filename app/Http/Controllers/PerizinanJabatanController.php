<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerizinanJabatanController extends Controller
{
    public function index()
    {
        return view(view: "jabatanperizinan.index");
    }
}
