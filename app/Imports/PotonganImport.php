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

        // 3. Mapping Header Potongan (Dimulai dari Indeks 19: Pinjaman Koperasi)
        $headerSlugs = collect($header)
            ->slice(offset: 19)
            ->map(fn($h) => $h ? Str::slug(trim($h)) : null)
            ->values();

        // dd($header);
        logger()->info("SLUGS dari HEADER:", $headerSlugs->toArray());

        foreach ($rows->slice(5) as $row) {
            // 4. Identifikasi User (Slug ada di indeks 1)
            $slug = trim($row[1] ?? '');
            $user = User::where('slug', $slug)->with(['jenis', 'kategorijabatan', 'kategorifungsional'])->first();
            if (!$user) {
                // Log::warning("PotonganImport: User dengan slug '{$slug}' tidak ditemukan.");
                continue;
            }

            // dd($row[4]);
            // 5. Ekstraksi Komponen Gaji (Indeks bergeser +1 karena kolom 'No')
            $gapok            = (int) ($row[4]  ?? 0);
            $nom_fungsi       = (int) ($row[5]  ?? 0);
            $nom_jabatan      = (int) ($row[6]  ?? 0);
            $nom_umum         = (int) ($row[7]  ?? 0);
            $nom_makan         = (int) ($row[8]  ?? 0);
            $nom_transport     = (int) ($row[9]  ?? 0);
            $nom_poskes        = (int) ($row[10] ?? 0);
            $nom_lainnya       = (int) ($row[11] ?? 0);
            $nom_lembur        = (int) ($row[12] ?? 0);
            $level_jabatan     = (int) ($row[13] ?? 0);
            $nom_pendapatan_rs = (int) ($row[14] ?? 0);

            $prosentase_tukin   = (float) (($row[15] ?? 0) * 100);
            $KPI                = (float) (($row[16] ?? 0) * 100);
            $nom_tukin_diterima = (int) ($row[17] ?? 0);

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

            // 7. Proses Potongan yang ada di Kolom Excel (Indeks 19 ke atas)
            foreach ($headerSlugs as $i => $slugKey) {
                $colIndex = $i + 19;
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
                    $nom = round(0.03 * ($gapok + $tunjangan));
                }
                // Logika BPJS Kesehatan Ortu (1%)
                elseif (Str::contains($key, 'bpjs-kesehatan-ortu')) {
                    $nom = $user->bpjs_ortu ? round(0.01 * ($gapok + $tunjangan + $makanTransport)) : 0;
                    // $nom = $user->bpjs_ortu ? round(0.01 * ($gapok + $tunjangan)) : 0;
                }
                // Logika BPJS Kesehatan Standar (1%)
                elseif (Str::contains($key, 'bpjs-kesehatan') && !Str::contains($key, ['ortu', 'rekonsiliasi'])) {
                    $nom = round(0.01 * ($gapok + $tunjangan + $makanTransport));
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

    protected function cleanRupiah($value): string
    {
        if (is_numeric($value)) return (string) $value;
        return preg_replace('/[^\d]/', '', $value ?? '');
    }
}
