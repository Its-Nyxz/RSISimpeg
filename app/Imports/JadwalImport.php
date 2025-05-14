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

class JadwalImport implements ToCollection
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
    public function collection(Collection $rows)
    {
        $header = $rows->first()->toArray();

        if ($header[0] === 'NO') {
            $rows->shift();
        }

        foreach ($rows as $row) {
            try {
                if (!isset($row[1]) || !isset($row[5])) {
                    continue;
                }

                $user = User::where('slug', Str::slug($row[1]))->first();


                if (!$user) {
                    Log::warning("User dengan slug '{$row[1]}' tidak ditemukan.");
                    continue;
                }

                // Gunakan bulan & tahun dari variabel yang dikirim
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $tanggal_jadwal = "{$this->tahun}-" . str_pad($this->bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);


                    $shiftCell = isset($row[4 + $day]) ? trim($row[4 + $day]) : null;

                    // Default null
                    $nama_shift = null;
                    $jam_masuk = null;
                    $jam_keluar = null;

                    // Jika bukan libur, parse nama dan jam shift dari format "Pagi (08:00 - 16:00)"
                    if ($shiftCell && $shiftCell !== 'L') {
                        if (preg_match('/^(\w)\s*\((\d{2}:\d{2}):\d{2}\s*-\s*(\d{2}:\d{2}):\d{2}\)$/i', $shiftCell, $matches)) {
                            $nama_shift = strtoupper($matches[1]);
                            $jam_masuk = Carbon::createFromFormat('H:i', $matches[2])->format('H:i:s');
                            $jam_keluar = Carbon::createFromFormat('H:i', $matches[3])->format('H:i:s');
                        } else {
                            // Fallback jika tidak cocok formatnya
                            $nama_shift = $shiftCell;
                        }
                    }

                    if ($nama_shift) {
                        $shift = Shift::where('nama_shift', $nama_shift)
                            ->where('jam_masuk', $jam_masuk)
                            ->where('jam_keluar', $jam_keluar)
                            ->first();

                        if ($shift) {
                            if ($user->id && $tanggal_jadwal && $shift->id) {
                                JadwalAbsensi::updateOrCreate(
                                    [
                                        'user_id' => $user->id,
                                        'tanggal_jadwal' => $tanggal_jadwal
                                    ],
                                    [
                                        'shift_id' => $shift->id
                                    ]
                                );
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error saat import jadwal: " . $e->getMessage());
                continue;
            }
        }

        return redirect()->back()->with('success', 'Jadwal berhasil diimport!');
    }
}
