<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataNotifikasiController extends Controller
{
    public function index()
    {
        return view('notification.index');
    }
}
