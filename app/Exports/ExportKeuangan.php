<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Potongan;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

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
            'kategorijabatan',
            'kategorifungsional',
            'gajiBruto' => fn($q) => $q->where('bulan_penggajian', $this->bulan)->where('tahun_penggajian', $this->tahun),
            'gajiBruto.potongan.masterPotongan'
        ])
            ->when($this->unitId, fn($q) => $q->where('unit_id', $this->unitId))
            ->when($this->jenisId, fn($q) => $q->where('jenis_id', $this->jenisId))
            ->when($this->keyword, fn($q) => $q->where('name', 'like', "%{$this->keyword}%"))
            ->where('id', '>', 1)
            ->get();

        $users->each(function ($user) {
            $bruto = $user->gajiBruto->first();
            $user->total_bruto = $bruto?->total_bruto ?? 0;
            $user->total_potongan = $bruto?->potongan->sum('nominal') ?? 0;
            $user->netto = $user->total_bruto - $user->total_potongan;
        });

        return view('exports.export-keuangan', [
            'users' => $users,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
