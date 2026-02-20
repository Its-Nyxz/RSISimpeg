<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JadwalExport implements FromView, ShouldAutoSize
{
    // Tambahkan property baru
    protected $jadwals, $tanggalJadwal, $filteredShifts, $namaUnit, $monthName, $tahun;

    public function __construct($jadwals, $tanggalJadwal, $filteredShifts, $namaUnit, $monthName, $tahun)
    {
        $this->jadwals = $jadwals;
        $this->tanggalJadwal = $tanggalJadwal;
        $this->filteredShifts = $filteredShifts;
        $this->namaUnit = $namaUnit;
        $this->monthName = $monthName;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('exports.jadwal', [
            'jadwals' => $this->jadwals,
            'tanggalJadwal' => $this->tanggalJadwal,
            'filteredShifts' => $this->filteredShifts,
            'namaUnit' => $this->namaUnit,
            'monthName' => $this->monthName,
            'tahun' => $this->tahun
        ]);
    }
}
