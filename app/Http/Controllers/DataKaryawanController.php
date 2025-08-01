<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\DataKaryawanImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanTemplateExport;
use App\Imports\DataKaryawanMultiSheetImport;

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

    public function export()
    {
        $filename = 'template_karyawan.xlsx';
        return Excel::download(new KaryawanTemplateExport(), $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $file = $request->file('file');
        if ($file->getClientOriginalName() !== 'template_karyawan.xlsx') {
            return back()->with('error', 'Nama file harus "template_karyawan.xlsx".');
        }

        try {
            Excel::import(new DataKaryawanMultiSheetImport, $file);
            return back()->with('success', 'Import berhasil.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
