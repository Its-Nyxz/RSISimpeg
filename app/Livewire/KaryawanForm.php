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
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
    public $fungsi;
    public $fungsis;
    public $umum;
    public $umums;
    public $formasi;
    public $trans;
    public $khusus;
    public $katjab;
    public $jeniskaryawan;
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
    public $jabatan; // Input untuk jabatan
    public $unit; // Input untuk unit kerja
    public $suggestions = [
        'jabatan' => [],
        'unit' => [],
    ];

    public function fetchSuggestions($field, $value)
    {
        $this->suggestions[$field] = [];

        // if ($value) {
        if ($field === 'jabatan') {
            $categories = KategoriJabatan::where('nama', 'like', "%$value%")
                ->get()
                ->groupBy('tunjangan'); // Mengelompokkan berdasarkan tunjangan

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
            $this->jabatan = $value;
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
            // Ambil golongan dalam rentang minim_gol dan maxim_gol
            $this->filteredGolongans = MasterGolongan::where('id', '>=', $pendidikan->minim_gol)
                ->where('id', '<=', $pendidikan->maxim_gol)
                ->get();

            // Hitung golongan berdasarkan masa kerja
            $baseGolonganId = $pendidikan->minim_gol;
            $maxGolonganId = $pendidikan->maxim_gol;

            if ($this->masakerja !== null) {
                // Hitung kenaikan golongan
                $increment = min(floor($this->masakerja / 4), 4); // Maksimal kenaikan 4 golongan
                $calculatedGolonganId = $baseGolonganId + $increment;

                // Pastikan golongan yang dihitung tidak melebihi batas maksimal golongan pendidikan
                $this->selectedGolongan = $calculatedGolonganId <= $maxGolonganId ? $calculatedGolonganId : $maxGolonganId;

                // Cari nama golongan
                $golongan = MasterGolongan::find($this->selectedGolongan);
                $this->selectedGolonganNama = $golongan ? $golongan->nama : '';
            } else {
                $this->selectedGolongan = $baseGolonganId; // Default ke minimal golongan jika masa kerja tidak tersedia
                $this->selectedGolonganNama = MasterGolongan::find($baseGolonganId)->nama ?? '';
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
            $this->selectedJenisKaryawan = $user->jenis_id;
            $this->tmt = $user->tmt;
            $this->masakerja = $user->masa_kerja;
            $this->selectedGolonganNama = $user->golongan->nama ?? '';
            $this->gol = $user->gol_id;
            $this->khusus = $user->khusus_id;
            $this->selectedPph = $user->kategori_id;
            $this->selectedRoles = $user->roles->pluck('id')->toArray();
            $this->typeShift = $user->type_shift;
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
        // if (!$unit) {
        //     return back()->with('error', 'Unit tidak ditemukan.');
        // } elseif (!$kategoriJabatan) {
        //     return back()->with('error', 'Kategori Jabatan tidak ditemukan.');
        // }

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
            'jenis_id' => $this->selectedJenisKaryawan ?? null,
            'tmt' => $this->tmt ?? null,
            'masa_kerja' => $this->masakerja ?? null,
            'gol_id' => $this->selectedGolongan ?? $this->gol ?? null,
            'khusus_id' => $this->khusus ?? null,
            'kategori_id' => $this->selectedPph ?? null,
            'type_shift' => $this->typeShift ?? null,
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
        // if (!$unit) {
        //     return back()->with('error', 'Unit tidak ditemukan.');
        // } elseif (!$kategoriJabatan) {
        //     return back()->with('error', 'Kategori Jabatan tidak ditemukan.');
        // }

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
            'jenis_id' => $this->selectedJenisKaryawan ?? null,
            'tmt' => $this->tmt ?? null,
            'masa_kerja' => $this->masakerja ?? null,
            'gol_id' => $this->selectedGolongan ?? $this->gol ?? null,
            'khusus_id' => $this->khusus ?? null,
            'kategori_id' => $this->selectedPph ?? null,
            'type_shift' => $this->typeShift ?? null,
        ]);

        if (!empty($this->selectedRoles)) {
            $roles = Role::whereIn('id', (array) $this->selectedRoles)->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        return redirect()->route('detailkaryawan.show', $this->user_id)->with('success', 'Karyawan berhasil diupdate.');
    }


    public function render()
    {
        return view('livewire.karyawan-form');
    }
}
