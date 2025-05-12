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
        $masterPotongans = MasterPotongan::all();
        $mapPotongans = $masterPotongans->keyBy(fn($p) => strtolower(trim($p->nama)));

        foreach ($rows as $row) {
            $slug = trim($row[0] ?? '');
            $user = User::where('slug', $slug)->with(['jenis'])->first();
            if (!$user) continue;

            $brutoValue = $this->cleanRupiah($row[11] ?? 0);
            $bruto = GajiBruto::firstOrCreate([
                'user_id' => $user->id,
                'bulan_penggajian' => $this->bulan,
                'tahun_penggajian' => $this->tahun,
            ], [
                'total_bruto' => $brutoValue
            ]);

            $brutoNominal = $bruto->total_bruto ?? $brutoValue;
            $gapok = (int) $this->cleanRupiah($row[3] ?? 0);
            $tunjangan = array_sum([
                (int) $this->cleanRupiah($row[4] ?? 0),
                (int) $this->cleanRupiah($row[5] ?? 0),
                (int) $this->cleanRupiah($row[6] ?? 0),
            ]);
            $makanTransport = (int) $this->cleanRupiah($row[7] ?? 0) + (int) $this->cleanRupiah($row[8] ?? 0);
            // Import manual potongan dari Excel
            foreach ($row->slice(12) as $i => $val) {
                $headerKey = strtolower(trim($header[$i + 12] ?? ''));
                $master = $mapPotongans[$headerKey] ?? null;
                if (!$master || !is_numeric($this->cleanRupiah($val))) continue;

                Potongan::updateOrCreate([
                    'bruto_id' => $bruto->id,
                    'master_potongan_id' => $master->id,
                    'bulan_penggajian' => $this->bulan,
                    'tahun_penggajian' => $this->tahun,
                ], [
                    'nominal' => (int) $this->cleanRupiah($val)
                ]);
            }

            // Tambahkan potongan otomatis jika belum ada
            foreach ($masterPotongans as $master) {
                $key = strtolower(trim($master->nama));
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
                } elseif (Str::contains($key, 'tenaga kerja')) {
                    $nom = round(0.03 * ($gapok + $tunjangan));
                } elseif (Str::contains($key, 'bpjs kesehatan ortu')) {
                    $nom = $user->bpjs_ortu ? round(0.01 * ($gapok + $tunjangan + $makanTransport)) : 0;
                } elseif (Str::contains($key, 'bpjs kesehatan') && !Str::contains($key, ['ortu', 'rekonsiliasi'])) {
                    $nom = round(0.01 * ($gapok + $tunjangan + $makanTransport));
                }

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

    /**
     * Bersihkan format angka dari format rupiah.
     * Contoh: "Rp 100.000" -> "100000"
     */
    protected function cleanRupiah($value): string
    {
        return preg_replace('/[^\d]/', '', $value ?? '');
    }
}
