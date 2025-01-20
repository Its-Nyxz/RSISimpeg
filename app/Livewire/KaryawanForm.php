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

    public function updatedSelectedPendidikan($pendidikanId)
    {
        $pendidikan = MasterPendidikan::with(['minimGolongan', 'maximGolongan'])->find($pendidikanId);

        if ($pendidikan) {
            // Ambil golongan dalam rentang minim_gol dan maxim_gol
            $this->filteredGolongans = MasterGolongan::where('id', '>=', $pendidikan->minim_gol)
                ->where('id', '<=', $pendidikan->maxim_gol)
                ->get();
        } else {
            $this->filteredGolongans = [];
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
        $this->khusus = MasterKhusus::all();
        $this->pphs = Kategoripph::all();
        $this->roles = Role::where('id', '>', '3')->get();
        if ($this->id) {
            $user = User::find($this->id);
            // Ambil roles yang sudah dimiliki user
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        }
    }

    public function render()
    {
        return view('livewire.karyawan-form');
    }
}
