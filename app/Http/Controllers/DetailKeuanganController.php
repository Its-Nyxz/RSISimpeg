<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Potongan;
use App\Models\GajiBruto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class DetailKeuanganController extends Controller
{
    public function show($id)
    {
        // Ambil data user berdasarkan id
        $user = User::findOrFail($id);

        // Kirim data user ke tampilan datakaryawan.detail
        return view('keuangan.detail', compact('user'));
    }

    public function export(User $user, $bulan, $tahun)
    {
        $gajiBruto = GajiBruto::with('potongan.masterPotongan')
            ->where('user_id', $user->id)
            ->where('bulan_penggajian', $bulan)
            ->where('tahun_penggajian', $tahun)
            ->first();

        $potonganList = collect();
        if ($gajiBruto) {
            $potonganList = Potongan::with('masterPotongan')
                ->where('bruto_id', $gajiBruto->id)
                ->get();
        }

        $totalPotongan = $potonganList->sum('nominal');
        $netto = ($gajiBruto->total_bruto ?? 0) - $totalPotongan;

        $pdf = Pdf::loadView('exports.detail-keuangan', compact('user', 'gajiBruto', 'potonganList', 'totalPotongan', 'netto', 'bulan', 'tahun'))
            ->setPaper('A4');

        $nama = str_replace(' ', '_', strtolower($user->name));
        $bulanNama = Carbon::createFromDate(null, (int) $bulan, 1)->locale('id')->isoFormat('MMMM');
        $filename = "slip_detail_{$nama}_{$bulanNama}_{$tahun}.pdf";

        return $pdf->download($filename);
    }
}
