<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\JenisKaryawan;
use App\Models\MasterPendidikan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataKaryawanImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati baris heading
        $rows->filter(function ($row, $index) {
            return $index > 1 && !empty($row[0]); // lewati header & baris contoh, dan pastikan kolom nama tidak kosong
        })->each(function ($row) {
            try {
                $jk = strtolower(trim($row[6])) === 'l'; // true if 'L', else false
                $pendidikanId = explode(' - ', $row[10])[0] ?? null;
                $unitKerjaId  = explode(' - ', $row[13])[0] ?? null;
                $jabatanStrukturalId = explode(' - ', $row[14])[0] ?? null; // Jabatan Struktural
                $jabatanFungsionalId = explode(' - ', $row[15])[0] ?? null; // Jabatan Fungsional
                $jenisKarId   = explode(' - ', $row[16])[0] ?? null;
                $typeShift = strtolower(trim($row[17])) === 'shift';
                $tunjanganId  = explode(' - ', $row[19])[0] ?? null;
                $pphId        = explode(' - ', $row[20])[0] ?? null;
                $tmtDate = Date::excelToDateTimeObject($row[18]);
                $today = now();

                $masaKerja = null;

                if ($tmtDate) {
                    $diff = $today->diff($tmtDate);
                    $masaKerja = $jenisKarId == 1 ? $diff->y : ($diff->y * 12) + $diff->m;
                }

                $golonganId = $this->hitungGolonganId($pendidikanId, $jenisKarId, $masaKerja);

                $name = $row[0];
                $slug = Str::slug($name);
                $nip = $row[2];
                $conditions = [];

                $user = null;

                if ($nip) {
                    // Coba cari berdasarkan NIP jika ada
                    $user = User::where('nip', $nip)->first();
                }

                if (!$user) {
                    // Jika tidak ada atau NIP kosong, coba cari berdasarkan slug
                    $user = User::where('slug', $slug)->first();
                }

                if ($user) {
                    // Jika sudah ada, update
                    $user->update([
                        'name' => $name,
                        'slug' => $slug,
                        'email' => $row[1],
                        'no_ktp' => $row[3],
                        'no_hp' => $row[4],
                        'no_rek' => $row[5],
                        'jk' => $jk,
                        'tempat' => $row[7],
                        'tanggal_lahir' => Date::excelToDateTimeObject($row[8] ?? null)?->format('Y-m-d'),
                        'alamat' => $row[9],
                        'kategori_pendidikan' => $pendidikanId,
                        'pendidikan' => $row[11],
                        'institusi' => $row[12],
                        'unit_id' => $unitKerjaId ?: null,
                        'jabatan_id' => $jabatanStrukturalId ?: null,
                        'fungsi_id' => $jabatanFungsionalId ?: null,
                        'jenis_id' => $jenisKarId ?: null,
                        'type_shift' => $typeShift,
                        'tmt' => $tmtDate?->format('Y-m-d'),
                        'masa_kerja' => $masaKerja,
                        'tunjangan_khusus_id' => $tunjanganId ?: null,
                        'kategori_id' => $pphId ?: null,
                        'gol_id' => $golonganId,
                    ]);

                    // Tambahkan nip jika sebelumnya kosong
                    if (!$user->nip && $nip) {
                        $user->nip = $nip;
                        $user->save();
                    }
                } else {
                    // Jika belum ada user sama sekali, buat baru
                    User::create([
                        'name' => $name,
                        'slug' => $slug,
                        'nip' => $nip,
                        'email' => $row[1],
                        'no_ktp' => $row[3],
                        'no_hp' => $row[4],
                        'no_rek' => $row[5],
                        'jk' => $jk,
                        'tempat' => $row[7],
                        'tanggal_lahir' => Date::excelToDateTimeObject($row[8] ?? null)?->format('Y-m-d'),
                        'alamat' => $row[9],
                        'kategori_pendidikan' => $pendidikanId,
                        'pendidikan' => $row[11],
                        'institusi' => $row[12],
                        'unit_id' => $unitKerjaId ?: null,
                        'jabatan_id' => $jabatanStrukturalId ?: null,
                        'fungsi_id' => $jabatanFungsionalId ?: null,
                        'jenis_id' => $jenisKarId ?: null,
                        'type_shift' => $typeShift,
                        'tmt' => $tmtDate?->format('Y-m-d'),
                        'masa_kerja' => $masaKerja,
                        'tunjangan_khusus_id' => $tunjanganId ?: null,
                        'kategori_id' => $pphId ?: null,
                        'gol_id' => $golonganId,
                        'password' => Hash::make('123'),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Gagal import row: ', [
                    'row' => $row->toArray(), // tampilkan isi array saja
                    'error_message' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(), // ini sangat membantu
                ]);
            }
        });
    }

    public function chunkSize(): int
    {
        return 100; // proses 100 baris per batch
    }

    private function hitungGolonganId($pendidikanId, $jenisKaryawanId, $masaKerja)
    {
        $pendidikan = MasterPendidikan::find($pendidikanId);
        $jenis = JenisKaryawan::find($jenisKaryawanId)?->nama;

        if (!$pendidikan || !$pendidikan->minim_gol || !$pendidikan->maxim_gol) {
            return null;
        }

        $baseGol = $pendidikan->minim_gol;
        $maxGol = $pendidikan->maxim_gol;

        // Golongan tetap dihitung pakai tahun walau kontrak
        $masaKerjaTahun = strtolower($jenis) === 'kontrak' ? floor($masaKerja / 12) : $masaKerja;

        if ($masaKerjaTahun !== null) {
            $increment = min(floor($masaKerjaTahun / 4), 4);
            $calculated = $baseGol + $increment;
            return $calculated <= $maxGol ? $calculated : $maxGol;
        }

        return $baseGol;
    }
}
