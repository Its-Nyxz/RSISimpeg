<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class KeuanganController extends Controller
{
    public function index()
    {
        return view("keuangan.index");
    }
}