<?php

namespace App\Livewire;



use Carbon\Carbon;
use App\Models\User;
use App\Models\Gapok;
use Livewire\Component;
use App\Models\MasterGapok;
use App\Models\Penyesuaian;
use Illuminate\Support\Str;
use App\Models\CutiKaryawan;
use App\Models\IzinKaryawan;

use Livewire\WithFileUploads;
use App\Models\RiwayatJabatan;
use App\Models\MasterPendidikan;
use App\Models\MasterPenyesuaian;
use App\Models\PeringatanKaryawan;
use App\Models\RiwayatApproval;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;


class DetailKaryawan extends Component
{
    use WithFileUploads;

    public $tingkat, $jenis_pelanggaran, $tanggal_sp, $file_sp, $keterangan;

    public $user;
    public $user_id;
    public $alasanResign;
    public $statusKaryawan;
    public $pend_awal, $pend_penyesuaian, $tanggal_penyesuaian, $tmt, $tmt_masuk;
    public $pendidikans;
    public $pend_awal_id;
    public $roles;
    public $viewPendAwal;
    public $canSeeRiwayat;

    public $listCuti;
    public $listIzin;
    public $listPenyesuaian;
    public $listGapok;
    public $listSP;
    public $listRiwayat;
    public $listRiwayatApproval;
    public $gapokSebelumnya;
    public $gapokPenyesuaian;
    public $phkDari;


    public function mount($user)
    {
        // Mendapatkan data user
        $this->user = $user->load(['sisaCutiTahunan', 'pendidikanUser']);
        $this->user_id = $user->id;
        $this->pend_awal_id = $user->kategori_pendidikan;
        $this->pend_awal = $user->pendidikanUser->deskripsi ?? null;
        $this->tmt = $user->tmt ? formatDate($user->tmt) : null;
        $this->tmt_masuk = $user->tmt_masuk ? formatDate($user->tmt_masuk) : null;
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
        $this->listPenyesuaian = Penyesuaian::with('user')->where('user_id', $this->user_id)->where('status_penyesuaian', '<', 2)->orderBy('created_at', 'desc')->get();
        $this->listGapok = Gapok::with('user')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get();
        $this->listSP = PeringatanKaryawan::where('user_id', $this->user_id)
            ->orderBy('tanggal_sp', 'desc')
            ->get();
        $this->listRiwayat = RiwayatJabatan::where('user_id', $this->user_id)
            ->whereNotNull('tanggal_selesai') // hanya yang sudah selesai
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Cek Role untuk melihat Riwayat Jabatan
        $this->canSeeRiwayat = auth()->user()->hasRole('Super Admin')
            || auth()->user()->roles()->where('name', 'LIKE', '%Kepala%')->exists()
            || auth()->user()->unitKerja->nama === 'KEPEGAWAIAN';

        // Query riwayat approval hanya jika user memiliki akses dan hanya untuk user yang sedang dilihat
        if ($this->canSeeRiwayat) {
            $this->listRiwayatApproval = RiwayatApproval::with(['cuti.user', 'cuti.jeniscuti'])
                ->where('approver_id', $this->user_id) // Filter by current user being viewed
                ->orderBy('approve_at', 'desc')
                ->get();
        }

        // dd($this->listRiwayat);

        if ($this->viewPendAwal) {
            $masaKerjaAwal = $this->viewPendAwal->masa_kerja_awal ?? 0;
            $masaKerjaAkhir = $this->viewPendAwal->masa_kerja_akhir ?? 0;

            $golAwal = $this->viewPendAwal->gol_id_awal;
            $golAkhir = $this->viewPendAwal->gol_id_akhir;

            $this->gapokSebelumnya = MasterGapok::where('gol_id', $golAwal)
                ->where('masa_kerja', '<=', $masaKerjaAwal)
                ->orderByDesc('masa_kerja')
                ->first();

            $this->gapokPenyesuaian = MasterGapok::where('gol_id', $golAkhir)
                ->where('masa_kerja', '<=', $masaKerjaAkhir)
                ->orderByDesc('masa_kerja')
                ->first();
        }

        if (
            auth()->user()->hasRole(['Super Admin', 'Kepala Unit', 'Kepegawaian']) ||
            auth()->user()->unitKerja->nama == 'KEPEGAWAIAN'
        ) {

            $this->listRiwayatApproval = RiwayatApproval::with(['cuti.user', 'approver'])
                ->where('approver_id', auth()->id())
                ->orderBy('approve_at', 'desc')
                ->get();
        }
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

        // Cek apakah user sudah pernah melakukan penyesuaian ke pendidikan yang sama (hanya yang aktif/belum dibatalkan)
        $sudahAda = Penyesuaian::where('user_id', $this->user_id)
            ->where('status_penyesuaian', '<', 2) // hanya yang aktif/belum dibatalkan
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
            'tgl_penyesuaian' => $this->tanggal_penyesuaian,
            'masa_kerja' => $masaKerjaAkhir,
            'gol_id' => $golBaru,
        ]);

        return redirect()->route('detailkaryawan.show', $this->user_id)
            ->with('success', 'History berhasil ditambahkan.');
    }

    public function tambahSP($confirmed_phk = false)
    {
        $this->validate([
            'tingkat' => 'required|in:I,II,III,IV',
            'jenis_pelanggaran' => 'required|string|max:255',
            'tanggal_sp' => 'required|date',
            'file_sp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($this->user_id);
        $path = null;

        if ($this->file_sp) {
            $user = User::find($this->user_id);
            $namaUser = Str::slug($user->name);
            $tanggalFormatted = Carbon::parse($this->tanggal_sp)->format('Ymd');
            $extension = $this->file_sp->getClientOriginalExtension();

            $filename = "sp_{$namaUser}_{$this->tingkat}_{$tanggalFormatted}." . $extension;

            // Simpan di folder public/dokumen/sp_uploads/
            $path = $this->file_sp->storeAs('dokumen/sp_uploads', $filename, 'public');
        } else {
            $path = null;
        }

        // Map tingkat ke sanksi
        $sanksi = match ($this->tingkat) {
            'II' => 1, // SP1
            'III' => 2, // SP2
            default => 0,
        };

        $isPhk = $this->tingkat === 'IV';

        // Hitung jumlah SP
        $sp1Count = PeringatanKaryawan::where('user_id', $user->id)->where('sanksi', 1)->count();
        $sp2Count = PeringatanKaryawan::where('user_id', $user->id)->where('sanksi', 2)->count();

        // === PHK otomatis jika melewati batas SP
        $willBePhk = false;

        if ($sanksi === 1 && $sp1Count >= 4) {
            $willBePhk = true;
            $this->phkDari = 'SP1';
        } elseif ($sanksi === 2 && $sp2Count >= 1) {
            $willBePhk = true;
            $this->phkDari = 'SP2';
        }

        // Tampilkan konfirmasi sebelum PHK otomatis
        if ($willBePhk && !$confirmed_phk) {
            $this->dispatch('konfirmasi-phk');
            return;
        }

        PeringatanKaryawan::create([
            'user_id' => $this->user_id,
            'tingkat' => $this->tingkat,
            'jenis_pelanggaran' => $this->jenis_pelanggaran,
            'tanggal_sp' => $this->tanggal_sp,
            'file_sp' => $path,
            'keterangan' => $this->keterangan,
            'sanksi' => $sanksi,
            'is_phk' => $isPhk,
        ]);

        // === Lakukan PHK bila perlu
        if ($isPhk || $willBePhk) {
            $alasanResign = match (true) {
                $isPhk => 'Diberhentikan melalui SP Tingkat IV',
                $sanksi === 1 => 'Diberhentikan otomatis karena menerima SP Tingkat II (SP1) sebanyak 5 kali',
                $sanksi === 2 => 'Diberhentikan otomatis karena menerima SP Tingkat III (SP2) sebanyak 2 kali',
            };

            $user->update([
                'status_karyawan' => 0,
                'alasan_resign' => $alasanResign,
            ]);

            $notifMsg = match (true) {
                $isPhk => 'Anda telah diberhentikan dari status karyawan karena pelanggaran berat. SP Tingkat IV telah diterbitkan.',
                $sanksi === 1 => 'Anda telah diberhentikan karena telah menerima SP Tingkat II (SP1) sebanyak 5 kali.',
                $sanksi === 2 => 'Anda telah diberhentikan karena telah menerima SP Tingkat III (SP2) sebanyak 2 kali.',
                default => 'Anda telah diberhentikan karena pelanggaran berat.',
            };

            Notification::send($user, new UserNotification($notifMsg, '/peringatan'));
        } else {
            // === Kirim notifikasi SP biasa (jika tidak PHK)
            $admin = auth()->user();
            $message = 'Anda telah diberikan Surat Peringatan Tingkat <strong>' . $this->tingkat . '</strong> oleh <strong>' . $admin->name . '</strong>.' .
                '<br><span class="text-red-600 font-semibold">Jenis Pelanggaran:</span> ' . $this->jenis_pelanggaran .
                ($this->keterangan ? '<br><em>Catatan:</em> ' . $this->keterangan : '');

            Notification::send($user, new UserNotification($message, '/peringatan'));
        }

        // === Flash message ke UI
        $messageSuccess = match (true) {
            $isPhk => 'Karyawan berhasil diberhentikan melalui SP Tingkat IV.',
            $sanksi === 1 && $sp1Count >= 4 => 'Karyawan diberhentikan otomatis karena SP Tingkat II (SP1) ke-5.',
            $sanksi === 2 && $sp2Count >= 1 => 'Karyawan diberhentikan otomatis karena SP Tingkat III (SP2) ke-2.',
            default => 'Surat Peringatan berhasil ditambahkan.',
        };

        return redirect()->route('detailkaryawan.show', $user->id)
            ->with('success', $messageSuccess);
    }

    public function batalPenyesuaian($id)
    {
        $penyesuaian = Penyesuaian::findOrFail($id);

        // Ambil penyesuaian aktif terakhir
        $latest = Penyesuaian::where('user_id', $penyesuaian->user_id)
            ->where('status_penyesuaian', 1)
            ->latest('created_at')
            ->first();

        // Ambil user saat ini
        $user = $penyesuaian->user;
        // Cek apakah yang diminta untuk dibatalkan adalah penyesuaian aktif terakhir
        if (!$latest || $latest->id !== $penyesuaian->id) {
            return redirect()->route('detailkaryawan.show', $user->id)
                ->with('error', 'Hanya penyesuaian terakhir yang aktif yang dapat dibatalkan.');
        }


        // Hitung selisih yang pernah dikurangi
        $selisih = $penyesuaian->masa_kerja_awal - $penyesuaian->masa_kerja_akhir;


        // Hitung masa kerja baru (tambah kembali selisih yang pernah dikurangi)
        $masaKerjaBaru = $user->masa_kerja + $selisih;

        // Ambil data pendidikan awal (bukan penyesuaian)
        $pendidikanAwalId = $penyesuaian->penyesuaian->pendidikan_awal;
        $pendidikanAwal = MasterPendidikan::find($pendidikanAwalId);

        // Hitung ulang golongan berdasarkan pendidikan awal
        $naikGol = floor($masaKerjaBaru / 4); // 1 kenaikan per 4 tahun
        $naikGol = min($naikGol, 4); // maksimal 4 kali kenaikan

        $golDihitung = $pendidikanAwal->minim_gol + $naikGol;
        $golBaru = min($golDihitung, $pendidikanAwal->maxim_gol); // tidak boleh lebih dari maksimal gol

        // Update  penyesuaian yg di hapus jadi dibatalkan
        $penyesuaian->update([
            'status_penyesuaian' => 2,
        ]);

        // update penyesuai an sebelumnya menjadi aktif
        $penyesuaianLama = Penyesuaian::where('user_id', $this->user_id)
            ->where('status_penyesuaian', 0)
            ->latest('created_at')
            ->first();
        // jadikan aktif
        $penyesuaianLama?->update(['status_penyesuaian' => 1]);

        
        // Update data user (kategori_pendidikan dan masa_kerja + selisih)
        $user->update([
            'kategori_pendidikan' => $penyesuaian->penyesuaian->pendidikan_awal,
            'masa_kerja' => $masaKerjaBaru,
            'gol_id' => $golBaru,
        ]);

        return redirect()->route('detailkaryawan.show', $user->id)
            ->with('success', 'Pendidikan Penyesuaian Berhasil Dibatalkan.');
    }

    public function render()
    {
        // $users = $this->mont();
        return view('livewire.detail-karyawan');
    }
}
