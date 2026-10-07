<?php

namespace App\Livewire;

use App\Models\KpiItem;
use App\Models\KpiPenilaian;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormulirKpi extends Component
{
    public $userId;
    public $kpiId;
    public $isEdit = false;

    // 1. Yang Dinilai
    public $nama;
    public $posisi;
    public $unitKerja;
    public $gaji;
    public $bonus;

    // 2. Pejabat Penilai
    public $penilaiId;
    public $penilaiNama;
    public $penilaiNik;
    public $penilaiJabatan;
    public $penilaiUnitKerja;

    // 3. Atasan Pejabat Penilai
    public $atasanPenilaiId;
    public $atasanPenilaiNama;
    public $atasanPenilaiNik;
    public $atasanPenilaiJabatan;
    public $atasanPenilaiUnitKerja;

    // Periode & Tanggal
    public $periodeBulan;
    public $periodeTahun;
    public $tanggalPenilaian;

    // Tabel Parameter KPI
    public $items = [];

    // Kesimpulan & Kalkulasi
    public $totalBobot = 0;
    public $totalSkor = 0;
    public $anggaranTunjangan = 0;
    public $persentaseTunjangan = 0;
    public $tunjanganDiterima = 0;
    public $status = 'draft';
    public $catatan;

    public function mount($userId, $kpiId = null)
    {
        $this->userId = $userId;
        $this->kpiId = $kpiId;

        $targetUser = User::with(['kategorijabatan', 'jabatan.kategorijabatan', 'unitKerja', 'historyGapok', 'roles'])->findOrFail($userId);

        if ($kpiId) {
            // Mode Edit / View
            $kpi = KpiPenilaian::with('items')->findOrFail($kpiId);
            $this->isEdit = true;
            $this->nama = $targetUser->name;
            $this->posisi = $kpi->posisi;
            $this->unitKerja = $kpi->unit_kerja;
            $this->gaji = $kpi->gaji;
            $this->bonus = $kpi->bonus;

            $this->penilaiId = $kpi->penilai_id;
            $this->penilaiNama = $kpi->penilai_nama;
            $this->penilaiNik = $kpi->penilai_nik;
            $this->penilaiJabatan = $kpi->penilai_jabatan;
            $this->penilaiUnitKerja = $kpi->penilai_unit_kerja;

            $this->atasanPenilaiId = $kpi->atasan_penilai_id;
            $this->atasanPenilaiNama = $kpi->atasan_penilai_nama;
            $this->atasanPenilaiNik = $kpi->atasan_penilai_nik;
            $this->atasanPenilaiJabatan = $kpi->atasan_penilai_jabatan;
            $this->atasanPenilaiUnitKerja = $kpi->atasan_penilai_unit_kerja;

            $this->periodeBulan = $kpi->periode_bulan;
            $this->periodeTahun = $kpi->periode_tahun;
            $this->tanggalPenilaian = $kpi->tanggal_penilaian ? $kpi->tanggal_penilaian->format('Y-m-d') : date('Y-m-d');

            $this->anggaranTunjangan = $kpi->anggaran_tunjangan;
            $this->status = $kpi->status;
            $this->catatan = $kpi->catatan;

            $this->items = $kpi->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'perspektif' => $item->perspektif,
                    'sasaran_kinerja' => $item->sasaran_kinerja,
                    'parameter_kpi' => $item->parameter_kpi,
                    'bobot' => (float) $item->bobot,
                    'target' => (float) $item->target,
                    'realisasi' => (float) $item->realisasi,
                    'skor' => (float) $item->skor,
                    'keterangan' => $item->keterangan,
                ];
            })->toArray();
        } else {
            // Mode Baru (Create)
            $this->periodeBulan = (int) date('m');
            $this->periodeTahun = (int) date('Y');
            $this->tanggalPenilaian = date('Y-m-d');

            // 1. Autofill Yang Dinilai
            $this->nama = $targetUser->name;
            $this->posisi = $targetUser->kategorijabatan?->nama ?? $targetUser->jabatan?->kategorijabatan?->nama ?? $targetUser->roles->first()?->name ?? 'Karyawan';
            $this->unitKerja = $targetUser->unitKerja?->nama ?? '-';
            $this->gaji = $targetUser->historyGapok()->latest()->first()?->nominal ?? 0;
            $this->bonus = 0;
            $this->anggaranTunjangan = $targetUser->kategorijabatan?->nominal ?? 0;

            // 2. Autofill Pejabat Penilai (Auth User)
            $authUser = Auth::user();
            if ($authUser) {
                $this->penilaiId = $authUser->id;
                $this->penilaiNama = $authUser->name;
                $this->penilaiNik = $authUser->nik ?? '-';
                $this->penilaiJabatan = $authUser->kategorijabatan?->nama ?? $authUser->jabatan?->kategorijabatan?->nama ?? $authUser->roles->first()?->name ?? '-';
                $this->penilaiUnitKerja = $authUser->unitKerja?->nama ?? '-';
            }

            // 3. Autofill Atasan Pejabat Penilai
            $atasan = $this->detectAtasanPenilai($authUser);
            if ($atasan) {
                $this->atasanPenilaiId = $atasan->id;
                $this->atasanPenilaiNama = $atasan->name;
                $this->atasanPenilaiNik = $atasan->nik ?? '-';
                $this->atasanPenilaiJabatan = $atasan->kategorijabatan?->nama ?? $atasan->jabatan?->kategorijabatan?->nama ?? $atasan->roles->first()?->name ?? '-';
                $this->atasanPenilaiUnitKerja = $atasan->unitKerja?->nama ?? '-';
            }

            // Inisialisasi default parameter KPI (sesuai template Excel pembimbing)
            $this->items = $this->getDefaultItems();
        }

        $this->calculateTotals();
    }

    public function detectAtasanPenilai($penilai)
    {
        if (!$penilai) return null;

        // Jika penilai Direktur
        if ($penilai->hasRole('Direktur') || $penilai->jabatan_id == 1) {
            return $penilai;
        }

        // Jika penilai Wadir -> atasan Direktur
        if ($penilai->hasRole('Wadir') || $penilai->jabatan_id == 2) {
            return User::where(function($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', 'Direktur'))
                  ->orWhere('jabatan_id', 1);
            })->first();
        }

        // Jika penilai Manajer -> atasan Wadir
        if ($penilai->hasRole('Manager') || $penilai->hasRole('Manajer') || $penilai->jabatan_id == 3) {
            $unitNama = strtolower($penilai->unitKerja?->nama ?? '');
            $isPelayanan = str_contains($unitNama, 'pelayanan') || str_contains($unitNama, 'medik') || str_contains($unitNama, 'keperawatan') || str_contains($unitNama, 'penunjang');

            $wadirQuery = User::where(function($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', 'LIKE', '%Wadir%'))
                  ->orWhere('jabatan_id', 2);
            });

            if ($isPelayanan) {
                $wadir = (clone $wadirQuery)->whereHas('roles', fn($r) => $r->where('name', 'LIKE', '%Pelayanan%'))->first();
            } else {
                $wadir = (clone $wadirQuery)->whereHas('roles', fn($r) => $r->where('name', 'LIKE', '%Umum%'))->first();
            }

            return $wadir ?: $wadirQuery->first();
        }

        // Jika penilai Kepala Seksi / Unit / Instalasi -> atasan Manajer
        $unitId = $penilai->unit_id;
        $unit = $penilai->unitKerja;
        $parentUnitId = $unit?->parent_id;

        $manajer = User::where(function($q) use ($unitId, $parentUnitId) {
                if ($unitId) $q->where('unit_id', $unitId);
                if ($parentUnitId) $q->orWhere('unit_id', $parentUnitId);
            })
            ->where(function($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', 'LIKE', '%Manajer%')->orWhere('name', 'LIKE', '%Manager%'))
                  ->orWhere('jabatan_id', 3);
            })
            ->first();

        if ($manajer && $manajer->id !== $penilai->id) {
            return $manajer;
        }

        // Fallback: atasan langsung di unit penilai
        $atasanUnit = User::where('unit_id', $penilai->unit_id)
            ->where('id', '!=', $penilai->id)
            ->where(function($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', 'LIKE', '%Kepala%')->orWhere('name', 'LIKE', '%Ka.%'))
                  ->orWhere('jabatan_id', '<', 5);
            })
            ->first();

        if ($atasanUnit) {
            return $atasanUnit;
        }

        // Fallback pimpinan umum (Wadir atau Direktur)
        return User::where('jabatan_id', 2)->first() ?: User::where('jabatan_id', 1)->first();
    }

    public function updatedAtasanPenilaiId($val)
    {
        if (!$val) {
            $this->atasanPenilaiNama = '';
            $this->atasanPenilaiNik = '';
            $this->atasanPenilaiJabatan = '';
            $this->atasanPenilaiUnitKerja = '';
            return;
        }

        $atasan = User::with(['unitKerja', 'kategorijabatan', 'jabatan.kategorijabatan', 'roles'])->find($val);
        if ($atasan) {
            $this->atasanPenilaiNama = $atasan->name;
            $this->atasanPenilaiNik = $atasan->nik ?? '-';
            $this->atasanPenilaiJabatan = $atasan->kategorijabatan?->nama ?? $atasan->jabatan?->kategorijabatan?->nama ?? $atasan->roles->first()?->name ?? '-';
            $this->atasanPenilaiUnitKerja = $atasan->unitKerja?->nama ?? '-';
        }
    }

    public function getDefaultItems()
    {
        return [
            [
                'perspektif' => 'Finansial',
                'sasaran_kinerja' => 'Lembur',
                'parameter_kpi' => 'Prosentase Efisiensi Biaya Lembur',
                'bobot' => 25,
                'target' => 0.3,
                'realisasi' => 0.5,
                'skor' => 15,
                'keterangan' => '< 0,3% dari Biaya SDM',
            ],
            [
                'perspektif' => 'Proses Produksi',
                'sasaran_kinerja' => 'Analisa Kinerja',
                'parameter_kpi' => 'Prosentase Analisa Kinerja PJ dibawah Manajer SDM',
                'bobot' => 20,
                'target' => 100,
                'realisasi' => 90,
                'skor' => 18,
                'keterangan' => '100%',
            ],
            [
                'perspektif' => 'Proses Produksi',
                'sasaran_kinerja' => 'Produk/ Evaluasi Regulasi',
                'parameter_kpi' => 'Jumlah Produk/ Evaluasi regulasi yang dihasilkan dan disahkan dalam 1 bulan',
                'bobot' => 20,
                'target' => 2,
                'realisasi' => 2,
                'skor' => 20,
                'keterangan' => '≤ 2 Regulasi',
            ],
            [
                'perspektif' => 'Proses Produksi',
                'sasaran_kinerja' => 'Supervisi',
                'parameter_kpi' => 'Jumlah Supervisi Kedisiplinan karyawan dan Pelaksanaan Sosialisasi Regulasi yang terlaksana',
                'bobot' => 15,
                'target' => 4,
                'realisasi' => 4,
                'skor' => 15,
                'keterangan' => '≥ 4 kali',
            ],
            [
                'perspektif' => 'Customer',
                'sasaran_kinerja' => 'Komplain/ Insiden',
                'parameter_kpi' => 'Jumlah Komplain terkait kinerja SDM dalam satu bulan',
                'bobot' => 10,
                'target' => 2,
                'realisasi' => 2,
                'skor' => 10,
                'keterangan' => '≤ 2 Kali',
            ],
            [
                'perspektif' => 'Growth and Learning',
                'sasaran_kinerja' => 'Ilmiah Series',
                'parameter_kpi' => 'Jumlah Ilmiah Series yang diikuti dalam waktu 1 bulan',
                'bobot' => 5,
                'target' => 2,
                'realisasi' => 2,
                'skor' => 5,
                'keterangan' => '≥ 2 kali',
            ],
            [
                'perspektif' => 'Growth and Learning',
                'sasaran_kinerja' => 'Hafalan',
                'parameter_kpi' => 'Tambahan Hafalan dalam 1 bulan',
                'bobot' => 5,
                'target' => 10,
                'realisasi' => 6,
                'skor' => 3,
                'keterangan' => '≥ 10 Ayat',
            ],
        ];
    }

    public function updatedItems()
    {
        $this->calculateTotals();
    }

    public function updatedAnggaranTunjangan()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $bobotSum = 0;
        $skorSum = 0;

        foreach ($this->items as $item) {
            $bobotSum += (float) ($item['bobot'] ?? 0);
            $skorSum += (float) ($item['skor'] ?? 0);
        }

        $this->totalBobot = round($bobotSum, 2);
        $this->totalSkor = round($skorSum, 2);

        // Kriteria Nilai Skor & Prosentase Tunjangan Jabatan
        if ($this->totalSkor <= 30) {
            $this->persentaseTunjangan = 30;
        } elseif ($this->totalSkor <= 120) {
            $this->persentaseTunjangan = $this->totalSkor;
        } else {
            $this->persentaseTunjangan = 120;
        }

        $anggaran = (float) str_replace(['.', ','], '', (string) $this->anggaranTunjangan);
        $this->tunjanganDiterima = round($anggaran * ($this->persentaseTunjangan / 100));
    }

    public function addItem()
    {
        $this->items[] = [
            'perspektif' => '',
            'sasaran_kinerja' => '',
            'parameter_kpi' => '',
            'bobot' => 0,
            'target' => 0,
            'realisasi' => 0,
            'skor' => 0,
            'keterangan' => '',
        ];
        $this->calculateTotals();
    }

    public function removeItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateTotals();
        }
    }

    public function save($status = 'draft')
    {
        $this->validate([
            'nama' => 'required',
            'periodeBulan' => 'required|numeric|min:1|max:12',
            'periodeTahun' => 'required|numeric|min:2020|max:2099',
            'items' => 'required|array|min:1',
        ]);

        $this->calculateTotals();
        $this->status = $status;

        $kpiData = [
            'user_id' => $this->userId,
            'posisi' => $this->posisi,
            'unit_kerja' => $this->unitKerja,
            'gaji' => (float) str_replace(['.', ','], '', (string) $this->gaji),
            'bonus' => (float) str_replace(['.', ','], '', (string) $this->bonus),

            'penilai_id' => $this->penilaiId,
            'penilai_nama' => $this->penilaiNama,
            'penilai_nik' => $this->penilaiNik,
            'penilai_jabatan' => $this->penilaiJabatan,
            'penilai_unit_kerja' => $this->penilaiUnitKerja,

            'atasan_penilai_id' => $this->atasanPenilaiId,
            'atasan_penilai_nama' => $this->atasanPenilaiNama,
            'atasan_penilai_nik' => $this->atasanPenilaiNik,
            'atasan_penilai_jabatan' => $this->atasanPenilaiJabatan,
            'atasan_penilai_unit_kerja' => $this->atasanPenilaiUnitKerja,

            'periode_bulan' => $this->periodeBulan,
            'periode_tahun' => $this->periodeTahun,
            'tanggal_penilaian' => $this->tanggalPenilaian,

            'total_bobot' => $this->totalBobot,
            'total_skor' => $this->totalSkor,
            'anggaran_tunjangan' => (float) str_replace(['.', ','], '', (string) $this->anggaranTunjangan),
            'persentase_tunjangan' => $this->persentaseTunjangan,
            'tunjangan_diterima' => $this->tunjanganDiterima,

            'status' => $this->status,
            'catatan' => $this->catatan,
        ];

        if ($this->kpiId) {
            $kpi = KpiPenilaian::findOrFail($this->kpiId);
            $kpi->update($kpiData);
            $kpi->items()->delete();
        } else {
            $kpi = KpiPenilaian::create($kpiData);
            $this->kpiId = $kpi->id;
            $this->isEdit = true;
        }

        foreach ($this->items as $item) {
            KpiItem::create([
                'kpi_id' => $kpi->id,
                'perspektif' => $item['perspektif'] ?? '-',
                'sasaran_kinerja' => $item['sasaran_kinerja'] ?? '-',
                'parameter_kpi' => $item['parameter_kpi'] ?? '-',
                'bobot' => (float) ($item['bobot'] ?? 0),
                'target' => (float) ($item['target'] ?? 0),
                'realisasi' => (float) ($item['realisasi'] ?? 0),
                'skor' => (float) ($item['skor'] ?? 0),
                'keterangan' => $item['keterangan'] ?? '',
            ]);
        }

        session()->flash('success', 'Formulir KPI berhasil disimpan.');
        return redirect()->route('detailkaryawan.show', $this->userId);
    }

    public function render()
    {
        $listAtasan = User::with(['kategorijabatan', 'roles', 'unitKerja'])
            ->where('id', '!=', $this->userId)
            ->where(function($q) {
                $q->whereIn('jabatan_id', [1, 2, 3, 4, 5])
                  ->orWhereHas('roles', fn($r) => $r->whereIn('name', ['Super Admin', 'Direktur', 'Wadir', 'Manager', 'Manajer', 'Kepala Seksi', 'Kepala Instalasi', 'Kepala Unit']));
            })
            ->orderBy('name')
            ->get();

        return view('livewire.formulir-kpi', [
            'listAtasan' => $listAtasan,
            'bulanList' => [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ]
        ]);
    }
}
