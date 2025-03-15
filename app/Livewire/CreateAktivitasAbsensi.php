<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Absen;
use App\Models\JadwalAbsensi;
use Carbon\Carbon;

class CreateAktivitasAbsensi extends Component
{
    public $user_id, $user_name, $jadwal_id, $tanggal, $time_in, $time_out, $deskripsi_in, $deskripsi_out, $deskripsi_lembur, $keterangan;
    public $suggestions = [];

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
    }

    public function fetchSuggestions($type, $query)
    {
        if ($type === 'user') {
            $this->suggestions = User::where('name', 'like', "%$query%")
                ->select('id', 'name')
                ->get()
                ->toArray();
        }
    }

    public function selectUser($id, $name)
    {
        $this->user_id = $id;
        $this->user_name = $name;
        $this->suggestions = [];

        // Pastikan user memiliki jadwal
        $jadwal = JadwalAbsensi::where('user_id', $this->user_id)
            ->latest()
            ->first();

        $this->jadwal_id = $jadwal ? $jadwal->id : null;
    }

    public function store()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:jadwal_absensis,id',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'deskripsi_in' => 'nullable|string',
            'deskripsi_out' => 'nullable|string',
            'deskripsi_lembur' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Pastikan jadwal sesuai dengan user yang dipilih
        $jadwal = JadwalAbsensi::where('id', $this->jadwal_id)
            ->where('user_id', $this->user_id)
            ->first();

        if (!$jadwal) {
            session()->flash('error', 'Jadwal tidak sesuai dengan user yang dipilih.');
            return;
        }

        Absen::updateOrCreate(
            [
                'jadwal_id' => $jadwal->id,
                'user_id' => $this->user_id,
            ],
            [
                'time_in' => $this->time_in ? Carbon::parse($this->time_in)->format('Y-m-d H:i:s') : null,
                'time_out' => $this->time_out ? Carbon::parse($this->time_out)->format('Y-m-d H:i:s') : null,
                'deskripsi_in' => $this->deskripsi_in,
                'deskripsi_out' => $this->deskripsi_out,
                'deskripsi_lembur' => $this->deskripsi_lembur,
                'keterangan' => $this->keterangan,
            ]
        );

        return redirect()->route('aktivitasabsensi.index')->with('success', 'Data absen berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.create-aktivitas-absensi');
    }
}