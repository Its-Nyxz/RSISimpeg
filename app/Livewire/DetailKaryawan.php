<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CutiKaryawan;
use App\Models\Gapok;
use App\Models\IzinKaryawan;
use Livewire\Component;
use App\Models\Penyesuaian;
use App\Models\MasterPendidikan;
use App\Models\MasterPenyesuaian;

use Illuminate\Support\Facades\DB;


class DetailKaryawan extends Component
{
    public $user;
    public $user_id;
    public $alasanResign;
    public $statusKaryawan;
    public $pend_awal, $pend_penyesuaian, $tanggal_penyesuaian, $tmt;
    public $pendidikans;
    public $pend_awal_id;
    public $roles;
    public $viewPendAwal;
    public $listCuti;
    public $listIzin;
    public $listPenyesuaian;
    public $listGapok;

    public function mount($user)
    {
        // Mendapatkan data user
        $this->user = $user->load(['sisaCutiTahunan', 'pendidikanUser']);
        $this->user_id = $user->id;
        $this->pend_awal_id = $user->kategori_pendidikan;
        $this->pend_awal = $user->pendidikanUser->deskripsi ?? null;
        $this->tmt = $user->tmt ? formatDate($user->tmt) : null;
        $this->statusKaryawan = $user->status_karyawan;
        $this->alasanResign = $user->alasan_resign;
        $this->pendidikans = MasterPendidikan::all();
        $this->viewPendAwal = Penyesuaian::with('penyesuaian', 'user')
            ->where('user_id', $this->user_id)
            ->where('status_penyesuaian', 1) // tampilkan yang aktif
            ->first();
        // $this->roles = DB::table('roles')
        //     ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
        //     ->where('model_has_roles.model_id', $this->user_id)
        //     ->pluck('roles.name')
        //     ->toArray();
        $this->pendidikans = MasterPendidikan::all();
        // return CutiKaryawan::with('user')->where('user_id');
        $this->listCuti = CutiKaryawan::with('user')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get();
        $this->listIzin = IzinKaryawan::with('user')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get();
        $this->listPenyesuaian = Penyesuaian::with('user')->where('user_id', $this->user_id)->where('status_penyesuaian', 0)->orderBy('created_at', 'desc')->get();
        $this->listGapok = Gapok::with('user')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get();
        // dd($this->viewPendAwal);
    }

    public function resignKerja()
    {
        $this->validate(['alasanResign' => 'required|string|max:255']);
        $user = User::findOrFail($this->user_id);
        $user->update([
            'status_karyawan' => 0,
            'alasan_resign' => $this->alasanResign
        ]);
        return redirect()->route('detailkaryawan.show', $this->user_id)->with('success', 'Karyawan berhasil dinonaktifkan.');
    }

    public function kembaliKerja()
    {
        $user = User::findOrFail($this->user_id);
        $user->update([
            'status_karyawan' => 1,
            'alasan_resign' => $this->alasanResign
        ]);
        return redirect()->route('detailkaryawan.show', $this->user_id)->with('success', 'Karyawan berhasil diaktifkan.');
    }

    public function tambahHistory()
    {
        $this->validate([
            'tmt' => 'required',
            'pend_awal' => 'required',
            'pend_penyesuaian' => 'required',
            'tanggal_penyesuaian' => 'required',
        ], [
            'tmt.required' => 'Tanggal Mulai Kerja tidak boleh kosong, silakan isi dulu melalui Edit Karyawan.',
            'pend_awal.required' => 'Pendidikan Awal tidak boleh kosong.',
            'pend_penyesuaian.required' => 'Pendidikan Penyesuaian tidak boleh kosong.',
            'tanggal_penyesuaian.required' => 'Tanggal Penyesuaian tidak boleh kosong.',
        ]);

        if ($this->pend_penyesuaian == $this->pend_awal_id) {
            return redirect()->route('detailkaryawan.show', $this->user_id)
                ->with('error', 'Penyesuaian Pendidikan dan Pendidikan Awal tidak boleh sama.');
        }

        // Cek apakah user sudah pernah melakukan penyesuaian ke pendidikan yang sama
        $sudahAda = Penyesuaian::where('user_id', $this->user_id)
            ->whereHas('penyesuaian', function ($query) {
                $query->where('pendidikan_awal', $this->pend_awal_id)
                    ->where('pendidikan_penyesuaian', $this->pend_penyesuaian);
            })
            ->exists();

        if ($sudahAda) {
            return redirect()->route('detailkaryawan.show', $this->user_id)
                ->with('error', 'History sudah pernah ditambahkan sebelumnya.');
        }

        $master = MasterPenyesuaian::where('pendidikan_awal', $this->pend_awal_id)
            ->where('pendidikan_penyesuaian', $this->pend_penyesuaian)
            ->first();

        if (!$master) {
            return redirect()->route('detailkaryawan.show', $this->user_id)
                ->with('error', 'Data master penyesuaian tidak ditemukan. Silakan hubungi admin untuk menambahkan terlebih dahulu.');
        }

        // Ambil data user
        $user = User::findOrFail($this->user_id);
        $masaKerjaAwal = $user->masa_kerja;
        $golAwal = $user->gol_id;

        // Ambil masa kerja yang dikurangi dari master (hanya angka)
        $pengurangan = (int) filter_var($master->masa_kerja, FILTER_SANITIZE_NUMBER_INT);
        $masaKerjaAkhir = max(0, $masaKerjaAwal - $pengurangan);

        $pendidikanBaru = MasterPendidikan::find($this->pend_penyesuaian);

        $golBaru = $golAwal; // default
        if ($pendidikanBaru) {
            $naikGol = floor($masaKerjaAkhir / 4);     // 1 kenaikan per 4 tahun
            $naikGol = min($naikGol, 4);               // maksimal 4 kali

            $golDihitung = $pendidikanBaru->minim_gol + $naikGol;

            $golBaru = min($golDihitung, $pendidikanBaru->maxim_gol); // batasi maksimal gol
        }

        // Nonaktifkan penyesuaian lama
        Penyesuaian::where('user_id', $this->user_id)
            ->where('status_penyesuaian', 1)
            ->update(['status_penyesuaian' => 0]);

        // Simpan penyesuaian baru
        Penyesuaian::create([
            'user_id' => $this->user_id,
            'penyesuaian_id' => $master->id,
            'tanggal_penyesuaian' => $this->tanggal_penyesuaian,
            'status_penyesuaian' => 1,
            'gol_id_awal' => $golAwal,
            'gol_id_akhir' => $golBaru,
            'masa_kerja_awal' => $masaKerjaAwal,
            'masa_kerja_akhir' => $masaKerjaAkhir,
        ]);

        // Update data user
        $user->update([
            'kategori_pendidikan' => $this->pend_penyesuaian,
            'masa_kerja' => $masaKerjaAkhir,
            'gol_id' => $golBaru,
        ]);

        return redirect()->route('detailkaryawan.show', $this->user_id)
            ->with('success', 'History berhasil ditambahkan.');
    }

    public function render()
    {
        // $users = $this->mont();
        return view('livewire.detail-karyawan');
    }
}
