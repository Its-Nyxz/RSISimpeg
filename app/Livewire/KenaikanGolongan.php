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
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class KenaikanGolongan extends Component
{
    use WithPagination;

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $roles;
    public $units;
    public $selectedUserAktif = 1; // Default aktif
    public $selectedUnit = null;
    public $isKepegawaian = false;

    public $selectedJenisKaryawan = null;
    public $jenisKaryawans = [];

    public function mount()
    {
        $this->units = UnitKerja::all();
        $unitKepegawaianId = UnitKerja::where('nama', 'KEPEGAWAIAN')->value('id');
        $user = auth()->user();
        $this->jenisKaryawans = JenisKaryawan::all();
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
            ->when($this->selectedJenisKaryawan, function ($query) {
                $query->where('jenis_id', $this->selectedJenisKaryawan);
            })
            ->paginate(15);

        // Proses tambahan: masa kerja, gaji, kenaikan berkala, golongan
        foreach ($users as $user) {
            if ($user->tmt) {
                // Ambil masa kerja real-time dari TMT untuk tampilan
                $diff = Carbon::parse($user->tmt)->diff(Carbon::now());
                $user->masa_kerja_tahun = $diff->y;
                $user->masa_kerja_bulan = $diff->m;

                // Masa kerja dari DB (sudah disesuaikan)
                $masaKerjaDariDB = (int) $user->masa_kerja;
                $user->masa_kerja_golongan = $masaKerjaDariDB;

                // Gaji sekarang
                $gapok = MasterGapok::where('gol_id', $user->gol_id)
                    ->where('masa_kerja', '<=', $masaKerjaDariDB)
                    ->orderByDesc('masa_kerja')
                    ->first();
                $user->gaji_sekarang = $gapok?->nominal_gapok;

                // Kenaikan Gaji Berkala
                $baseTmt = Carbon::parse($user->tmt);
                $next_mk = $masaKerjaDariDB + (2 - ($masaKerjaDariDB % 2));
                $kenaikanDate = $baseTmt->copy()->addYears($next_mk);
                // Lewati jika tanggal kenaikan sudah lewat
                while ($kenaikanDate->lt(Carbon::now())) {
                    $next_mk += 2;
                    $kenaikanDate = $baseTmt->copy()->addYears($next_mk);
                }

                $user->kenaikan_berkala_waktu = $kenaikanDate->format('Y-m-d');

                $next_gapok = MasterGapok::where('gol_id', $user->gol_id)
                    ->where('masa_kerja', '<=', $masaKerjaDariDB + 2)
                    ->orderByDesc('masa_kerja')
                    ->first();
                $user->kenaikan_berkala_gaji = $next_gapok?->nominal_gapok;

                // Kenaikan Golongan
                $pendidikan = $user->pendidikanUser;
                $currentGolId = $user->gol_id;
                $masaKerjaDariDB = (int) $user->masa_kerja;

                if ($pendidikan && $currentGolId) {
                    $maksimalKenaikan = 4;
                    $kenaikanTercapai = min($maksimalKenaikan, floor($masaKerjaDariDB / 4));

                    // Ambil daftar golongan setelah golongan sekarang sampai max pendidikan
                    $daftarGolongan = MasterGolongan::where('id', '>', $currentGolId)
                        ->where('id', '<=', $pendidikan->maxim_gol)
                        ->orderBy('id')
                        ->take($maksimalKenaikan)
                        ->get();

                    if ($kenaikanTercapai < $daftarGolongan->count()) {
                        // Masih bisa naik
                        $golonganBerikutnya = $daftarGolongan->get($kenaikanTercapai); // index ke-n
                        $kenaikanKe = $kenaikanTercapai + 1;
                        $baseTmt = Carbon::parse($user->tmt);
                        $kenaikanDate = $baseTmt->copy()->addYears($kenaikanKe * 4);

                        // Pastikan tanggal kenaikan belum lewat (karena masa kerja bisa tidak sesuai dengan TMT)
                        while ($kenaikanDate->lt(Carbon::now())) {
                            $kenaikanKe++;
                            $kenaikanDate = $baseTmt->copy()->addYears($kenaikanKe * 4);
                        }

                        // Simpan tanggal kenaikan
                        $user->kenaikan_golongan_waktu = $kenaikanDate->format('Y-m-d');
                        $kenaikanGapok = MasterGapok::where('gol_id', $golonganBerikutnya->id)
                            ->where('masa_kerja', '<=', $masaKerjaDariDB + 4)
                            ->orderByDesc('masa_kerja')
                            ->first();

                        $user->kenaikan_golongan_gaji = $kenaikanGapok?->nominal_gapok;
                    } else {
                        // Sudah maksimal
                        $user->kenaikan_golongan_waktu = Carbon::parse($user->tmt)
                            ->addYears($maksimalKenaikan * 4)
                            ->format('Y-m-d');

                        $gapokTertinggi = MasterGapok::where('gol_id', $currentGolId)
                            ->where('masa_kerja', '<=', $masaKerjaDariDB)
                            ->orderByDesc('masa_kerja')
                            ->first();

                        $user->kenaikan_golongan_gaji = $gapokTertinggi?->nominal_gapok;
                    }

                    $user->golongan_tertinggi = (
                        $kenaikanTercapai >= $maksimalKenaikan ||
                        $daftarGolongan->count() <= $kenaikanTercapai
                    );
                } else {
                    $user->kenaikan_golongan_waktu = null;
                    $user->kenaikan_golongan_gaji = null;
                    $user->golongan_tertinggi = false;
                }
            } else {
                // tanpa TMT
                $user->masa_kerja_tahun = null;
                $user->masa_kerja_bulan = null;
                $user->gaji_sekarang = null;
                $user->kenaikan_berkala_waktu = null;
                $user->kenaikan_berkala_gaji = null;
                $user->kenaikan_golongan_waktu = null;
                $user->kenaikan_golongan_gaji = null;
            }
        }


        return $users;
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
        $message = 'Kenaikan golongan Anda dari ' . ($user->golongan->nama ?? '-') . ' ke golongan ' . $gapok->golonganBaru->nama . ' telah <span class="text-green-600 font-bold">disetujui</span> oleh ' . $admin->name . '.';

        Notification::send($user, new \App\Notifications\UserNotification($message, null));

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

        Notification::send($user, new \App\Notifications\UserNotification($message, null));

        return redirect()->route('kenaikan.index')->with('success', 'Kenaikan golongan ditolak.');
    }


    public function render()
    {
        return view('livewire.kenaikan-golongan', [
            'users' => $this->loadData(),
        ]);
    }
}
