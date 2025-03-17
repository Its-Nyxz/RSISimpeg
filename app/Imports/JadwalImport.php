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
    public function collection(Collection $rows)
    {
        // Ambil header dari file excel (hilangkan row pertama jika itu header)
        $header = $rows->first()->toArray();

        // Hilangkan header jika memang sudah terbaca
        if ($header[0] === 'NO') {
            $rows->shift(); // Hapus header
        }

        foreach ($rows as $row) {
            try {
                // Pastikan data user tidak null
                if (!isset($row[1]) || !isset($row[5])) {
                    continue; // Lewatkan jika data kosong
                }

                // Cari user berdasarkan slug (bukan nama)
                $user = User::where('slug', Str::slug($row[1]))->first();

                if (!$user) {
                    Log::warning("User dengan slug '{$row[1]}' tidak ditemukan.");
                    continue;
                }

                // Proses untuk seluruh tanggal dalam satu bulan
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, now()->month, now()->year);

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $tanggal_jadwal = now()->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

                    $nama_shift = $row[4 + $day] ?? null; // Ambil nama shift untuk tanggal ke-n

                    if ($nama_shift) {
                        // Cari shift berdasarkan nama shift
                        $shift = Shift::where('nama_shift', $nama_shift)->first();

                        if ($shift) {
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
            } catch (\Exception $e) {
                Log::error("Error saat import jadwal: " . $e->getMessage());
                continue;
            }
        }
        // Redirect dengan notifikasi sukses jika tidak ada error
        return redirect()->back()->with('success', 'Jadwal berhasil diimport!');
    }
}
