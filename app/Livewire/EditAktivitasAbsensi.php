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
        $absen = Absen::with('user')->findOrFail($id);

        // Set nilai ke properti komponen
        $this->absen_id = $absen->id;
        $this->user_id = $absen->user_id;
        $this->user_name = $absen->user ? $absen->user->name : 'User Tidak Ditemukan';
        $this->time_in = $absen->time_in ? Carbon::parse($absen->time_in)->format('H:i') : '-';
        $this->time_out = $absen->time_out ? Carbon::parse($absen->time_out)->format('H:i') : '-';
        $this->deskripsi_in = $absen->deskripsi_in ?? '-';
        $this->deskripsi_out = $absen->deskripsi_out ?? '-';
        $this->keterangan = $absen->keterangan ?? '-';
        $this->tanggal = Carbon::parse($absen->created_at)->format('Y-m-d');
        $this->feedback = $absen->feedback ?? '';
    }

    public function updateFeedback()
    {       
        $this->validate([
            'feedback' => 'nullable|string|max:255',
        ]);
    
        Absen::where('id', $this->absen_id)->update([
            'feedback' => $this->feedback,
        ]);
    
        session()->flash('success', 'Feedback berhasil ditambahkan!');
        return redirect()->route('aktivitasabsensi.index');
    }
    
    public function render()
    {
        return view('livewire.edit-aktivitas-absensi');
    }
}