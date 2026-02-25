<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Absen;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class AktivitasAbsensiShow extends Component
{
    public $absen;
    public $tanggalFormatted;
    public $jamKerjaFormatted;
    public $realMasukFormatted;
    public $realKeluarFormatted;
    public $isLembur = false;
    public $lemburFormatted = '-';

    public $lemburMasukFormatted;

    public $lemburKeluarFormatted;
    public $keteranganDinas = null;

    public function mount($absen)
    {
        $this->absen = $absen->load([
            'user.kategorijabatan',
            'user.unitKerja',
            'user.pendidikanUser',
        ]);

        $this->tanggalFormatted = $absen->created_at
            ? Carbon::parse($absen->created_at)->locale('id')->timezone('Asia/Jakarta')->translatedFormat('l, d F Y')
            : '-';

        // ✅ Cek apakah ada absen regular (shift kerja) untuk menentukan tampilan jam masuk/keluar
        $absenRegular = Absen::where('user_id', $absen->user_id)
            ->whereDate('created_at', Carbon::parse($absen->created_at)->toDateString())
            ->where(function ($q) {
                $q->where('is_lembur', false)
                    ->orWhereNull('is_lembur');
            })
            ->first();

        $timeIn = $absen->time_in ? Carbon::parse($absen->time_in)->timezone('Asia/Jakarta') : null;
        $timeOut = $absen->time_out ? Carbon::parse($absen->time_out)->timezone('Asia/Jakarta') : null;

        // ✅ Jika hanya ada lembur mandiri (tidak ada shift regular), tampilkan '-' untuk real time
        if ($timeIn && $timeOut && $absenRegular) {
            $diffInSeconds = $timeIn->diffInSeconds($timeOut);
            $this->jamKerjaFormatted = gmdate('H:i:s', $diffInSeconds);

            $this->realMasukFormatted = $timeIn->format('H:i:s');
            $this->realKeluarFormatted = $timeOut->format('H:i:s');
        } else {
            $this->jamKerjaFormatted = '-';
            $this->realMasukFormatted = '-';
            $this->realKeluarFormatted = '-';
        }

        // ✅ Cek apakah ada lembur dari row tambahan
        $absensiSemua = Absen::where('user_id', $absen->user_id)
            ->whereDate('created_at', Carbon::parse($absen->created_at)->toDateString())
            ->where('is_lembur', true)
            ->get();

        if ($absensiSemua->isNotEmpty()) {
            $this->isLembur = true;
            $totalLemburDetik = 0;
            $firstLemburIn = null;
            $lastLemburOut = null;

            foreach ($absensiSemua as $rowLembur) {
                $lemburIn = Carbon::parse($rowLembur->time_in)->timezone('Asia/Jakarta');
                $lemburOut = Carbon::parse($rowLembur->time_out)->timezone('Asia/Jakarta');

                if ($lemburIn && $lemburOut) {
                    $totalLemburDetik += $lemburIn->diffInSeconds($lemburOut);
                    
                    // Ambil jam masuk lembur pertama
                    if ($firstLemburIn === null) {
                        $firstLemburIn = $lemburIn;
                    }
                    
                    // Ambil jam keluar lembur terakhir
                    $lastLemburOut = $lemburOut;
                }
            }

            $this->lemburFormatted = gmdate('H:i:s', $totalLemburDetik);
            $this->lemburMasukFormatted = $firstLemburIn ? $firstLemburIn->format('H:i:s') : '-';
            $this->lemburKeluarFormatted = $lastLemburOut ? $lastLemburOut->format('H:i:s') : '-';
        }

        $dinasLuar = Absen::where('user_id', $absen->user_id)
            ->where('jadwal_id', $absen->jadwal_id)
            ->where('is_dinas', true)
            ->first();

        if ($dinasLuar) {
            $this->keteranganDinas = $dinasLuar->deskripsi_in ?? '-';
        }
        // dd($dinasLuar);
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('pdf.aktivitas-absensi-pdf', [
            'absen' => $this->absen,
            'tanggalFormatted' => $this->tanggalFormatted,
            'jamKerjaFormatted' => $this->jamKerjaFormatted,
            'realMasukFormatted' => $this->realMasukFormatted,
            'realKeluarFormatted' => $this->realKeluarFormatted,
            'isLembur' => $this->isLembur,
            'lemburMasukFormatted' => $this->lemburMasukFormatted,
            'lembutKeluarFormatted' => $this->lembutKeluarFormatted,
            'lemburFormatted' => $this->lemburFormatted,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Detail Absensi ' . $this->absen->user->name . '-' . now()->format('d-m-Y') . '.pdf');
    }

    public function render()
    {
        return view('livewire.aktivitas-absensi-show');
    }
}
