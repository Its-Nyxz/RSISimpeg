<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeranFungsionalController extends Controller
{
    public function index()
    {
        return view(view: "peranfungsional.index");
    }
}
