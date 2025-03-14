<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataKaryawanController extends Controller
{
    public function index()
    {
        return view('datakaryawan.index');
    }
    public function create()
    {
        return view('datakaryawan.create');
    }

    public function edit($id)
    {
        // Ambil data user berdasarkan id
        $user = User::with('unitKerja', 'kategorijabatan', 'golongan')->findOrFail($id);
        return view('datakaryawan.edit', compact('user'));
    }
}
