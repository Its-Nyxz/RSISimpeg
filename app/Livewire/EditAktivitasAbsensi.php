<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Absen;
use Carbon\Carbon;

class EditAktivitasAbsensi extends Component
{
    public $user_id, $user_name, $absen_id, $time_in, $time_out, $deskripsi_in, $deskripsi_out, $keterangan, $tanggal, $feedback;

    public function mount($id)
    {
        // Ambil data absen sekaligus relasi user
        $absen = Absen::with('user', 'jadwalAbsen')->findOrFail($id);
        
        // Set nilai ke properti komponen
        $this->absen_id = $absen->id;
        $this->user_id = $absen->user_id;
        $this->user_name = $absen->user ? $absen->user->name : 'User Tidak Ditemukan';
        $this->time_in = $absen->time_in
            ? Carbon::parse($absen->time_in)->setTimezone('Asia/Jakarta')->format('H:i:s')
            : '-';
        $this->time_out = $absen->time_out
            ? Carbon::parse($absen->time_out)->setTimezone('Asia/Jakarta')->format('H:i:s')
            : '-';
        $this->deskripsi_in = $absen->deskripsi_in ?? '-';
        $this->deskripsi_out = $absen->deskripsi_out ?? '-';
        $this->keterangan = $absen->keterangan ?? '-';
        $this->tanggal = Carbon::parse($absen->jadwalAbsen->tanggal_jadwal)->format('Y-m-d');
        $this->feedback = $absen->feedback ?? '';
    }

    public function updateFeedback()
    {
        $this->validate([
            'feedback' => 'nullable|string|max:255',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
        ]);
    
        // Konversi waktu ke format H:i:s sebelum menyimpan
        $timeIn = $this->time_in ? Carbon::createFromFormat('H:i', $this->time_in, 'Asia/Jakarta')->format('H:i:s') : null;
        $timeOut = $this->time_out ? Carbon::createFromFormat('H:i', $this->time_out, 'Asia/Jakarta')->format('H:i:s') : null;
    
        Absen::where('id', $this->absen_id)->update([
            'feedback' => $this->feedback,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
        ]);
    
        session()->flash('success', 'Data absensi berhasil diperbarui!');
        return redirect()->route('aktivitasabsensi.index');
    }
    
    

    public function setApproval($status)
    {
        Absen::where('id', $this->absen_id)->update([
            'is_lembur' => $status,
        ]);

        session()->flash('approval_message', $status ? 'Lembur disetujui!' : 'Lembur tidak disetujui.');
    }

    public function render()
    {
        return view('livewire.edit-aktivitas-absensi');
    }
}