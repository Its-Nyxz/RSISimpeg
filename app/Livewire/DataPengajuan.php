<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TukarJadwal;
use App\Models\CutiKaryawan;
use App\Models\IzinKaryawan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

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
            CutiKaryawan::findOrFail($id)->delete();
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
