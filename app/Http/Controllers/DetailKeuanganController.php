<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DetailKeuanganController extends Controller
{
    public function show($id)
    {
        // Ambil data user berdasarkan id
        $user = User::findOrFail($id);

        // Kirim data user ke tampilan datakaryawan.detail
        return view('keuangan.detail', compact('user'));
    }
}
