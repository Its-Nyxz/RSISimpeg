<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\Kategoripph;
use App\Models\MasterFungsi;
use App\Models\MasterKhusus;
use App\Models\JenisKaryawan;
use App\Models\MasterJabatan;
use App\Models\MasterGolongan;
use App\Models\RiwayatJabatan;
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // sementara

class KaryawanForm extends Component
{
    public $karyawan;
    public $user;
    public $user_id;
    public $id;
    public $nama;
    public $namapendidikan;
    public $institusi;
    public $username;
    public $nip;
    public $no_ktp;
    public $no_hp;
    public $email;
    public $units;
    public $jabatans;
    public $fungsionals;
    public $fungsi;
    public $fungsis;
    public $umum;
    public $umums;
    public $formasi;
    public $trans;
    public $khusus;
    public $katjab;
    public $jeniskaryawan;
    public $jenisKaryawanNama;
    public $selectedJenisKaryawan; //untuk select jenis karyawan
    public $gol;
    public $golongans;
    public $jk;
    public $tempat;
    public $tanggal_lahir;
    public $alamat;
    public $pendidikans;
    public $no_rek;
    public $kategori;
    public $selectedPph;
    public $parentPphs;
    public $childPphs;
    public $tmt;
    public $masakerja;
    public $roles;
    public $typeShift;
    public $selectedRoles = [];
    public $selectedPendidikan; // ID pendidikan yang dipilih
    public $filteredGolongans = []; // Golongan yang difilter berdasarkan pendidikan
    public $filteredKhusus = [];
    public $selectedGolongan; // ID golongan yang dipilih otomatis
    public string $selectedGolonganNama; // Nama golongan yang ditampilkan di input
    public $jabatan;
    public $bpjsOrtu;
    public $fungsional;
    public $jabatanAwal;
    public $fungsionalAwal;
    public $jabatanChanged = false;
    public $fungsionalChanged = false;
    public $unit; // Input untuk unit kerja
    public $suggestions = [
        'jabatan' => [],
        'fungsional' => [],
        'unit' => [],
    ];
    protected $listeners = ['savekaryawan'];

    public function fetchSuggestions($field, $value)
    {
        $this->suggestions[$field] = [];

        // if ($value) {
        if ($field === 'jabatan') {
            $categories = KategoriJabatan::where('nama', 'like', "%$value%")
                ->whereIn('tunjangan', ['jabatan', 'umum']) // hanya ambil jabatan tunjangan kabatan & umum
                ->get()
                ->groupBy('tunjangan');

            foreach ($categories as $tunjangan => $katjabList) {
                $this->suggestions[$field][$tunjangan] = $katjabList->pluck('nama')->toArray();
            }
        } elseif ($field === 'fungsional') {
            $categories = KategoriJabatan::where('nama', 'like', "%$value%")
                ->where('tunjangan', 'fungsi') // Filter hanya yang tunjangan = 'fungsi'
                ->get()
                ->groupBy('tunjangan');

            foreach ($categories as $tunjangan => $katjabList) {
                $this->suggestions[$field][$tunjangan] = $katjabList->pluck('nama')->toArray();
            }
        } elseif ($field === 'unit') {
            $this->suggestions[$field] = UnitKerja::where('nama', 'like', "%$value%")->pluck('nama')->toArray();
        }
    }

    public function selectSuggestion($field, $value)
    {
        if ($field === 'jabatan') {
            if ($this->jabatanAwal !== $value) {
                $this->dispatch('confirmJabatanChange');
            }
            $this->jabatan = $value;
        } elseif ($field === 'fungsional') {
            if ($this->fungsionalAwal !== $value) {
                $this->dispatch('confirmFungsionalChange');
            }
            $this->fungsional = $value;
        } elseif ($field === 'unit') {
            $this->unit = $value;
        }

        $this->suggestions[$field] = [];
    }


    public function hideSuggestions($field)
    {
        $this->suggestions[$field] = [];
    }

    public function updatedSelectedPendidikan($pendidikanId)
    {
        $pendidikan = MasterPendidikan::with(['minimGolongan', 'maximGolongan'])->find($pendidikanId);

        if ($pendidikan) {
            $this->filteredGolongans = MasterGolongan::where('id', '>=', $pendidikan->minim_gol)
                ->where('id', '<=', $pendidikan->maxim_gol)
                ->get();

            $baseGolonganId = $pendidikan->minim_gol;
            $maxGolonganId = $pendidikan->maxim_gol;

            $jenis = JenisKaryawan::find($this->selectedJenisKaryawan)?->nama;

            // Golongan tetap dihitung pakai tahun walau kontrak
            $masaKerjaDalamTahun = $this->masakerja;

            if (strtolower($jenis) === 'kontrak') {
                $masaKerjaDalamTahun = floor($this->masakerja / 12); // convert bulan → tahun
            }

            if ($masaKerjaDalamTahun !== null) {
                $increment = min(floor($masaKerjaDalamTahun / 4), 4);
                $calculatedGolonganId = $baseGolonganId + $increment;
                $this->selectedGolongan = $calculatedGolonganId <= $maxGolonganId ? $calculatedGolonganId : $maxGolonganId;
                $this->selectedGolonganNama = MasterGolongan::find($this->selectedGolongan)?->nama ?? '';
            } else {
                $this->selectedGolongan = $baseGolonganId;
                $this->selectedGolonganNama = MasterGolongan::find($baseGolonganId)?->nama ?? '';
            }
        } else {
            $this->filteredGolongans = [];
            $this->selectedGolongan = null;
            $this->selectedGolonganNama = '';
        }
    }

    public function selectGolongan()
    {
        if ($this->selectedPendidikan && $this->tmt) {
            $this->updatedSelectedPendidikan($this->selectedPendidikan);
            // dd($this->selectedGolonganNama);
        }
    }
    public function updatedMasakerja($value)
    {
        if ($this->selectedPendidikan) {
            $this->updatedSelectedPendidikan($this->selectedPendidikan);
        }
    }

    public function updatedFormasi($formasi)
    {
        // Periksa apakah formasi mengandung "fungsi_"
        if (str_starts_with($formasi, 'fungsi_')) {
            // Ambil ID fungsi dari formasi
            $fungsiId = str_replace('fungsi_', '', $formasi);

            // Cek apakah fungsi ID terkait dengan dokter
            $fungsi = MasterFungsi::find($fungsiId);

            if ($fungsi) {
                $namaFungsi = strtolower($fungsi->nama);

                if (str_contains($namaFungsi, 'dokter spesialis')) {
                    // Filter khusus untuk Dokter Spesialis + Dokter Penanggung Jawab
                    $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Dokter Spesialis%')
                        ->orWhere('nama', 'like', '%Dokter penanggungjawab%')
                        ->orWhere('nama', 'like', '%Tenaga Kesehatan%')
                        ->get();
                } elseif (str_contains($namaFungsi, 'dokter umum')) {
                    // Filter khusus untuk Dokter Umum + Dokter Penanggung Jawab
                    $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Dokter Umum%')
                        ->orWhere('nama', 'like', '%Dokter penanggungjawab%')
                        ->orWhere('nama', 'like', '%Tenaga Kesehatan%')
                        ->get();
                } elseif (str_contains($namaFungsi, 'dokter gigi')) {
                    // Filter khusus untuk Dokter Gigi + Dokter Penanggung Jawab
                    $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Dokter Gigi%')
                        ->orWhere('nama', 'like', '%Dokter penanggungjawab%')
                        ->orWhere('nama', 'like', '%Tenaga Kesehatan%')
                        ->get();
                } else {
                    // Default untuk tenaga kesehatan
                    $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Tenaga Kesehatan%')->get();
                }
            } else {
                // Default jika fungsi tidak ditemukan
                $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Tenaga Kesehatan%')->get();
            }
        } else {
            // Jika bukan "fungsi_", gunakan default
            $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Tenaga Kesehatan%')->get();
        }
    }


    public function mount($user = null)
    {
        $this->user = $user;
        if ($user != null) {
            $this->user_id = $user->id;
            $this->nip = $user->nip;
            $this->nama = $user->name;
            $this->email = $user->email;
            $this->no_ktp = $user->no_ktp;
            $this->no_hp = $user->no_hp;
            $this->no_rek = $user->no_rek;
            $this->selectedPendidikan = $user->kategori_pendidikan;
            $this->namapendidikan = $user->pendidikan;
            $this->institusi = $user->institusi;
            $this->jk = $user->jk;
            $this->alamat = $user->alamat;
            $this->tempat = $user->tempat;
            $this->tanggal_lahir = $user->tanggal_lahir;
            $this->unit = $user->unitKerja->nama ?? '';
            $this->jabatan = $user->kategorijabatan->nama ?? null;
            $this->jabatanAwal = $this->jabatan; // Simpan nilai awal jabatan
            $this->jabatanChanged = false;
            $this->fungsional = $user->kategorifungsional->nama ?? null;
            $this->fungsionalAwal = $this->fungsional; // Simpan nilai awal jabatan
            $this->fungsionalChanged = false;
            $this->selectedJenisKaryawan = $user->jenis_id;
            $this->tmt = $user->tmt;
            $this->masakerja = $user->masa_kerja;
            $this->selectedGolonganNama = $user->golongan->nama ?? '';
            $this->gol = $user->gol_id;
            $this->khusus = $user->khusus_id;
            $this->selectedPph = $user->kategori_id;
            $this->selectedRoles = $user->roles->pluck('id')->toArray();
            $this->typeShift = $user->type_shift;
            $this->bpjsOrtu = (bool) $user->bpjs_ortu;
            $this->jenisKaryawanNama = JenisKaryawan::find($this->selectedJenisKaryawan)?->nama;
        }

        $this->units = UnitKerja::all();
        $this->jeniskaryawan = JenisKaryawan::all();
        $this->pendidikans = MasterPendidikan::with(['minimGolongan', 'maximGolongan'])->get();
        $this->golongans = MasterGolongan::all();
        $this->jabatans = MasterJabatan::all();
        $this->fungsis = MasterFungsi::all();
        $this->umums = MasterUmum::all();
        $this->katjab = KategoriJabatan::all();
        $this->filteredKhusus = MasterKhusus::all();
        $this->selectedJenisKaryawan = $user->jenis_id ?? null;
        $this->jenisKaryawanNama = JenisKaryawan::find($this->selectedJenisKaryawan)?->nama;


        // Pisahkan antara Parent dan Child PPH
        $this->parentPphs = Kategoripph::whereNull('parent_id')->get();
        $this->childPphs = Kategoripph::whereNotNull('parent_id')->get();

        // Default value untuk child PPH yang dipilih
        $this->roles = Role::where('id', '>', '3')->get();
    }

    public function save()
    {
        // Validasi input
        $this->validate([
            'nama' => 'nullable|string|max:255|unique:users,name,' . ($this->user_id ?? 'NULL'),
            'nip' => 'nullable|max:50|unique:users,nip,' . ($this->user_id ?? 'NULL'),
            'email' => 'nullable|email|max:255|unique:users,email,' . ($this->user_id ?? 'NULL'),
            'no_ktp' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:15',
            'no_rek' => 'nullable',
            'selectedPendidikan' => 'nullable|exists:master_pendidikan,id',
            'namapendidikan' => 'nullable',
            'institusi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'unit' => 'nullable',
            'jabatan' => 'nullable',
            'fungsional' => 'nullable',
            'selectedJenisKaryawan' => 'nullable',
            'tmt' => 'nullable',
            'masakerja' => 'nullable',
            'selectedGolonganNama' => 'nullable',
            'khusus' => 'nullable',
            'selectedPph' => 'nullable',
            'selectedRoles' => 'nullable',
            'typeShift' => 'nullable',
        ]);

        $unit = UnitKerja::where('nama', $this->unit)->first(); // Cari ID berdasarkan nama unit
        $kategoriJabatan = KategoriJabatan::where('nama', $this->jabatan)->first(); // Cari ID berdasarkan nama kategori jabatan
        $kategoriFungsional = KategoriJabatan::where('nama', $this->fungsional)->first(); // Cari ID berdasarkan nama kategori jabatan
        // if (!$unit) {
        //     return back()->with('error', 'Unit tidak ditemukan.');
        // } elseif (!$kategoriJabatan) {
        //     return back()->with('error', 'Kategori Jabatan tidak ditemukan.');
        // }
        if ($this->tmt) {
            $start = Carbon::parse($this->tmt);
            $now = Carbon::now();

            $jenis = JenisKaryawan::find($this->selectedJenisKaryawan)?->nama;

            if (strtolower($jenis) === 'kontrak') {
                // Hitung dalam bulan
                $this->masakerja = floor($start->diffInMonths($now));
            } else {
                // Hitung dalam tahun (pembulatan bawah)
                $this->masakerja = floor($start->floatDiffInYears($now));
            }
        }


        $user = User::create([
            'name' => $this->nama ?? null,
            'nip' => $this->nip ?? null,
            'email' => $this->email ?? null,
            'no_ktp' => $this->no_ktp ?? null,
            'no_hp' => $this->no_hp ?? null,
            'no_rek' => $this->no_rek ?? null,
            'kategori_pendidikan' => $this->selectedPendidikan ?? null,
            'pendidikan' => $this->namapendidikan ?? null,
            'institusi' => $this->institusi ?? null,
            'jk' => $this->jk ?? null,
            'alamat' => $this->alamat ?? null,
            'tempat' => $this->tempat ?? null,
            'tanggal_lahir' => $this->tanggal_lahir ?? null,
            'unit_id' => $unit->id ?? null,
            'jabatan_id' => $kategoriJabatan->id ?? null,
            'fungsi_id' => $kategoriFungsional->id ?? null,
            'jenis_id' => $this->selectedJenisKaryawan ?? null,
            'tmt' => $this->tmt ?? null,
            'masa_kerja' => $this->masakerja ?? null,
            'gol_id' => $this->selectedGolongan ?? $this->gol ?? null,
            'khusus_id' => $this->khusus ?? null,
            'kategori_id' => $this->selectedPph ?? null,
            'type_shift' => $this->typeShift ?? null,
            'bpjs_ortu' =>  $this->bpjsOrtu ?? null,
            'password' => Hash::make('123'),
        ]);


        // Update roles jika user baru dibuat atau diperbarui
        if (!empty($this->selectedRoles)) {
            $roles = Role::whereIn('id', (array) $this->selectedRoles)->pluck('name')->toArray();
            $user->syncRoles($roles);
        }
        return redirect()->route('datakaryawan.index')->with('success', 'Karyawan berhasil diTambah.');
    }



    public function updateKaryawan()
    {

        $this->validate([
            'nama' => 'nullable|string|max:255',
            'nip' => 'nullable|max:50|unique:users,nip,' .  ($this->user_id ?? 'NULL'),
            'email' => 'nullable|email|max:255|unique:users,email,' .  ($this->user_id ?? 'NULL'),
            'no_ktp' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:15',
            'no_rek' => 'nullable',
            'selectedPendidikan' => 'nullable|exists:master_pendidikan,id',
            'namapendidikan' => 'nullable',
            'institusi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'unit' => 'nullable',
            'jabatan' => 'nullable',
            'fungsional' => 'nullable',
            'selectedJenisKaryawan' => 'nullable',
            'tmt' => 'nullable',
            'masakerja' => 'nullable',
            'selectedGolonganNama' => 'nullable',
            'khusus' => 'nullable',
            'selectedPph' => 'nullable',
            'selectedRoles' => 'nullable',
            'typeShift' => 'nullable',
        ]);
        $unit = UnitKerja::where('nama', $this->unit)->first(); // Cari ID berdasarkan nama unit
        $kategoriJabatan = KategoriJabatan::where('nama', $this->jabatan)->first();
        $kategoriFungsional = KategoriJabatan::where('nama', $this->fungsional)->first(); // Cari ID berdasarkan nama kategori jabatan
        // Cari ID berdasarkan nama kategori jabatan
        // if (!$unit) {
        //     return back()->with('error', 'Unit tidak ditemukan.');
        // } elseif (!$kategoriJabatan) {
        //     return back()->with('error', 'Kategori Jabatan tidak ditemukan.');
        // }

        if ($this->tmt) {
            $start = Carbon::parse($this->tmt);
            $now = Carbon::now();

            $jenis = JenisKaryawan::find($this->selectedJenisKaryawan)?->nama;

            if (strtolower($jenis) === 'kontrak') {
                // Hitung dalam bulan
                $this->masakerja = floor($start->diffInMonths($now));
            } else {
                // Hitung dalam tahun (pembulatan bawah)
                $this->masakerja = floor($start->floatDiffInYears($now));
            }
        }

        $user = User::findOrFail($this->user_id);
        // Simpan jabatan dan fungsional lama
        $oldJabatanId = $user->jabatan_id;
        $oldFungsionalId = $user->fungsi_id;

        $user = User::findOrFail($this->user_id);
        $user->update([
            'name' => $this->nama ?? null,
            'nip' => $this->nip ?? null,
            'email' => $this->email ?? null,
            'no_ktp' => $this->no_ktp ?? null,
            'no_hp' => $this->no_hp ?? null,
            'no_rek' => $this->no_rek ?? null,
            'kategori_pendidikan' => $this->selectedPendidikan ?? null,
            'pendidikan' => $this->namapendidikan ?? null,
            'institusi' => $this->institusi ?? null,
            'jk' => $this->jk ?? null,
            'alamat' => $this->alamat ?? null,
            'tempat' => $this->tempat ?? null,
            'tanggal_lahir' => $this->tanggal_lahir ?? null,
            'unit_id' => $unit->id ?? null,
            'jabatan_id' => $kategoriJabatan->id ?? null,
            'fungsi_id' => $kategoriFungsional->id ?? null,
            'jenis_id' => $this->selectedJenisKaryawan ?? null,
            'tmt' => $this->tmt ?? null,
            'masa_kerja' => $this->masakerja ?? null,
            'gol_id' => $this->selectedGolongan ?? $this->gol ?? null,
            'khusus_id' => $this->khusus ?? null,
            'kategori_id' => $this->selectedPph ?? null,
            'type_shift' => $this->typeShift ?? null,
            'bpjs_ortu' =>  $this->bpjsOrtu ?? null,
        ]);

        // Update riwayat jabatan jika berubah
        if ($this->jabatan !== $this->jabatanAwal && $kategoriJabatan) {
            RiwayatJabatan::where('user_id', $user->id)
                ->where('kategori_jabatan_id', $oldJabatanId)
                ->where('tunjangan', 'jabatan')
                ->whereNull('tanggal_selesai')
                ->update(['tanggal_selesai' => now()]);

            RiwayatJabatan::create([
                'user_id' => $user->id,
                'kategori_jabatan_id' => $kategoriJabatan->id,
                'tunjangan' => $kategoriJabatan->tunjangan,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => null,
            ]);
        }

        // Update riwayat fungsional jika berubah
        if ($this->fungsional !== $this->fungsionalAwal && $kategoriFungsional) {
            RiwayatJabatan::where('user_id', $user->id)
                ->where('kategori_jabatan_id', $oldFungsionalId)
                ->where('tunjangan', 'fungsi')
                ->whereNull('tanggal_selesai')
                ->update(['tanggal_selesai' => now()]);

            RiwayatJabatan::create([
                'user_id' => $user->id,
                'kategori_jabatan_id' => $kategoriFungsional->id,
                'tunjangan' => $kategoriFungsional->tunjangan,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => null,
            ]);
        }

        if (!empty($this->selectedRoles)) {
            $roles = Role::whereIn('id', (array) $this->selectedRoles)->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        return redirect()->route('detailkaryawan.show', $this->user_id)
            ->with('success', 'Karyawan berhasil diupdate.');
    }

    public function savekaryawan()
    {
        if ($this->jabatan !== $this->jabatanAwal) {
            $this->dispatch('confirmJabatanChange'); // Panggil event ke browser
            return; // Hentikan eksekusi agar tidak langsung menyimpan sebelum konfirmasi
        }
        if ($this->fungsional !== $this->fungsionalAwal) {
            $this->dispatch('confirmFungsionalChange'); // Panggil event ke browser
            return; // Hentikan eksekusi agar tidak langsung menyimpan sebelum konfirmasi
        }
        $this->updateKaryawan();
    }
    public function updateKaryawanConfirmed()
    {
        $this->jabatanChanged = false; // Reset perubahan
        $this->jabatanAwal = $this->jabatan; // Update nilai awal agar tidak terdeteksi perubahan lagi
        $this->fungsionalChanged = false; // Reset perubahan
        $this->fungsionalAwal = $this->fungsional; // Update nilai awal agar tidak terdeteksi perubahan lagi

        // Lanjutkan proses update karyawan seperti di updateKaryawan()
        $this->updateKaryawan();
    }

    public function updatedSelectedJenisKaryawan($id)
    {
        $this->jenisKaryawanNama = JenisKaryawan::find($id)?->nama;

        // Rehitung masa kerja berdasarkan jenis karyawan saat ini
        if ($this->tmt) {
            $start = Carbon::parse($this->tmt);
            $now = Carbon::now();

            if (strtolower($this->jenisKaryawanNama) === 'kontrak') {
                $this->masakerja = floor($start->diffInMonths($now)); // dalam bulan
            } else {
                $this->masakerja = floor($start->floatDiffInYears($now)); // dalam tahun
            }
        }
    }

    public function updatedTmt($value)
    {
        if (!$value) return;

        $start = Carbon::parse($value);
        $now = Carbon::now();

        if (strtolower($this->jenisKaryawanNama) === 'kontrak') {
            $this->masakerja = floor($start->diffInMonths($now));
        } else {
            $this->masakerja = floor($start->floatDiffInYears($now));
        }

        // Refresh golongan otomatis
        if ($this->selectedPendidikan) {
            $this->updatedSelectedPendidikan($this->selectedPendidikan);
        }
    }
    public function render()
    {
        return view('livewire.karyawan-form');
    }
}
