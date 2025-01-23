<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\MasterUmum;
use App\Models\MasterFungsi;
use App\Models\JenisKaryawan;
use App\Models\Kategoripph;
use App\Models\MasterJabatan;
use App\Models\MasterGolongan;
use App\Models\MasterKhusus;
use App\Models\MasterPendidikan;
use Spatie\Permission\Models\Role;

class KaryawanForm extends Component
{
    public $karyawan;
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
    public $jabatan;
    public $jabatans;
    public $fungsi;
    public $fungsis;
    public $umum;
    public $umums;
    public $formasi;
    public $trans;
    public $khusus;
    public $jeniskaryawan;
    public $gol;
    public $golongans;
    public $jk;
    public $tempat;
    public $tanggal_lahir;
    public $alamat;
    public $pendidikans;
    public $no_rek;
    public $kategori;
    public $pphs;
    public $tmt;
    public $masakerja;
    public $roles;
    public $selectedRoles = [];
    public $selectedPendidikan; // ID pendidikan yang dipilih
    public $filteredGolongans = []; // Golongan yang difilter berdasarkan pendidikan
    public $filteredKhusus = [];
    public $selectedGolongan; // ID golongan yang dipilih otomatis
    public string $selectedGolonganNama; // Nama golongan yang ditampilkan di input

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


    public function mount()
    {
        $this->units = UnitKerja::all();
        $this->jeniskaryawan = JenisKaryawan::all();
        $this->pendidikans = MasterPendidikan::with(['minimGolongan', 'maximGolongan'])->get();
        $this->golongans = MasterGolongan::all();
        $this->jabatans = MasterJabatan::all();
        $this->fungsis = MasterFungsi::all();
        $this->umums = MasterUmum::all();
        $this->filteredKhusus = MasterKhusus::where('nama', 'like', '%Tenaga Kesehatan%')->get();
        $this->pphs = Kategoripph::all();
        $this->roles = Role::where('id', '>', '3')->get();
        if ($this->id) {
            $user = User::find($this->id);
            // Ambil roles yang sudah dimiliki user
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        }
    }

    public function save()
    {
        // Validasi input
        $this->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|max:50|unique:users,nip,' . $this->id,
            'email' => 'required|email|max:255|unique:users,email,' . $this->id,
            'no_ktp' => 'required|max:50',
            'no_hp' => 'required|max:15',
            'no_rek' => 'required|max:50',
            'alamat' => 'required|max:500',
            'tempat' => 'required|max:255',
            'tanggal_lahir' => 'required|date',
            'selectedPendidikan' => 'required|exists:master_pendidikan,id',
            'filteredGolongans' => 'required|exists:master_golongan,id',
            'jeniskaryawan' => 'required|exists:jenis_karyawans,id',
            'formasi' => 'required',
            'tmt' => 'required|date',
            'masakerja' => 'required|integer|min:0',
        ]);

        // Mapping formasi ke kolom yang sesuai
        $jabatanId = null;
        $fungsiId = null;
        $umumId = null;

        if (str_starts_with($this->formasi, 'jabatan_')) {
            $jabatanId = str_replace('jabatan_', '', $this->formasi);
        } elseif (str_starts_with($this->formasi, 'fungsi_')) {
            $fungsiId = str_replace('fungsi_', '', $this->formasi);
        } elseif (str_starts_with($this->formasi, 'umum_')) {
            $umumId = str_replace('umum_', '', $this->formasi);
        }

        // Proses simpan dengan updateOrCreate
        $user = User::updateOrCreate(
            ['id' => $this->id], // Kondisi untuk update jika ID ada
            [
                'name' => $this->nama,
                'nip' => $this->nip,
                'email' => $this->email,
                'no_ktp' => $this->no_ktp,
                'no_hp' => $this->no_hp,
                'no_rek' => $this->no_rek,
                'alamat' => $this->alamat,
                'tempat' => $this->tempat,
                'tanggal_lahir' => $this->tanggal_lahir,
                'pendidikan_id' => $this->selectedPendidikan,
                'golongan_id' => $this->selectedGolongan,
                'jenis_id' => $this->jeniskaryawan,
                'jabatan_id' => $jabatanId,
                'fungsi_id' => $fungsiId,
                'umum_id' => $umumId,
                'khusus_id' => $this->khusus,
                'tmt' => $this->tmt,
                'masa_kerja' => $this->masakerja,
            ]
        );


        // Update roles jika user baru dibuat atau diperbarui
        if ($this->selectedRoles) {
            $user->syncRoles($this->selectedRoles);
        }
    }


    public function render()
    {
        return view('livewire.karyawan-form');
    }
}
