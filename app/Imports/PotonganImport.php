<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Potongan;
use App\Models\GajiBruto;
use App\Models\TaxBracket;
use Illuminate\Support\Str;
use App\Models\MasterPotongan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class PotonganImport implements ToCollection
{
    public function __construct(protected int $bulan, protected int $tahun) {}

    public function collection(Collection $rows)
    {
        // 1. Mengambil baris pertama sebagai header
        $headerAtas = $rows[3] ?? [];
        $headerBawah = $rows[4] ?? [];

        $header = collect($headerBawah)->map(function ($value, $index) use ($headerAtas) {
            // Jika baris bawah kosong (karena rowspan), ambil dari baris atasnya
            return (is_null($value) || $value === '') ? ($headerAtas[$index] ?? null) : $value;
        })->toArray();


        // 2. Persiapan Master Data
        $masterPotongans = MasterPotongan::aktifTerurut()->get();
        $mapPotongans = $masterPotongans->keyBy('slug');

        // 3. Mapping Header Potongan (Dimulai dari Indeks 19: Pinjaman Koperasi) -> ganti ke 18 krn 'slug' dihapus
        $headerSlugs = collect($header)
            ->slice(offset: 18)
            ->map(fn($h) => $h ? Str::slug(trim($h)) : null)
            ->values();

        // dd($header);
        logger()->info("SLUGS dari HEADER:", $headerSlugs->toArray());

        $users = User::with(['jenis', 'kategorijabatan', 'kategorifungsional'])->get();
        $usersBySlug = $users->keyBy(function ($user) {
            return Str::slug((string) $user->slug);
        });

        $usersByExportedName = $users->groupBy(function ($user) {
            $namaExport = filled($user->nama_bersih) ? $user->nama_bersih : $user->name;
            return Str::slug(trim((string) $namaExport));
        });

        $usersByName = $users->groupBy(function ($user) {
            return Str::slug(trim((string) $user->name));
        });

        foreach ($rows->slice(5) as $row) {
            // $namaMentah = trim($row[1] ?? '');
            // $namaBersih = new User(['name' => $namaMentah]);
            // $slug = Str::slug($namaBersih->nama_bersih);
            // $user = User::where('slug', $slug)->with(['jenis', 'kategorijabatan', 'kategorifungsional'])->first();
            // if (!$user) {
                //     // Log::warning("PotonganImport: User dengan slug '{$slug}' tidak ditemukan.");
                //     continue;
                // }
                
            // 4. Identifikasi User (Slug ada di indeks 1) -> 'slug' dihapus, row[1] = nama lengkap di excel
            $namaExcel = trim((string) ($row[1] ?? ''));

            $user = $this->resolveUserFromExcelName(
                $namaExcel,
                $usersBySlug,
                $usersByExportedName,
                $usersByName
            );

            if (!$user) {
                logger()->warning('User tidak ditemukan saat import potongan', [
                    'nama_excel' => $namaExcel,
                    'slug_excel' => Str::slug($namaExcel),
                ]);
                continue;
            }

            // dd($row[4]);
            // 5. Ekstraksi Komponen Gaji (Indeks bergeser +1 karena kolom 'No')
            $gapok            = (int) ($row[3]  ?? 0);
            $nom_fungsi       = (int) ($row[4]  ?? 0);
            $nom_jabatan      = (int) ($row[5]  ?? 0);
            $nom_umum         = (int) ($row[6]  ?? 0);
            $nom_makan         = (int) ($row[7]  ?? 0);
            $nom_transport     = (int) ($row[8]  ?? 0);
            $nom_poskes        = (int) ($row[9] ?? 0);
            $nom_lainnya       = (int) ($row[10] ?? 0);
            $nom_lembur        = (int) ($row[11] ?? 0);
            $level_jabatan     = (int) ($row[12] ?? 0);
            $nom_pendapatan_rs = (int) ($row[13] ?? 0);

            $prosentase_tukin   = (float) ($row[14] ?? 0) * 100;
            $KPI                = (float) ($row[15] ?? 0) * 100;
            // $nom_tukin_diterima = (int) ($row[16] ?? 0);
            $nom_tukin_diterima = (int) round($nom_pendapatan_rs * ($prosentase_tukin / 100) * ($KPI / 100));
            // index 18 = TOTAL BRUTO dari file Excel, tidak perlu dipakai karena dihitung ulang
            // dd($nom_tukin_diterima);

            // $nom_makan     = (int) $this->cleanRupiah($row[9] ?? 0);
            // $nom_transport = (int) $this->cleanRupiah($row[10] ?? 0);
            // $nom_khusus    = (int) $this->cleanRupiah($row[11] ?? 0);

            // Hitung ulang bruto untuk memastikan validitas
            // $total_bruto = $gapok + $nom_jabatan + $nom_fungsi + $nom_umum + $nom_khusus + $nom_makan + $nom_transport + $nom_lainnya;

            // Perhitungan Bruto Baru dengan Menambahkan Lembur
            // $total_bruto = $gapok + $nom_jabatan + $nom_fungsi + $nom_umum + $nom_lembur + $nom_lainnya + $nom_poskes;
            // --- RUMUS SUM ---
            $total_bruto = $gapok + $nom_fungsi + $nom_jabatan + $nom_umum + $nom_poskes + $nom_lainnya + $nom_lembur + $nom_makan + $nom_transport;

            // Jika Anda ingin TOTAL AKHIR (termasuk Tukin Diterima)
            $total_akhir_bruto = $total_bruto + $nom_tukin_diterima;

            // 6. Simpan/Update Gaji Bruto
            $bruto = GajiBruto::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ],
                [
                    'nom_gapok'         => $gapok,
                    'nom_jabatan'       => $nom_jabatan,
                    'nom_fungsi'        => $nom_fungsi,
                    'nom_umum'          => $nom_umum,
                    'nom_makan'         => $nom_makan,
                    'nom_transport'     => $nom_transport,
                    'nom_lainnya'           => $nom_lainnya,
                    'nom_poskes'        => $nom_poskes,
                    'nom_lembur'        => $nom_lembur,
                    'level_jabatan'     => $level_jabatan,
                    'nom_pendapatan_rs' => $nom_pendapatan_rs,
                    'prosentase_tukin'  => $prosentase_tukin,
                    'KPI'               => $KPI,
                    'nom_tukin_diterima' => $nom_tukin_diterima,
                    'total_bruto'       => $total_akhir_bruto,
                    'created_at'        => now(),
                ]
            );

            // Gunakan total bruto hasil hitung sistem
            $brutoNominal = $bruto->total_bruto;

            // 7. Proses Potongan yang ada di Kolom Excel (Indeks 19 ke atas) -> ganti ke 18
            foreach ($headerSlugs as $i => $slugKey) {
                $colIndex = $i + 18; // <- geser index ke 18
                $val = $row[$colIndex] ?? null;
                $originalHeader = $header[$colIndex] ?? 'UNKNOWN';

                $master = $mapPotongans[$slugKey] ?? null;
                if (!$master) {
                    // Log::warning("PotonganImport: Master untuk '{$originalHeader}' tidak ditemukan.");
                    continue;
                }

                $cleanVal = (int) $this->cleanRupiah($val);
                if ($cleanVal <= 0) continue;

                Potongan::updateOrCreate([
                    'bruto_id' => $bruto->id,
                    'master_potongan_id' => $master->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ], [
                    'nominal' => $cleanVal
                ]);
            }

            // 8. Hitung Potongan Otomatis (Jika tidak ada di Excel)
            $tunjangan = $nom_jabatan + $nom_fungsi + $nom_umum;
            $makanTransport = $nom_makan + $nom_transport;

            foreach ($masterPotongans as $master) {
                $key = $master->slug;

                // Cek apakah potongan ini sudah masuk dari Excel tadi
                $existing = Potongan::where([
                    'bruto_id' => $bruto->id,
                    'master_potongan_id' => $master->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ])->exists();

                if ($existing) continue;

                $nom = 0;

                // Logika PPh21
                if (Str::contains($key, 'pph')) {
                    $kategoriInduk = $user->kategoriPphInduk();
                    $tax = $kategoriInduk
                        ? TaxBracket::where('kategoripph_id', $kategoriInduk->id)
                        ->where('upper_limit', '>=', $brutoNominal)
                        ->orderBy('upper_limit')->first()
                        : null;
                    $nom = round($brutoNominal * ($tax?->persentase ?? 0));
                }
               // Logika BPJS Tenaga Kerja (3%)
                elseif (Str::contains($key, 'tenaga-kerja')) {
                    $nom = $this->betterRound(0.03 * ($gapok + $tunjangan));
                }
                // Logika BPJS Kesehatan Ortu (1%)
                elseif (Str::contains($key, 'bpjs-kesehatan-ortu')) {
                    $nom = $user->bpjs_ortu ? $this->betterRound(0.01 * ($gapok + $tunjangan + $makanTransport)) : 0;
                    // $nom = $user->bpjs_ortu ? round(0.01 * ($gapok + $tunjangan + $makanTransport)) : 0;
                    // $nom = $user->bpjs_ortu ? round(0.01 * ($gapok + $tunjangan)) : 0;
                }
                // Logika BPJS Kesehatan Standar (1%)
                elseif (Str::contains($key, 'bpjs-kesehatan') && !Str::contains($key, ['ortu', 'rekonsiliasi'])) {
                    $nom = $this->betterRound(0.01 * ($gapok + $tunjangan + $makanTransport));
                    // $nom = round(0.01 * ($gapok + $tunjangan + $makanTransport));
                    // $nom = round(0.01 * ($gapok + $tunjangan));
                }

                // Logika Organisasi Profesi (IDI / PPNI)
                $jabatanKategori = strtolower($user->kategorijabatan?->nama ?? '');
                $jabatanFungsional = strtolower($user->kategorifungsional?->nama ?? '');
                $isDokter = Str::contains($jabatanKategori, 'dokter') || Str::contains($jabatanFungsional, 'dokter');
                $isBidan  = Str::contains($jabatanKategori, 'bidan')  || Str::contains($jabatanFungsional, 'bidan');

                if ($key === 'idi' && $isDokter && $master->nominal > 0) {
                    $nom = $master->nominal;
                } elseif ($key === 'ppni' && $isBidan && $master->nominal > 0) {
                    $nom = $master->nominal;
                }

                // Simpan jika ada nominal yang dihasilkan
                if ($nom > 0) {
                    Potongan::create([
                        'bruto_id' => $bruto->id,
                        'master_potongan_id' => $master->id,
                        'bulan_penggajian' => $this->bulan,
                        'tahun_penggajian' => $this->tahun,
                        'nominal' => $nom,
                    ]);
                }
            }
        }
    }

    protected function resolveUserFromExcelName(
        string $namaExcel,
        Collection $usersBySlug,
        Collection $usersByExportedName,
        Collection $usersByName
    ): ?User {
        $namaExcel = trim($namaExcel);

        if ($namaExcel === '') {
            return null;
        }

        $key = Str::slug($namaExcel);

        // 1. Cocok langsung ke slug user
        if ($usersBySlug->has($key)) {
            return $usersBySlug->get($key);
        }

        // 2. Cocok ke nama yang memang diexport:
        //    nama_bersih jika ada, kalau kosong pakai name
        $candidates = $usersByExportedName->get($key, collect());

        if ($candidates->count() === 1) {
            return $candidates->first();
        }

        // 3. Fallback ke name asli
        $candidatesByName = $usersByName->get($key, collect());

        if ($candidatesByName->count() === 1) {
            return $candidatesByName->first();
        }

        // 4. Kalau kandidat lebih dari satu, coba exact match
        $exact = $candidates->first(function ($user) use ($namaExcel) {
            $namaExport = filled($user->nama_bersih) ? $user->nama_bersih : $user->name;

            return trim((string) $namaExport) === $namaExcel
                || trim((string) $user->name) === $namaExcel;
        });

        if ($exact) {
            return $exact;
        }

        return null;
    }
    public function betterRound($value): int
    {
        $decimal_part = $value - floor($value);
        if ($decimal_part >= 0.5) {
            $result = ceil($value);
        } else {
            $result = floor($value);
        }
        return $result;
    }
    protected function cleanRupiah($value): string
    {
        if (is_numeric($value)) return (string) $value;
        return preg_replace('/[^\d]/', '', $value ?? '');
    }
}
