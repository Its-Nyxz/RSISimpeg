<?php
namespace App\Http\Controllers;

use App\Models\MasterJabatan;
use Illuminate\Http\Request;

class DetailJabatanController extends Controller
{
    public function show($id)
{
    // Ambil data jabatan berdasarkan id
    $jabatan = MasterJabatan::find($id);

    if (!$jabatan) {
        abort(404, 'Jabatan tidak ditemukan');
    }

    // Render tampilan yang berada di folder jabatanperizinan
    return view('jabatanperizinan.detail', compact('jabatan'));
}

}


