<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TukarJadwal;
use App\Models\CutiKaryawan;
use App\Models\IzinKaryawan;
use App\Models\SisaCutiTahunan;
use App\Models\MasterJatahCuti;
use App\Models\Shift;
use App\Models\JadwalAbsensi;
use App\Models\Absen;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataPengajuan extends Component
{
    use WithPagination;

    public $tipe; // Menerima tipe dari blade
    public $judul;

    // Lifecycle hook untuk menerima parameter dari blade
    public function mount($tipe)
    {
        $this->tipe = $tipe;
    }

    public function delete($id, $tipe)
    {
        if ($tipe === 'cuti') {
            DB::transaction(function () use ($id) {
                $cuti = CutiKaryawan::with('jenisCuti')
                    ->where('user_id', Auth::id())
                    ->findOrFail($id);

                // Saldo hanya dikembalikan jika cuti sudah disetujui final.
                if (
                    $cuti->status_cuti_id == 1 &&
                    strtolower($cuti->jenisCuti->nama_cuti ?? '') === 'cuti tahunan'
                ) {
                    // Mengikuti tahun pelaksanaan cuti
                    $tahun = Carbon::parse($cuti->tanggal_mulai)->year;

                    if ($cuti->kategori_cuti === 'tambahan') {
                        $sisaCuti = SisaCutiTahunan::where('user_id', $cuti->user_id)
                            ->where('tahun', $tahun)
                            ->lockForUpdate()
                            ->first();

                        if ($sisaCuti) {
                            $sisaCuti->increment('cuti_tambahan', $cuti->jumlah_hari);
                        }
                    } else {
                        // Cuti Tahunan: Cek apakah ada kuota yang dikasbon dari tahun depan
                        $tahunDepan = $tahun + 1;
                        $sisaDepan = SisaCutiTahunan::where('user_id', $cuti->user_id)
                            ->where('tahun', $tahunDepan)
                            ->lockForUpdate()
                            ->first();

                        $jatahDepan = MasterJatahCuti::where('tahun', $tahunDepan)->value('jumlah_cuti') ?? 12;
                        $jumlahBalik = $cuti->jumlah_hari;

                        // Jika saldo tahun depan berkurang karena kasbon, pulihkan tahun depan terlebih dahulu
                        if ($sisaDepan && $sisaDepan->sisa_cuti < $jatahDepan) {
                            $defisit = $jatahDepan - $sisaDepan->sisa_cuti;
                            $kembaliDepan = min($jumlahBalik, $defisit);
                            $sisaDepan->increment('sisa_cuti', $kembaliDepan);
                            $jumlahBalik -= $kembaliDepan;
                        }

                        // Sisanya dikembalikan ke saldo tahun cuti tersebut
                        if ($jumlahBalik > 0) {
                            $sisaCuti = SisaCutiTahunan::where('user_id', $cuti->user_id)
                                ->where('tahun', $tahun)
                                ->lockForUpdate()
                                ->first();

                            if ($sisaCuti) {
                                $sisaCuti->increment('sisa_cuti', $jumlahBalik);
                            }
                        }
                    }
                }

                // Jika cuti yang pernah disetujui (status_cuti_id == 1) dibatalkan/dihapus:
                if ($cuti->status_cuti_id == 1) {
                    $shiftCutiIds = Shift::where('nama_shift', 'C')->pluck('id');

                    // Ambil jadwal absensi di rentang tanggal cuti yang shift-nya C
                    $jadwalCutis = JadwalAbsensi::where('user_id', $cuti->user_id)
                        ->whereBetween('tanggal_jadwal', [$cuti->tanggal_mulai, $cuti->tanggal_selesai])
                        ->whereIn('shift_id', $shiftCutiIds)
                        ->get();

                    $userUnitId = $cuti->user->unit_id ?? Auth::user()->unit_id;
                    $shiftLibur = Shift::firstOrCreate(
                        [
                            'unit_id' => $userUnitId,
                            'nama_shift' => 'L',
                            'jam_masuk' => null,
                            'jam_keluar' => null,
                        ],
                        ['keterangan' => 'Libur']
                    );

                    foreach ($jadwalCutis as $j) {
                        // Hapus presensi otomatis yang dibuat saat cuti disetujui
                        Absen::where('jadwal_id', $j->id)
                            ->where('user_id', $cuti->user_id)
                            ->delete();

                        // Kembalikan shift ke Libur (L) agar status C langsung hilang
                        $j->update(['shift_id' => $shiftLibur->id]);
                    }
                }

                $cuti->delete();
            });

            return redirect()->route('pengajuan.index', 'cuti')
                ->with('success', 'Pengajuan Cuti berhasil dihapus.');
        }

        if ($tipe === 'ijin') {
            IzinKaryawan::findOrFail($id)->delete();

            return redirect()->route('pengajuan.index', 'ijin')
                ->with('success', 'Pengajuan Izin berhasil dihapus.');
        }

        if ($tipe === 'tukar_jadwal') {
            TukarJadwal::findOrFail($id)->delete();

            return redirect()->route('pengajuan.index', 'tukar_jadwal')
                ->with('success', 'Pengajuan Tukar Jadwal berhasil dihapus.');
        }

        // Tipe tidak valid
        abort(404);
    }

    public function render()
    {
        $userId = Auth::id();

        switch ($this->tipe) {
            case 'cuti':
                $this->judul = "List Pengajuan Cuti";
                $dataPengajuan = CutiKaryawan::with('jenisCuti', 'statusCuti')->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;

            case 'ijin':
                $this->judul = "List Pengajuan Izin";
                $dataPengajuan = IzinKaryawan::with('jenisIzin')->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;

            case 'tukar_jadwal':
                $this->judul = "List Pengajuan Tukar Jadwal";
                $dataPengajuan = TukarJadwal::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;

            default:
                abort(404); // Tipe tidak valid, kembalikan 404
        }

        return view('livewire.data-pengajuan', compact('dataPengajuan'));
    }
}
