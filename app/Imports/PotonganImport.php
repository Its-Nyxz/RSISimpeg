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
        $header = $rows->shift();
        $masterPotongans = MasterPotongan::orderBy('id')->get();
        $mapPotongans = $masterPotongans->keyBy('slug'); // gunakan slug yang sudah pasti valid
        $headerSlugs = collect($header)
            ->slice(13)
            ->map(fn($h) => $h ? Str::slug(trim($h)) : null)
            ->values();
        logger()->info("SLUGS dari HEADER:", $headerSlugs->toArray());
        logger()->info("SLUGS dari MASTER:", $mapPotongans->keys()->toArray());
        foreach ($rows as $row) {
            $slug = trim($row[0] ?? '');
            $user = User::where('slug', $slug)->with(['jenis'])->first();
            if (!$user) continue;

            $brutoValue = $this->cleanRupiah($row[12] ?? 0);

            $gapok = (int) $this->cleanRupiah($row[4] ?? 0);
            $nom_jabatan = (int) $this->cleanRupiah($row[5] ?? 0);
            $nom_fungsi  = (int) $this->cleanRupiah($row[6] ?? 0);
            $nom_umum    = (int) $this->cleanRupiah($row[7] ?? 0);
            $nom_makan   = (int) $this->cleanRupiah($row[8] ?? 0);
            $nom_transport = (int) $this->cleanRupiah($row[9] ?? 0);
            $nom_khusus  = (int) $this->cleanRupiah($row[10] ?? 0);
            $nom_lainnya = (int) $this->cleanRupiah($row[11] ?? 0); // Tunjangan Tukin

            // Total bruto dihitung ulang dari semua komponen
            $total_bruto = $gapok + $nom_jabatan + $nom_fungsi + $nom_umum + $nom_khusus + $nom_makan + $nom_transport + $nom_lainnya;

            $bruto = GajiBruto::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ],
                [
                    'nom_gapok'     => $gapok,
                    'nom_jabatan'   => $nom_jabatan,
                    'nom_fungsi'    => $nom_fungsi,
                    'nom_umum'      => $nom_umum,
                    'nom_khusus'    => $nom_khusus,
                    'nom_makan'     => $nom_makan,
                    'nom_transport' => $nom_transport,
                    'nom_lainnya'   => $nom_lainnya,
                    'total_bruto'   => $total_bruto,
                    'created_at'    => now(),
                ]
            );


            $brutoNominal = $bruto->total_bruto ?? $brutoValue;
            $gapok = (int) $this->cleanRupiah($row[3] ?? 0);
            $tunjangan = array_sum([
                (int) $this->cleanRupiah($row[4] ?? 0),
                (int) $this->cleanRupiah($row[5] ?? 0),
                (int) $this->cleanRupiah($row[6] ?? 0),
            ]);
            $makanTransport = (int) $this->cleanRupiah($row[8] ?? 0) + (int) $this->cleanRupiah($row[9] ?? 0);

            foreach ($headerSlugs as $i => $slugKey) {
                $val = $row[$i + 13] ?? null;
                $originalHeader = $header[$i + 13] ?? 'UNKNOWN';

                $master = $mapPotongans[$slugKey] ?? null;
                if (!$master) {
                    Log::warning("PotonganImport: Tidak ditemukan master untuk kolom '{$originalHeader}' → slug '{$slugKey}'");
                    continue;
                }

                $cleanVal = (int) $this->cleanRupiah($val);
                if ($cleanVal <= 0) continue;

                if (!$bruto || !$master->id) {
                    Log::error("Potongan gagal: bruto_id/master_id null. User: {$user->slug}");
                    continue;
                }

                Potongan::updateOrCreate([
                    'bruto_id' => $bruto->id,
                    'master_potongan_id' => $master->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ], [
                    'nominal' => $cleanVal
                ]);
            }

            foreach ($masterPotongans as $master) {
                $key = $master->slug;
                $existing = Potongan::where([
                    'bruto_id' => $bruto->id,
                    'master_potongan_id' => $master->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ])->exists();

                if ($existing) continue;

                $nom = 0;

                if (Str::contains($key, 'pph')) {
                    $kategoriInduk = $user->kategoriPphInduk();
                    $tax = $kategoriInduk
                        ? TaxBracket::where('kategoripph_id', $kategoriInduk->id)
                        ->where('upper_limit', '>=', $brutoNominal)
                        ->orderBy('upper_limit')->first()
                        : null;
                    $nom = round($brutoNominal * ($tax?->persentase ?? 0));
                } elseif (Str::contains($key, 'tenaga-kerja')) {
                    $nom = round(0.03 * ((int) $gapok + (int) $tunjangan));
                } elseif (Str::contains($key, 'bpjs-kesehatan-ortu')) {
                    $nom = $user->bpjs_ortu ? round(0.01 * ((int) $gapok + (int) $tunjangan + (int) $makanTransport)) : 0;
                } elseif (Str::contains($key, 'bpjs-kesehatan') && !Str::contains($key, ['ortu', 'rekonsiliasi'])) {
                    $nom = round(0.01 * ((int) $gapok + (int) $tunjangan + (int) $makanTransport));
                }

                $jabatanKategori = strtolower($user->kategorijabatan?->nama ?? '');
                $jabatanFungsional = strtolower($user->kategorifungsional?->nama ?? '');

                $isDokter = Str::contains($jabatanKategori, 'dokter') || Str::contains($jabatanFungsional, 'dokter');
                $isBidan  = Str::contains($jabatanKategori, 'bidan')  || Str::contains($jabatanFungsional, 'bidan');

                if ($key === 'idi' && $isDokter && $master->nominal > 0) {
                    $nom = $master->nominal;
                } elseif ($key === 'ppni' && $isBidan && $master->nominal > 0) {
                    $nom = $master->nominal;
                }

                // if ($nom > 0) {
                Potongan::create([
                    'bruto_id' => $bruto->id,
                    'master_potongan_id' => $master->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                    'nominal' => $nom,
                ]);
                // }
            }
        }
    }

    protected function cleanRupiah($value): string
    {
        return preg_replace('/[^\d]/', '', $value ?? '');
    }
}
