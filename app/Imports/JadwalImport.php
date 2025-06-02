<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Str;
use App\Models\JadwalAbsensi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class JadwalImport implements WithMultipleSheets
{
    /**
     * @param Collection $rows
     */

    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function sheets(): array
    {
        return [
            'Worksheet' => new JadwalSheetImport($this->bulan, $this->tahun),
        ];
    }

    // public function collection(Collection $rows)
    // {
    //     Log::info("Cek baris pertama: " . json_encode($rows->first()));

    //     $header = $rows->first()->toArray();

    //     if ($header[0] === 'NO') {
    //         $rows->shift();
    //     }

    //     foreach ($rows as $row) {
    //         try {
    //             if (!isset($row[1]) || !isset($row[5])) {
    //                 continue;
    //             }

    //             $user = User::where('slug', Str::slug($row[1]))->first();


    //             if (!$user) {
    //                 Log::warning("User dengan slug '{$row[1]}' tidak ditemukan.");
    //                 continue;
    //             }

    //             // Gunakan bulan & tahun dari variabel yang dikirim
    //             $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);

    //             for ($day = 1; $day <= $daysInMonth; $day++) {
    //                 $tanggal_jadwal = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day);
    //                 $cellValue = isset($row[4 + $day]) ? trim((string)$row[4 + $day]) : '';
    //                 $shiftCell = preg_replace('/\s*\(-\)/', '', $cellValue);

    //                 $shift = null;

    //                 // === Cell kosong atau "L" → jadikan shift Libur ===
    //                 if ($shiftCell === '' || strtoupper($shiftCell) === 'L') {
    //                     $shift = Shift::firstOrCreate(
    //                         [
    //                             'unit_id' => $user->unit_id,
    //                             'nama_shift' => 'L',
    //                             'jam_masuk' => null,
    //                             'jam_keluar' => null,
    //                         ],
    //                         ['keterangan' => 'Libur']
    //                     );
    //                 } else {
    //                     $nama_shift = null;
    //                     $jam_masuk = null;
    //                     $jam_keluar = null;

    //                     // Format lengkap: P (08:00:00 - 16:00:00)
    //                     if (preg_match('/^(\w)\s*\((\d{2}:\d{2}):\d{2}\s*-\s*(\d{2}:\d{2}):\d{2}\)$/i', $shiftCell, $matches)) {
    //                         $nama_shift = strtoupper($matches[1]);
    //                         $jam_masuk = Carbon::createFromFormat('H:i', $matches[2])->format('H:i:s');
    //                         $jam_keluar = Carbon::createFromFormat('H:i', $matches[3])->format('H:i:s');

    //                         $shift = Shift::where('unit_id', $user->unit_id)
    //                             ->where('nama_shift', $nama_shift)
    //                             ->where('jam_masuk', $jam_masuk)
    //                             ->where('jam_keluar', $jam_keluar)
    //                             ->first();
    //                     } else {
    //                         // Format hanya huruf shift, contoh: "P"
    //                         $shift = Shift::where('unit_id', $user->unit_id)
    //                             ->where('nama_shift', strtoupper($shiftCell))
    //                             ->first();
    //                     }
    //                 }

    //                 // Selalu update jika shift ditemukan
    //                 if ($shift) {
    //                     JadwalAbsensi::updateOrCreate(
    //                         [
    //                             'user_id' => $user->id,
    //                             'tanggal_jadwal' => $tanggal_jadwal,
    //                         ],
    //                         [
    //                             'shift_id' => $shift->id
    //                         ]
    //                     );
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             Log::error("Error saat import jadwal: " . $e->getMessage());
    //             continue;
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Jadwal berhasil diimport!');
    // }
}
