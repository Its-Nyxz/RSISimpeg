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

class JadwalSheetImport implements ToCollection
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection(Collection $rows)
    {
        $header = $rows->first()->toArray();
        // Log::info("Cek baris pertama: " . json_encode($header));

        if (strtoupper(trim($header[0])) === 'NO') {
            $rows->shift(); // hapus header
        }

        foreach ($rows as $row) {
            try {
                // if (empty($row[1])) {
                //     Log::warning("Baris nama kosong: " . json_encode($row));
                //     continue;
                // }

                $user = User::where('slug', Str::slug($row[1]))->first();
                // if (!$user) {
                //     Log::warning("User tidak ditemukan: " . $row[1]);
                //     continue;
                // }

                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $tanggal_jadwal = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day);
                    $cellValue = isset($row[4 + $day]) ? trim((string)$row[4 + $day]) : '';
                    $shiftCell = preg_replace('/\s*\(-\)/', '', $cellValue);

                    $shift = null;

                    if ($shiftCell === '' || strtoupper($shiftCell) === 'L') {
                        $shift = Shift::firstOrCreate(
                            [
                                'unit_id' => $user->unit_id,
                                'nama_shift' => 'L',
                                'jam_masuk' => null,
                                'jam_keluar' => null,
                            ],
                            ['keterangan' => 'Libur']
                        );
                    } else {
                        // Format seperti "P (07:30:00-14:30:00)"
                        if (preg_match('/^(\w)\s*\((\d{2}:\d{2})(?::\d{2})?\s*-\s*(\d{2}:\d{2})(?::\d{2})?\)$/i', $shiftCell, $matches)) {
                            $namaShift = strtoupper($matches[1]);

                            // Gunakan format sesuai DB Anda (HH:MM)
                            $jamMasuk = substr($matches[2], 0, 5); // ambil 07:30 dari 07:30 atau 07:30:00
                            $jamKeluar = substr($matches[3], 0, 5);

                            $shift = Shift::where('unit_id', $user->unit_id)
                                ->where('nama_shift', $namaShift)
                                ->where('jam_masuk', $jamMasuk)
                                ->where('jam_keluar', $jamKeluar)
                                ->first();

                            if (!$shift) {
                                Log::warning("Shift tidak ditemukan: $namaShift ($jamMasuk - $jamKeluar) untuk user {$user->name}");
                            }
                        } else {
                            $shift = Shift::where('unit_id', $user->unit_id)
                                ->where('nama_shift', strtoupper($shiftCell))
                                ->first();
                        }
                    }

                    if ($shift) {
                        $existing = JadwalAbsensi::where('user_id', $user->id)
                            ->where('tanggal_jadwal', $tanggal_jadwal)
                            ->first();

                        $isShiftL = strtoupper($shift->nama_shift) === 'L';

                        if (!$existing) {
                            // Belum ada jadwal, buat baru
                            JadwalAbsensi::create([
                                'user_id' => $user->id,
                                'tanggal_jadwal' => $tanggal_jadwal,
                                'shift_id' => $shift->id,
                            ]);
                        } else {
                            $existingShift = $existing->shift;
                            $existingIsL = strtoupper(optional($existingShift)->nama_shift) === 'L';

                            // Update hanya jika:
                            // 1. Shift sebelumnya L dan yang baru bukan L
                            // 2. Shift sebelumnya bukan L dan yang baru bukan L
                            if (($existingIsL && !$isShiftL) || (!$existingIsL && !$isShiftL)) {
                                $existing->update([
                                    'shift_id' => $shift->id,
                                ]);
                            }
                            // selain itu: skip update
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Import Gagal: " . $e->getMessage());
            }
        }
    }
}
