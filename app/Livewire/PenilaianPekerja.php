<?php

namespace App\Livewire;

use App\Models\JadwalAbsensi;
use Livewire\Component;

class PenilaianPekerja extends Component
{
    public $data; // Menyimpan data untuk view

    public function render()
    {
        return view('livewire.penilaian-pekerja', [
            'data' => $this->data,
        ]);
    }

    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $this->data = JadwalAbsensi::with(['absensi', 'user']) // Menambahkan relasi user
            ->get()
            ->map(function ($jadwal) {
                $absensiTerakhir = $jadwal->absensi->last();
                $rangeJamKerja = null;
    
                if ($absensiTerakhir) {
                    $timeIn = $absensiTerakhir->time_in ? \Carbon\Carbon::parse($absensiTerakhir->time_in) : null;
                    $timeOut = $absensiTerakhir->time_out ? \Carbon\Carbon::parse($absensiTerakhir->time_out) : null;
    
                    if ($timeIn && $timeOut) {
                        $diff = $timeIn->diff($timeOut); // Menghitung selisih waktu
    
                        // Mengonversi waktu ke dalam jam dan menit, lalu menghitung total jam
                        $totalJam = $diff->h + ($diff->i / 60); // Jam + menit yang dibagi 60 untuk dikonversi ke jam
                        
                        // Menghilangkan angka desimal jika 0 (seperti 2.00 menjadi 2)
                        $rangeJamKerja = ($totalJam == intval($totalJam)) ? intval($totalJam) : number_format($totalJam, 2); 
                    }
                }
    
                return [
                    'user_id' => $jadwal->user_id,
                    'name' => $jadwal->user->name,
                    'jabatan' => $jadwal->user->jabatan_id ?? '-', // Mengambil nama dari relasi user
                    'masa_kerja' => $jadwal->user->masa_kerja ?? '-', // Mengambil masa_kerja dari relasi user
                    'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                    'keterangan_absen' => $jadwal->keterangan_absen,
                    'range_jam_kerja' => $rangeJamKerja ?? '-',
                ];
            });
    }    

}
