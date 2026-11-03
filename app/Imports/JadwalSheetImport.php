<?php

namespace App\Imports;

use App\Models\Absen;
use App\Models\CutiKaryawan;
use App\Models\JadwalAbsensi;
use App\Models\PJ;
use App\Models\Shift;
use App\Models\StatusAbsen;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection; // pastikan sudah di-import

class JadwalSheetImport implements ToCollection
{
    protected $bulan;

    protected $tahun;

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

                $daysInMonth = \Carbon\Carbon::create($this->tahun, $this->bulan, 1)->daysInMonth;

                // Kolom PJ setelah Pendidikan (index 3)
                $pjValue = isset($row[3]) ? strtolower(trim($row[3])) : '';
                $isPJ = $pjValue === 'ya' || $pjValue === 'iya';

                if ($user) {
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $tanggal_jadwal = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day);
                        // 6 kolom tetap: 0..5 → hari ke-1 ada di index 6
                        $cellIndex = 5 + $day;
                        $cellValue = isset($row[$cellIndex]) ? trim((string) $row[$cellIndex]) : '';
                        $shiftCell = preg_replace('/\s*\(-\)/', '', $cellValue);
                        $upperShift = strtoupper(trim($shiftCell));

                        // 1. Cek apakah user memiliki cuti yang disetujui di tanggal ini
                        $cutiKaryawan = CutiKaryawan::with('jenisCuti')
                            ->where('user_id', $user->id)
                            ->whereIn('status_cuti_id', [1, 4])
                            ->whereDate('tanggal_mulai', '<=', $tanggal_jadwal)
                            ->whereDate('tanggal_selesai', '>=', $tanggal_jadwal)
                            ->first();

                        $isCutiCell = in_array($upperShift, ['C', 'CUTI', 'CT']);

                        $shift = null;

                        if ($cutiKaryawan || $isCutiCell) {
                            $shift = Shift::firstOrCreate(
                                [
                                    'unit_id' => $user->unit_id,
                                    'nama_shift' => 'C',
                                ],
                                [
                                    'jam_masuk' => null,
                                    'jam_keluar' => null,
                                    'keterangan' => 'Cuti',
                                ]
                            );
                        } elseif ($shiftCell === '' || $upperShift === 'L') {
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
                            if (preg_match('/^(.+?)\s*\((\d{2}:\d{2})(?::\d{2})?\s*-\s*(\d{2}:\d{2})(?::\d{2})?\)$/', $shiftCell, $matches)) {
                                $namaShift = trim($matches[1]); // Bisa "P", "Pendek", "Shift A", dll.
                                $jamMasuk = substr($matches[2], 0, 5); // Ambil HH:MM
                                $jamKeluar = substr($matches[3], 0, 5);

                                $shift = Shift::where('unit_id', $user->unit_id)
                                    ->where('nama_shift', $namaShift)
                                    ->where('jam_masuk', $jamMasuk)
                                    ->where('jam_keluar', $jamKeluar)
                                    ->first();

                                if (! $shift) {
                                    // Log::warning("Shift tidak ditemukan: $namaShift ($jamMasuk - $jamKeluar) untuk user {$user->name}");
                                }
                            } else {
                                $shift = Shift::where('unit_id', $user->unit_id)
                                    ->where('nama_shift', $upperShift)
                                    ->first();
                            }
                        }

                        if ($shift) {
                            $existing = JadwalAbsensi::where('user_id', $user->id)
                                ->where('tanggal_jadwal', $tanggal_jadwal)
                                ->first();

                            if (! $existing) {
                                $jadwal = JadwalAbsensi::create([
                                    'user_id' => $user->id,
                                    'tanggal_jadwal' => $tanggal_jadwal,
                                    'shift_id' => $shift->id,
                                ]);
                            } else {
                                // Update kalau berbeda (termasuk ke/dari L atau C)
                                if ($existing->shift_id !== $shift->id) {
                                    $existing->update(['shift_id' => $shift->id]);
                                }
                                $jadwal = $existing;
                            }

                            // Jika shift adalah Cuti, pastikan record Absen dibuat / disinkronkan
                            if ($shift->nama_shift === 'C' && $jadwal) {
                                $cutiStatusId = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
                                $namaJenisCuti = $cutiKaryawan->jenisCuti->nama_cuti ?? 'Cuti';

                                Absen::updateOrCreate(
                                    [
                                        'user_id' => $user->id,
                                        'jadwal_id' => $jadwal->id,
                                    ],
                                    [
                                        'status_absen_id' => $cutiStatusId,
                                        'present' => 1,
                                        'absent' => 0,
                                        'late' => 0,
                                        'time_in' => null,
                                        'time_out' => null,
                                        'keterangan' => 'Cuti disetujui',
                                        'deskripsi_in' => $namaJenisCuti,
                                        'deskripsi_out' => $namaJenisCuti,
                                        'is_dinas' => false,
                                        'is_lembur' => false,
                                        'approved_lembur' => false,
                                    ]
                                );
                            }
                        }

                        // Simpan PJ (hanya jika 'ya')
                        if ($isPJ) {
                            PJ::updateOrCreate([
                                'user_id' => $user->id,
                                'assigned_at' => $tanggal_jadwal,
                            ], [
                                'is_pj' => true,
                            ]);
                        } else {
                            PJ::where('user_id', $user->id)
                                ->where('assigned_at', $tanggal_jadwal)
                                ->delete();
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Import Gagal: '.$e->getMessage());
            }
        }
    }
}
