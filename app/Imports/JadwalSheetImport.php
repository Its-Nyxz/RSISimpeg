<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Str;
use App\Models\CutiKaryawan;
use App\Models\JadwalAbsensi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\PJ; // pastikan sudah di-import

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

        if (strtoupper(trim($header[0])) === 'NO') {
            $rows->shift(); // hapus header
        }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);
        $startDate   = sprintf('%04d-%02d-01', $this->tahun, $this->bulan);
        $endDate     = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $daysInMonth);

        // Preload seluruh user berdasarkan slug untuk menghindari query di setiap baris
        $usersBySlug = User::all()->keyBy('slug');

        // Preload cuti resmi untuk seluruh user pada bulan ini (0 query di dalam loop)
        $allCutis = CutiKaryawan::where('status_cuti_id', 1)
            ->where('tanggal_mulai', '<=', $endDate)
            ->where('tanggal_selesai', '>=', $startDate)
            ->get()
            ->groupBy('user_id');

        // Preload jadwal yang sudah ada di database untuk bulan ini (0 query di dalam loop)
        $existingJadwals = JadwalAbsensi::whereBetween('tanggal_jadwal', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($j) => $j->user_id . '_' . $j->tanggal_jadwal);

        // Preload shifts untuk in-memory lookup
        $allShifts = Shift::all();

        $getOrCreateShift = function ($unitId, $namaShift, $jamMasuk = null, $jamKeluar = null, $keterangan = null) use (&$allShifts) {
            $matched = $allShifts->first(function ($s) use ($unitId, $namaShift, $jamMasuk, $jamKeluar) {
                if ($s->unit_id != $unitId || strtoupper($s->nama_shift) !== strtoupper($namaShift)) {
                    return false;
                }
                if ($jamMasuk !== null || $jamKeluar !== null) {
                    $sMasuk = $s->jam_masuk ? substr($s->jam_masuk, 0, 5) : null;
                    $sKeluar = $s->jam_keluar ? substr($s->jam_keluar, 0, 5) : null;
                    return $sMasuk === $jamMasuk && $sKeluar === $jamKeluar;
                }
                return true;
            });

            if (!$matched) {
                $matched = Shift::firstOrCreate(
                    [
                        'unit_id' => $unitId,
                        'nama_shift' => $namaShift,
                        'jam_masuk' => $jamMasuk,
                        'jam_keluar' => $jamKeluar,
                    ],
                    ['keterangan' => $keterangan ?? $namaShift]
                );
                $allShifts->push($matched);
            }

            return $matched;
        };

        foreach ($rows as $row) {
            try {
                if (empty($row[1])) {
                    continue;
                }

                $user = $usersBySlug->get(Str::slug($row[1]));

                // Kolom PJ setelah Pendidikan (index 3)
                $pjValue = isset($row[3]) ? strtolower(trim($row[3])) : '';
                $isPJ = $pjValue === 'ya' || $pjValue === 'iya';

                if ($user) {
                    $userCutis = $allCutis->get($user->id, collect());

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $tanggal_jadwal = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day);
                        $cellIndex = 5 + $day;
                        $cellValue = isset($row[$cellIndex]) ? trim((string)$row[$cellIndex]) : '';

                        // Pecah jika ada beberapa shift di hari yang sama (misal 'P | M' atau 'P (...) | M (...)')
                        $rawParts = explode('|', $cellValue);
                        $resolvedShifts = [];

                        foreach ($rawParts as $part) {
                            $partTrimmed = trim($part);
                            $shiftCell = trim(preg_replace('/\s*\([-]+\)/', '', $partTrimmed));

                            if ($shiftCell === '' || strtoupper($shiftCell) === 'L' || strtoupper($shiftCell) === 'LIBUR') {
                                $resolvedShifts[] = $getOrCreateShift($user->unit_id, 'L', null, null, 'Libur');
                            } elseif (strtoupper($shiftCell) === 'C' || strtoupper($shiftCell) === 'CUTI') {
                                $resolvedShifts[] = $getOrCreateShift($user->unit_id, 'C', null, null, 'Cuti');
                            } else {
                                if (preg_match('/^(.+?)\s*\((\d{2}:\d{2})(?::\d{2})?\s*-\s*(\d{2}:\d{2})(?::\d{2})?\)$/', $partTrimmed, $matches)) {
                                    $namaShift = trim($matches[1]);
                                    $jamMasuk = substr($matches[2], 0, 5);
                                    $jamKeluar = substr($matches[3], 0, 5);

                                    $resolvedShifts[] = $getOrCreateShift($user->unit_id, $namaShift, $jamMasuk, $jamKeluar);
                                } else {
                                    $resolvedShifts[] = $getOrCreateShift($user->unit_id, strtoupper($shiftCell));
                                }
                            }
                        }

                        $resolvedShifts = array_values(array_filter($resolvedShifts));

                        // PROTEKSI CUTI RESMI (Anti-Overwrite):
                        $hasApprovedCuti = $userCutis->contains(function ($c) use ($tanggal_jadwal) {
                            return $c->tanggal_mulai <= $tanggal_jadwal && $c->tanggal_selesai >= $tanggal_jadwal;
                        });

                        if ($hasApprovedCuti && !collect($resolvedShifts)->contains('nama_shift', 'C')) {
                            $resolvedShifts = [$getOrCreateShift($user->unit_id, 'C', null, null, 'Cuti')];
                        }

                        if (!empty($resolvedShifts)) {
                            $key = $user->id . '_' . $tanggal_jadwal;
                            $existingRows = $existingJadwals->get($key, collect())->values();

                            foreach ($resolvedShifts as $idx => $shift) {
                                if (isset($existingRows[$idx])) {
                                    $ex = $existingRows[$idx];
                                    if ($ex->shift_id !== $shift->id) {
                                        $ex->update(['shift_id' => $shift->id]);
                                    }
                                } else {
                                    $newJadwal = JadwalAbsensi::create([
                                        'user_id'        => $user->id,
                                        'tanggal_jadwal' => $tanggal_jadwal,
                                        'shift_id'       => $shift->id,
                                    ]);
                                    $existingRows->push($newJadwal);
                                }
                            }

                            // Jika sebelumnya ada shift berlebih (misal dari double shift diubah jadi 1 shift)
                            if ($existingRows->count() > count($resolvedShifts)) {
                                for ($i = count($resolvedShifts); $i < $existingRows->count(); $i++) {
                                    $existingRows[$i]->delete();
                                }
                                $existingRows = $existingRows->slice(0, count($resolvedShifts));
                            }

                            $existingJadwals->put($key, $existingRows);
                        }
                    }

                    // Simpan Penanggung Jawab (PJ) di luar loop tanggal untuk menghemat query
                    if ($isPJ) {
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $tglPj = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day);
                            PJ::updateOrCreate([
                                'user_id' => $user->id,
                                'assigned_at' => $tglPj,
                            ], [
                                'is_pj' => true,
                            ]);
                        }
                    } else {
                        // Cukup 1 query delete untuk seluruh bulan ini jika bukan PJ (menghemat 1.200+ query)
                        PJ::where('user_id', $user->id)
                            ->whereBetween('assigned_at', [$startDate, $endDate])
                            ->delete();
                    }
                }
            } catch (\Exception $e) {
                Log::error("Import Gagal: " . $e->getMessage());
            }
        }
    }
}
