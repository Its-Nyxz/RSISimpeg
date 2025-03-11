<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Shift;
// use App\Models\OpsiAbsen;
use App\Models\JadwalAbsensi;

class CreateJadwal extends Component
{
    public $nama;          // Menyimpan nama user yang dipilih (hanya untuk tampilan)
    public $user_id;       // Menyimpan ID user untuk database
    public $shift_nama;    // Menyimpan nama shift yang dipilih (hanya untuk tampilan)
    public $shift_id;      // Menyimpan ID shift untuk database
    // public $opsi_nama;     // Menyimpan nama opsi absensi (hanya untuk tampilan)
    // public $opsi_id;       // Menyimpan ID opsi absensi untuk database
    public $tanggal;       // Tanggal jadwal
    // public $keterangan;    // Keterangan absensi

    public $users = [];    // Daftar user
    public $shifts = [];   // Daftar shift
    // public $opsis = [];    // Daftar opsi absensi

    // Aturan validasi
    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'shift_id' => 'required|exists:shifts,id',
        // 'opsi_id' => 'required|exists:opsi_absens,id',
        'tanggal' => 'required|date',
        // 'keterangan' => 'nullable|string',
    ];

    public function fetchSuggestions($field, $query)
    {
        $userLogin = auth()->user(); // User yang sedang login
    
        if ($field === 'user') {
            $this->users = User::where('name', 'like', "%$query%")
                ->when(
                    !$userLogin->hasRole('Super Admin'),
                    function ($query) use ($userLogin) {
                        $query->whereHas('unitKerja', function ($q) use ($userLogin) {
                            $q->where('id', $userLogin->unitKerja->id);
                        });
                    }
                )
                ->get();
        } elseif ($field === 'shift') {
            $this->shifts = Shift::where('nama_shift', 'like', "%$query%")->get();
        } // } elseif ($field === 'opsi') {
        //     $this->opsis = OpsiAbsen::where('name', 'like', "%$query%")->get();
        // }
    }
    
    
    

    // public function mount()
    // {
    //     $this->users = User::all();
    //     $this->shifts = Shift::all();
    //     $this->opsis = OpsiAbsen::all();
    // }

    public function store()
    {
        $this->validate();

        JadwalAbsensi::create([
            'user_id' => $this->user_id,
            'shift_id' => $this->shift_id,
            // 'opsi_id' => $this->opsi_id,
            'tanggal_jadwal' => $this->tanggal,
            // 'keterangan_absen' => $this->keterangan,
        ]);

        session()->flash('success', 'Jadwal Absensi berhasil ditambahkan!');
        return redirect()->route('jadwal.index');
    }
    // Method untuk memilih user
    public function selectUser($id, $name)
    {
        $this->user_id = $id;
        $this->nama = $name;
        $this->users = [];
    }

    // Method untuk memilih shift
    public function selectShift($id, $nama_shift)
    {
        $this->shift_id = $id;
        $this->shift_nama = $nama_shift;
        $this->shifts = [];
    }

    // Method untuk memilih opsi absensi
    // public function selectOpsi($id, $name)
    // {
    //     $this->opsi_id = $id;
    //     $this->opsi_nama = $name;
    //     $this->opsis = [];
    // }


    public function render()
    {
        return view('livewire.create-jadwal');
    }
}
