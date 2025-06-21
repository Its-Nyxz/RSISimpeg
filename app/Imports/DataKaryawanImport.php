<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\JenisKaryawan;
use App\Models\RiwayatJabatan;
use Illuminate\Support\Carbon;
use App\Models\KategoriJabatan;
use App\Models\MasterJatahCuti;
use App\Models\SisaCutiTahunan;
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
                // Jenis Kelamin: 'L' → true, 'P' → false, lainnya/null → null
                $jkInput = strtolower(trim($row[6] ?? ''));
                $jk = $jkInput === 'l' ? true : ($jkInput === 'p' ? false : null);
                $pendidikanId = trim(explode(' - ', $row[10])[0] ?? null);
                $pendidikanId = $pendidikanId !== '' ? $pendidikanId : null;
                if ($pendidikanId && !MasterPendidikan::find($pendidikanId)) {
                    $pendidikanId = null;
                }
                $unitKerjaId  = explode(' - ', $row[13])[0] ?? null;
                $jabatanStrukturalId = explode(' - ', $row[14])[0] ?? null; // Jabatan Struktural
                $jabatanFungsionalId = explode(' - ', $row[15])[0] ?? null; // Jabatan Fungsional
                $jabatanUmumId = explode(' - ', $row[16])[0] ?? null; // Jabatan Umum
                $jenisKarId   = explode(' - ', $row[17])[0] ?? null;
                // Type Shift: 'shift' → true, 'nonshift' → false, lainnya/null → null
                $shiftInput = strtolower(trim($row[18] ?? ''));
                $typeShift = $shiftInput === 'shift' ? true : ($shiftInput === 'nonshift' ? false : null);
                $tunjanganId  = explode(' - ', $row[21])[0] ?? null;
                $pphId        = explode(' - ', $row[22])[0] ?? null;
                $tmtDate = null;
                if (is_numeric($row[19])) {
                    $tmtDate = Date::excelToDateTimeObject($row[19]);
                } elseif (!empty($row[19])) {
                    try {
                        $tmtDate = Carbon::parse($row[19]);
                    } catch (\Exception $e) {
                        Log::warning('Format TMT tidak bisa diparse: ' . $row[19]);
                    }
                }
                $tmtMasuk    = null;
                if (is_numeric($row[20])) {
                    $tmtMasuk = Date::excelToDateTimeObject($row[20]);
                } elseif (!empty($row[20])) {
                    try {
                        $tmtMasuk = Carbon::parse($row[20]);
                    } catch (\Exception $e) {
                        Log::warning('Format TMT Masuk tidak bisa diparse: ' . $row[20]);
                    }
                }
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
                // Log::info('Cek user berdasarkan NIP/slug', ['nip' => $nip, 'slug' => $slug]);

                if ($nip) {
                    // Coba cari berdasarkan NIP jika ada
                    $user = User::where('nip', $nip)->first();
                }

                if (!$user) {
                    // Jika tidak ada atau NIP kosong, coba cari berdasarkan slug
                    $user = User::where('slug', $slug)->first();
                }

                if ($user) {
                    // Log::info('User ditemukan untuk update', ['user_id' => $user->id]);
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
                        'tanggal_lahir' => is_numeric($row[8] ?? null)
                            ? Date::excelToDateTimeObject($row[8])->format('Y-m-d')
                            : null,
                        'alamat' => $row[9],
                        'kategori_pendidikan' => $pendidikanId,
                        'pendidikan' => $row[11],
                        'institusi' => $row[12],
                        'unit_id' => $unitKerjaId ?: null,
                        'jabatan_id' => $jabatanStrukturalId ?: null,
                        'fungsi_id' => $jabatanFungsionalId ?: null,
                        'umum_id' => $jabatanUmumId ?: null,
                        'jenis_id' => $jenisKarId ?: null,
                        'type_shift' => $typeShift,
                        'tmt' => $tmtDate?->format('Y-m-d'),
                        'tmt_masuk' => $tmtMasuk?->format('Y-m-d'),
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

                    if ($jabatanStrukturalId && $jabatanStrukturalId != $user->jabatan_id) {
                        $kategori = KategoriJabatan::find($jabatanStrukturalId);
                        if ($kategori && $kategori->tunjangan) {
                            // Tutup riwayat lama
                            RiwayatJabatan::where('user_id', $user->id)
                                ->where('kategori_jabatan_id', $user->jabatan_id)
                                ->where('tunjangan', $kategori->tunjangan)
                                ->whereNull('tanggal_selesai')
                                ->update(['tanggal_selesai' => now()]);

                            // Tambah riwayat baru
                            RiwayatJabatan::create([
                                'user_id' => $user->id,
                                'kategori_jabatan_id' => $jabatanStrukturalId,
                                'tunjangan' => strtolower($kategori->tunjangan),
                                'tanggal_mulai' => $tmtDate ?? now(),
                                'tanggal_selesai' => null,
                            ]);
                        }
                    }

                    if ($jabatanFungsionalId && $jabatanFungsionalId != $user->fungsi_id) {
                        $kategori = KategoriJabatan::find($jabatanFungsionalId);
                        if ($kategori && $kategori->tunjangan) {
                            // Tutup riwayat lama
                            RiwayatJabatan::where('user_id', $user->id)
                                ->where('kategori_jabatan_id', $user->fungsi_id)
                                ->where('tunjangan', $kategori->tunjangan)
                                ->whereNull('tanggal_selesai')
                                ->update(['tanggal_selesai' => now()]);

                            // Tambah riwayat baru
                            RiwayatJabatan::create([
                                'user_id' => $user->id,
                                'kategori_jabatan_id' => $jabatanFungsionalId,
                                'tunjangan' => strtolower($kategori->tunjangan),
                                'tanggal_mulai' => $tmtDate ?? now(),
                                'tanggal_selesai' => null,
                            ]);
                        }
                    }

                    if ($jabatanUmumId && $jabatanUmumId != $user->umum_id) {
                        $kategori = KategoriJabatan::find($jabatanUmumId);
                        if ($kategori && $kategori->tunjangan) {
                            // Tutup riwayat lama
                            RiwayatJabatan::where('user_id', $user->id)
                                ->where('kategori_jabatan_id', $user->umum_id)
                                ->where('tunjangan', $kategori->tunjangan)
                                ->whereNull('tanggal_selesai')
                                ->update(['tanggal_selesai' => now()]);

                            // Tambah riwayat baru
                            RiwayatJabatan::create([
                                'user_id' => $user->id,
                                'kategori_jabatan_id' => $jabatanUmumId,
                                'tunjangan' => strtolower($kategori->tunjangan),
                                'tanggal_mulai' => $tmtDate ?? now(),
                                'tanggal_selesai' => null,
                            ]);
                        }
                    }
                } else {
                    // Log::info('User tidak ditemukan, akan membuat user baru');
                    // Jika belum ada user sama sekali, buat baru
                    $userBaru = User::create([
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
                        'umum_id' => $jabatanUmumId ?: null,
                        'jenis_id' => $jenisKarId ?: null,
                        'type_shift' => $typeShift,
                        'tmt' => $tmtDate?->format('Y-m-d'),
                        'tmt_masuk' => $tmtMasuk?->format('Y-m-d'),
                        'masa_kerja' => $masaKerja,
                        'tunjangan_khusus_id' => $tunjanganId ?: null,
                        'kategori_id' => $pphId ?: null,
                        'gol_id' => $golonganId,
                        'password' => Hash::make('123'),
                    ]);

                    // === Generate cuti tahunan jika jenis karyawan adalah Tetap (id = 1) ===
                    $jenisNama = strtolower(JenisKaryawan::find($jenisKarId)?->nama ?? '');
                    if ($jenisNama === 'tetap') {
                        $currentYear = now()->year;

                        $sudahAda = SisaCutiTahunan::where('user_id', $userBaru->id)
                            ->where('tahun', $currentYear)
                            ->exists();

                        if (!$sudahAda) {
                            $jatahCuti = MasterJatahCuti::where('tahun', $currentYear)
                                ->value('jumlah_cuti') ?? 12;

                            SisaCutiTahunan::create([
                                'user_id' => $userBaru->id,
                                'tahun' => $currentYear,
                                'sisa_cuti' => $jatahCuti,
                            ]);
                        }
                    }

                    // Buat riwayat jabatan jika ada
                    if ($jabatanStrukturalId) {
                        $kategori = KategoriJabatan::find($jabatanStrukturalId);
                        if ($kategori && $kategori->tunjangan) {
                            RiwayatJabatan::create([
                                'user_id' => $userBaru->id,
                                'kategori_jabatan_id' => $jabatanStrukturalId,
                                'tunjangan' => strtolower($kategori->tunjangan),
                                'tanggal_mulai' => $tmtDate ?? now(),
                                'tanggal_selesai' => null,
                            ]);
                        }
                    }

                    // Buat riwayat fungsional jika ada
                    if ($jabatanFungsionalId) {
                        $kategori = KategoriJabatan::find($jabatanFungsionalId);
                        if ($kategori && $kategori->tunjangan) {
                            RiwayatJabatan::create([
                                'user_id' => $userBaru->id,
                                'kategori_jabatan_id' => $jabatanFungsionalId,
                                'tunjangan' => strtolower($kategori->tunjangan),
                                'tanggal_mulai' => $tmtDate ?? now(),
                                'tanggal_selesai' => null,
                            ]);
                        }
                    }

                    if ($jabatanUmumId) {
                        $kategori = KategoriJabatan::find($jabatanUmumId);
                        if ($kategori && $kategori->tunjangan) {
                            RiwayatJabatan::create([
                                'user_id' => $userBaru->id,
                                'kategori_jabatan_id' => $jabatanUmumId,
                                'tunjangan' => strtolower($kategori->tunjangan),
                                'tanggal_mulai' => $tmtMasuk ?? now(),
                                'tanggal_selesai' => null,
                            ]);
                        }
                    }
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

    // public function chunkSize(): int
    // {
    //     return 100; // proses 100 baris per batch
    // }

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
