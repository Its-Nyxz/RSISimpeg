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

        $timeIn = $absen->time_in ? Carbon::parse($absen->time_in)->timezone('Asia/Jakarta') : null;
        $timeOut = $absen->time_out ? Carbon::parse($absen->time_out)->timezone('Asia/Jakarta') : null;

        if ($timeIn && $timeOut) {
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

            foreach ($absensiSemua as $rowLembur) {
                $lemburIn = Carbon::parse($rowLembur->time_in);
                $lemburOut = Carbon::parse($rowLembur->time_out);

                if ($lemburIn && $lemburOut) {
                    $totalLemburDetik += $lemburIn->diffInSeconds($lemburOut);
                }
            }

            $this->lemburFormatted = gmdate('H:i:s', $totalLemburDetik);
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
