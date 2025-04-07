<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CutiKaryawan;
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

    public function mount($user)
    {
        // Mendapatkan data user
        $this->user = $user;
        $this->user_id = $user->id;
        $this->pend_awal_id = $user->kategori_pendidikan;
        $this->pend_awal = $user->pendidikanUser->deskripsi ?? null;
        $this->tmt = $user->tmt ? formatDate($user->tmt) : null;
        $this->statusKaryawan = $user->status_karyawan;
        $this->alasanResign = $user->alasan_resign;
        $this->pendidikans = MasterPendidikan::all();
        $this->viewPendAwal = Penyesuaian::with('penyesuaian', 'user')->where('user_id', $this->user_id)->first();
        // $this->roles = DB::table('roles')
        //     ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
        //     ->where('model_has_roles.model_id', $this->user_id)
        //     ->pluck('roles.name')
        //     ->toArray();
        $this->pendidikans = MasterPendidikan::all();
            // return CutiKaryawan::with('user')->where('user_id');
        $this->listCuti = CutiKaryawan::with('user')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get();
        $this->listIzin = IzinKaryawan::with('user')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get();
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
            'tmt.required' => 'Tanggal Mulai Kerja tidak boleh kosong, Silakan isi dulu melalui Edit Karyawan.',
            'pend_awal.required' => 'Pendidikan Awal tidak boleh kosong, Silakan isi dulu melalui Edit Karyawan.',
            'tanggal_penyesuaian.required' => 'Tanggal Penyesuaian tidak boleh kosong',
            'pend_penyesuaian.required' => 'Penyesuaian pendidikan tidak boleh kosong',
        ]);
        if ($this->pend_penyesuaian == $this->pend_awal_id) {
            return redirect()->route('detailkaryawan.show', $this->user_id)->with('error', 'Penyesuaian Pendidikan dan Pendidikan Awal tidak boleh sama.');
        } else {
            $cekMasterPenyesuaian = MasterPenyesuaian::where('pendidikan_awal', $this->pend_awal_id)->where('pendidikan_penyesuaian', $this->pend_penyesuaian)->where('user_id', $this->user_id)->exists();
            if (!$cekMasterPenyesuaian) {
                $existingPenyesuaian = Penyesuaian::where('user_id', $this->user_id)->where('status_penyesuaian', 1)->first();
                // Jika ada, ubah status_penyesuaian menjadi non aktif
                if ($existingPenyesuaian) {
                    $existingPenyesuaian->update(['status_penyesuaian' => 0]);
                }
                $masterPenyesuaian = MasterPenyesuaian::create([
                    'user_id' => $this->user_id,
                    'pendidikan_awal' => $this->pend_awal_id,
                    'pendidikan_penyesuaian' => $this->pend_penyesuaian,
                    'masa_kerja' => 'coming soon',
                ]);
                Penyesuaian::create([
                    'user_id' => $this->user_id,
                    'penyesuaian_id' => $masterPenyesuaian->id, //last id master
                    'tanggal_penyesuaian' => $this->tanggal_penyesuaian,
                    'status_penyesuaian' => 1
                ]);
                User::findOrFail($this->user_id)->update([
                    'kategori_pendidikan' => $this->pend_penyesuaian
                ]);
                return redirect()->route('detailkaryawan.show', $this->user_id)->with('success', 'History berhasil di tambahkan.');
            } else {
                return redirect()->route('detailkaryawan.show', $this->user_id)->with('error', 'History sudah ada sebelumnya.');
            }
        }
    }

    public function render()
    {
        // $users = $this->mont();
        return view('livewire.detail-karyawan');
    }
}
