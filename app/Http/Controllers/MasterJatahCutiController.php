<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterJatahCuti;

class MasterJatahCutiController extends Controller
{
    public function index()
    {
        return view('jatahcuti.index');
    }
    public function create($tipe, $cuti = 0)
    {
        return view('jatahcuti.create', compact('tipe', 'cuti'));
    }
}
