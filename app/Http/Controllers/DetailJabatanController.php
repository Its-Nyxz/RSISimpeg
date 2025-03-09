<?php
namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class DetailJabatanController extends Controller
{
    public function show($id)
    {
        // Ambil data role berdasarkan id
        $role = Role::find($id);

        if (!$role) {
            abort(404, 'Role tidak ditemukan');
        }

        // Render tampilan yang berada di folder jabatanperizinan
        return view('jabatanperizinan.detail', compact('role'));
    }
}