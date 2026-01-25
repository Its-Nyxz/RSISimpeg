<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\GapokKontrak;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class KenaikanKontrak extends Component
{

    use WithPagination;

    public $bulan;
    public $tahun;
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $roles;
    public $units;
    public $selectedUserAktif = 1; // Default aktif
    public $selectedUnit = null;
    public $isKepegawaian = false;

    public $jenisKaryawans = [];

    public function mount()
    {
         $now = Carbon::now('Asia/Jakarta');

        $this->bulan = $now->format('n');
        $this->tahun = $now->format('Y');
        
        $this->units = UnitKerja::all();
        $unitKepegawaianId = 87;
        $user = auth()->user();
        $this->isKepegawaian = $user->unit_id == $unitKepegawaianId;
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function updatedSelectedUserAktif()
    {
        $this->resetPage();
    }

    public function updatedSelectedUnit()
    {
        $this->resetPage();
    }

    public function loadData()
    {
        // $roles = ['Super Admin', 'Kepala Seksi Kepegawaian', 'Staf Kepegawaian', 'Kepegawaian', 'Administrator'];
        $roles = [1, 2, 14, 12];
        $unit_id = Auth::user()->unit_id;

        $users = User::with([
            'kategorijabatan',
            'unitKerja',
            'roles',
            'pendidikanUser',
            'jenis'
        ])
            ->where('id', '>', 1)
            ->where('jenis_id', 3)
            ->when(!Auth::user()->hasAnyRole($roles), function ($query) use ($unit_id) {
                $unitIds = UnitKerja::where('id', $unit_id)
                    ->orWhere('parent_id', $unit_id)
                    ->pluck('id')
                    ->toArray();
                $query->whereIn('unit_id', $unitIds);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('no_ktp', 'like', '%' . $this->search . '%')
                        ->orWhere('alamat', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kategorijabatan', function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('unitKerja', function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when(isset($this->selectedUserAktif), function ($query) {
                $query->where('status_karyawan', $this->selectedUserAktif);
            })
            ->when($this->selectedUnit, function ($query) {
                $unitIds = UnitKerja::where('id', $this->selectedUnit)
                    ->orWhere('parent_id', $this->selectedUnit)
                    ->pluck('id')
                    ->toArray();
                $query->whereIn('unit_id', $unitIds);
            })
            ->get(); // Ambil semua dulu untuk bisa filter manual pakai Carbon

        // Proses tambahan: masa kerja, gaji, kenaikan berkala, golongan
        foreach ($users as $user) {
            if ($user->tmt) {
                $baseTmt = Carbon::parse($user->tmt);
                $now = Carbon::now();

                // Hitung masa kerja berdasarkan bulan (tanpa float)
                $masaKerjaTotal = intval($baseTmt->diffInMonths($now)); // ← ini bulat, tanpa float
                $user->masa_kerja_total = $masaKerjaTotal;

                // Gaji sekarang
                $kategoriJabatanId = $user->jabatan_id ?? $user->fungsi_id ?? $user->umum_id;
                $pendidikanId = $user->pendidikanUser?->id;
                $gapokKontrak = GapokKontrak::where('kategori_jabatan_id', $kategoriJabatanId)
                    ->where('pendidikan_id', $pendidikanId)
                    ->where('min_masa_kerja', '<=', $masaKerjaTotal)
                    ->where('max_masa_kerja', '>=', $masaKerjaTotal)
                    ->first();

                $user->gaji_sekarang = $gapokKontrak?->nominal_aktif ?? $gapokKontrak?->nominal;

                // ✅ Kenaikan Berkala (tiap 1 tahun dari TMT)
                $kenaikanDate = $baseTmt->copy();
                while ($kenaikanDate->lt($now)) {
                    $kenaikanDate->addYears(1);
                }
                $user->kenaikan_berkala_waktu = $kenaikanDate->format('Y-m-d');

                $masaKerjaTahunDepan = $masaKerjaTotal + 1;

                $gapokNaik = GapokKontrak::where('kategori_jabatan_id', $kategoriJabatanId)
                    ->where('pendidikan_id', $pendidikanId)
                    ->where('min_masa_kerja', '<=', $masaKerjaTahunDepan)
                    ->where('max_masa_kerja', '>=', $masaKerjaTahunDepan)
                    ->first();

                if ($gapokNaik) {
                    // Cek apakah ada penyesuaian aktif
                    $penyesuaian = $gapokNaik->penyesuaian()
                        ->where('tanggal_berlaku', '<=', $kenaikanDate)
                        ->orderByDesc('tanggal_berlaku')
                        ->first();

                    $user->kenaikan_berkala_gaji = $penyesuaian?->nominal_baru ?? $gapokNaik->nominal;
                } else {
                    $user->kenaikan_berkala_gaji = null;
                }
            } else {
                $user->masa_kerja_tahun = null;
                $user->masa_kerja_bulan = null;
                $user->gaji_sekarang = null;
                $user->kenaikan_berkala_waktu = null;
                $user->kenaikan_berkala_gaji = null;
            }
        }

        // ✅ Filter berdasarkan bulan/tahun jika salah satu dipilih
        if (!empty($this->bulan) || !empty($this->tahun)) {
            $bulan = (int) $this->bulan;
            $tahun = (int) $this->tahun;

            $users = $users->filter(function ($user) use ($bulan, $tahun) {
                $tglBerkala = $user->kenaikan_berkala_waktu ?? null;

                $matchBerkala = $tglBerkala ? Carbon::parse($tglBerkala) : null;

                return ($matchBerkala && (
                    (empty($bulan) || $matchBerkala->month == $bulan) &&
                    (empty($tahun) || $matchBerkala->year == $tahun)
                )
                );
            })->values();
        }

        // Manual pagination (karena sudah pakai ->get())
        $perPage = 15;
        $currentPage = $this->page ?? 1;
        $paged = $users->slice(($currentPage - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator(
            $paged,
            $users->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function render()
    {
        return view('livewire.kenaikan-kontrak', [
            'users' => $this->loadData(),
        ]);
    }
}
