<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Potongan;
use App\Models\MasterPotongan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportKeuangan implements FromView
{
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected $unitId = null,
        protected $jenisId = null,
        protected $keyword = null,
    ) {}

    public function view(): View
    {
        $users = User::with([
            'unitKerja',
            'jenis',
            'gajiBruto' => fn($q) => $q->where('bulan_penggajian', $this->bulan)->where('tahun_penggajian', $this->tahun),
            'gajiBruto.potongan.masterPotongan'
        ])
            ->when($this->unitId, fn($q) => $q->where('unit_id', $this->unitId))
            ->when($this->jenisId, fn($q) => $q->where('jenis_id', $this->jenisId))
            ->when($this->keyword, fn($q) => $q->where('name', 'like', "%{$this->keyword}%"))
            ->where('id', '>', 1)
            ->get();

        $masterPotongans = MasterPotongan::all();

        $users->each(function ($user) use ($masterPotongans) {
            $bruto = $user->gajiBruto->first();

            // Ambil semua nilai tunjangan dari model langsung
            $user->nom_gapok = $bruto?->nom_gapok ?? 0;
            $user->nom_jabatan = $bruto?->nom_jabatan ?? 0;
            $user->nom_fungsi = $bruto?->nom_fungsi ?? 0;
            $user->nom_umum = $bruto?->nom_umum ?? 0;
            $user->nom_khusus = $bruto?->nom_khusus ?? 0;
            $user->nom_makan = $bruto?->nom_makan ?? 0;
            $user->nom_transport = $bruto?->nom_transport ?? 0;
            $user->nom_pj_poskes = $bruto?->nom_pj_poskes ?? 0;
            $user->nom_p_shift = $bruto?->nom_p_shift ?? 0;
            $user->nom_lainnya = $bruto?->nom_lainnya ?? 0;
            $user->total_bruto = $bruto?->total_bruto ?? 0;

            // Kompilasi potongan berdasarkan masterPotongan
            $potongan = [];

            foreach ($masterPotongans as $item) {
                $value = $bruto?->potongan
                    ->where('master_potongan_id', $item->id)
                    ->sum('nominal') ?? 0;
                $potongan[$item->nama] = $value;
            }

            $user->potongan_rinci = $potongan;
            $user->total_potongan = array_sum($potongan);
            $user->netto = $user->total_bruto - $user->total_potongan;
        });

        return view('exports.export-keuangan', [
            'users' => $users,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'masterPotongans' => $masterPotongans,
        ]);
    }
}
