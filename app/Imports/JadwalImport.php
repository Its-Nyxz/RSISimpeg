<?php

namespace App\Imports;

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


                    $nama_shift = isset($row[4 + $day]) ? trim($row[4 + $day]) : null; // Ambil nama shift untuk tanggal ke-n

                    // Jika ditemukan 'L' (menandakan libur), maka hapus jadwal sebelumnya
                    if ($nama_shift === 'L') {
                        // Cek apakah jadwal sebelumnya ada di database
                        $existingJadwal = JadwalAbsensi::where('user_id', $user->id)
                            ->where('tanggal_jadwal', $tanggal_jadwal)
                            ->first();

                        if ($existingJadwal) {
                            // Hapus jadwal sebelumnya
                            $existingJadwal->delete();

                            Log::info("Jadwal pada tanggal {$tanggal_jadwal} untuk user {$user->id} dihapus karena libur ('L').");
                        }
                        continue;
                    }

                    if ($nama_shift) {
                        $shift = Shift::where('nama_shift', $nama_shift)->first();

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
