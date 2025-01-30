<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TukinJabatanController extends Controller
{
    public function index()
    {
        return view('tukinjabatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }
}
