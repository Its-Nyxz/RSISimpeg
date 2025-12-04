<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Gapok;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\MasterGapok;
use Livewire\WithPagination;
use App\Models\JenisKaryawan;
use App\Models\MasterGolongan;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

class KenaikanGolongan extends Component
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
        $this->units = UnitKerja::all();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
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
        $roles = ['Super Admin', 'Kepala Seksi Kepegawaian', 'Staf Kepegawaian', 'Kepegawaian', 'Administrator'];
        $unit_id = Auth::user()->unit_id;

        $users = User::with([
            'kategorijabatan',
            'unitKerja',
            'roles',
            'pendidikanUser',
            'pendingGolonganGapok',
            'jenis'
        ])
            ->where('id', '>', 1)
            ->where('jenis_id', 1)
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
            ->get();

        foreach ($users as $user) {

            if ($user->tmt) {
                $baseTmt = Carbon::parse($user->tmt);
                $now = Carbon::now();

                // Hitung masa kerja
                $diff = $baseTmt->diff($now);
                $masaKerjaTahun = $diff->y;
                $masaKerjaBulan = $diff->m;
                $masaKerjaTotal = floor($baseTmt->floatDiffInYears($now));

                $user->masa_kerja_tahun = $masaKerjaTahun;
                $user->masa_kerja_bulan = $masaKerjaBulan;
                $user->masa_kerja_golongan = $masaKerjaTotal;

                // GAPOK SEKARANG
                $gapok = MasterGapok::where('gol_id', $user->gol_id)
                    ->where('masa_kerja', '<=', $masaKerjaTotal)
                    ->orderByDesc('masa_kerja')
                    ->first();
                $user->gaji_sekarang = $gapok?->nominal_gapok;

                // KENAIKAN BERKALA (GAJI)
                $kenaikanDate = $baseTmt->copy();
                while ($kenaikanDate->lt($now)) {
                    $kenaikanDate->addYears(2);
                }
                $user->kenaikan_berkala_waktu = $kenaikanDate->format('Y-m-d');

                $gapokNaik = MasterGapok::where('gol_id', $user->gol_id)
                    ->where('masa_kerja', '<=', $masaKerjaTotal + 2)
                    ->orderByDesc('masa_kerja')
                    ->first();
                $user->kenaikan_berkala_gaji = $gapokNaik?->nominal_gapok;

                // KENAIKAN GOLONGAN
                $pendidikan = $user->pendidikanUser;
                $currentGolId = $user->gol_id;

                if ($pendidikan && $currentGolId) {
                    $maksimalKenaikan = 4;
                    $kenaikanTercapai = min($maksimalKenaikan, floor($masaKerjaTotal / 4));

                    $daftarGolongan = MasterGolongan::where('id', '>', $currentGolId)
                        ->where('id', '<=', $pendidikan->maxim_gol)
                        ->orderBy('id')
                        ->take($maksimalKenaikan)
                        ->get();

                    if ($kenaikanTercapai < $daftarGolongan->count()) {

                        $golonganBerikutnya = $daftarGolongan->get($kenaikanTercapai);
                        $kenaikanKe = $kenaikanTercapai + 1;

                        $kenaikanGolDate = $baseTmt->copy()->addYears($kenaikanKe * 4);
                        while ($kenaikanGolDate->lt($now)) {
                            $kenaikanKe++;
                            $kenaikanGolDate = $baseTmt->copy()->addYears($kenaikanKe * 4);
                        }

                        $user->kenaikan_golongan_waktu = $kenaikanGolDate->format('Y-m-d');

                        $kenaikanGapok = MasterGapok::where('gol_id', $golonganBerikutnya->id)
                            ->where('masa_kerja', '<=', $masaKerjaTotal + 4)
                            ->orderByDesc('masa_kerja')
                            ->first();

                        $user->kenaikan_golongan_gaji = $kenaikanGapok?->nominal_gapok;
                        $user->golonganBaruNama = $golonganBerikutnya->nama ?? '-';

                    } else {

                        $user->kenaikan_golongan_waktu = $baseTmt->copy()->addYears(16)->format('Y-m-d');
                        $user->kenaikan_golongan_gaji = $gapok?->nominal_gapok;
                        $user->golonganBaruNama = $user->golongan->nama ?? '-';
                    }

                    $user->golongan_tertinggi = (
                        $kenaikanTercapai >= $maksimalKenaikan ||
                        $daftarGolongan->count() <= $kenaikanTercapai
                    );

                    // SP PENANGGUHAN
                    $spList = PeringatanKaryawan::where('user_id', $user->id)
                        ->whereIn('tingkat', ['II', 'III'])
                        ->where('tanggal_sp', '>=', now()->subYears(4))
                        ->orderBy('tanggal_sp')
                        ->get();

                    $penundaanGajiTahun = 0;
                    $penundaanGolonganTahun = 0;

                    foreach ($spList as $sp) {
                        if ($sp->tingkat === 'II') {
                            $penundaanGajiTahun += 2;
                        } elseif ($sp->tingkat === 'III') {
                            $penundaanGajiTahun += 2;
                            $penundaanGolonganTahun += 4;
                        }
                    }

                    // Terapkan penundaan Gaji
                    if ($penundaanGajiTahun) {
                        $user->kenaikan_berkala_waktu = Carbon::parse($user->kenaikan_berkala_waktu)
                            ->addYears($penundaanGajiTahun)
                            ->format('Y-m-d');
                    }

                    // Terapkan penundaan Golongan
                    if ($penundaanGolonganTahun) {
                        $user->kenaikan_golongan_waktu = Carbon::parse($user->kenaikan_golongan_waktu)
                            ->addYears($penundaanGolonganTahun)
                            ->format('Y-m-d');
                    }

                    // ===========================================
                    // LOGIKA BARU: Jika tahun golongan == tahun gaji → Tambah 2 tahun pada kenaikan berkala
                    // ===========================================
                    if (!empty($user->kenaikan_golongan_waktu) && !empty($user->kenaikan_berkala_waktu)) {
                        $tahunGol = Carbon::parse($user->kenaikan_golongan_waktu)->year;
                        $tahunGaji = Carbon::parse($user->kenaikan_berkala_waktu)->year;

                        if ($tahunGol == $tahunGaji) {
                            $user->kenaikan_berkala_waktu = Carbon::parse($user->kenaikan_berkala_waktu)
                                ->addYears(2)
                                ->format('Y-m-d');
                        }
                    }

                } else {
                    $user->kenaikan_golongan_waktu = null;
                    $user->kenaikan_golongan_gaji = null;
                    $user->golongan_tertinggi = false;
                }
            } else {
                $user->masa_kerja_tahun = null;
                $user->masa_kerja_bulan = null;
                $user->gaji_sekarang = null;
                $user->kenaikan_berkala_waktu = null;
                $user->kenaikan_berkala_gaji = null;
                $user->kenaikan_golongan_waktu = null;
                $user->kenaikan_golongan_gaji = null;
            }
        }

        // FILTER: Bulan & Tahun
        if (!empty($this->bulan) || !empty($this->tahun)) {
            $bulan = (int) $this->bulan;
            $tahun = (int) $this->tahun;

            $users = $users->filter(function ($user) use ($bulan, $tahun) {
                $tglGol = $user->kenaikan_golongan_waktu ?? null;
                $tglBerkala = $user->kenaikan_berkala_waktu ?? null;

                $matchGol = $tglGol ? Carbon::parse($tglGol) : null;
                $matchBerkala = $tglBerkala ? Carbon::parse($tglBerkala) : null;

                return (
                    ($matchGol && (
                        (empty($bulan) || $matchGol->month == $bulan) &&
                        (empty($tahun) || $matchGol->year == $tahun)
                    )) ||
                    ($matchBerkala && (
                        (empty($bulan) || $matchBerkala->month == $bulan) &&
                        (empty($tahun) || $matchBerkala->year == $tahun)
                    ))
                );
            })->values();
        }

        // PAGINATION
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


    public function approveKenaikan($userId)
    {
        $user = User::with('pendingGolonganGapok', 'golongan')->find($userId);

        if (!$user || !$user->pendingGolonganGapok) {
            return redirect()->route('kenaikan.index')->with('error', 'Data kenaikan golongan tidak ditemukan.');
        }

        $gapok = $user->pendingGolonganGapok;

        if ($gapok->jenis_kenaikan !== 'golongan' || $gapok->status) {
            return redirect()->route('kenaikan.index')->with('error', 'Kenaikan sudah disetujui atau tidak valid.');
        }

        $admin = auth()->user();

        // Update golongan dan status gapok
        $user->gol_id = $gapok->gol_id_baru;
        $user->save();

        $gapok->status = true;
        $gapok->save();

        // Kirim notifikasi ke user
        $message = 'Kenaikan golongan Anda dari ' . ($user->golongan->nama ?? '-') . ' ke golongan ' . $gapok->golonganBaru->nama . ' telah <span class="text-success-600 font-bold">disetujui</span> oleh ' . $admin->name . '.';

        Notification::send($user, new UserNotification($message, null));

        return redirect()->route('kenaikan.index')->with('success', 'Kenaikan golongan berhasil disetujui.');
    }

    public function rejectKenaikan($userId, $reason = null)
    {
        $user = User::with('pendingGolonganGapok')->find($userId);

        if (!$user || !$user->pendingGolonganGapok) {
            return redirect()->route('kenaikan.index')->with('error', 'Data kenaikan tidak ditemukan.');
        }

        // Tambahkan log alasan penolakan (jika disimpan di model, misalnya kolom 'catatan')
        if ($reason) {
            $user->pendingGolonganGapok->catatan = $reason;
            $user->pendingGolonganGapok->save();
        }

        // Kirim notifikasi ke user
        $message = 'Kenaikan golongan Anda telah <span class="text-red-600 font-bold">DITOLAK</span>' .
            ($reason ? ' dengan alasan: <em>' . $reason . '</em>.' : '.');

        Notification::send($user, new UserNotification($message, null));

        return redirect()->route('kenaikan.index')->with('success', 'Kenaikan golongan ditolak.');
    }


    public function render()
    {
        return view('livewire.kenaikan-golongan', [
            'users' => $this->loadData(),
        ]);
    }
}
