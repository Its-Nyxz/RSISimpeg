<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DetailKaryawanController extends Controller
{
    public function show($id)
    {
        // Ambil data user berdasarkan id
        $user = User::with('pendidikanUser')->findOrFail($id);

        // Kirim data user ke tampilan datakaryawan.detail
        return view('datakaryawan.detail', compact('user'));
    }
}


