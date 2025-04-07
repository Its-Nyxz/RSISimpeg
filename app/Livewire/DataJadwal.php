<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Imports\JadwalImport;
use App\Models\JadwalAbsensi;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use App\Models\Holidays;
use Carbon\Carbon;

class DataJadwal extends Component
{
    use WithFileUploads;
    public $search = '';
    public $bulan, $tahun;
    public $jadwals = [];
    public $tanggalJadwal = [];
    public $filteredShifts = [];
    public $file;
    public $units;
    public $users;
    public $routeIsDashboard;
    public $selectedUnit = null;
    public $selectedUser = null;

    public function mount()
    {
        $this->units = UnitKerja::orderBy('nama', 'asc')->get();

        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->routeIsDashboard = Request::routeIs('dashboard');
        if (!auth()->user()->hasRole('Super Admin')) {
            $this->selectedUnit = auth()->user()->unit_id;
            $this->loadUsers();
        } else {
            $this->loadUsers(); // Ambil semua user jika superadmin
        }

        $this->loadData();
    }

    public function updatedSelectedUnit()
    {
        $this->selectedUser = null; // Reset user jika unit berubah
        $this->loadUsers();
        $this->loadData();
    }

    public function updatedSelectedUser()
    {
        $this->loadUsers();
        $this->loadData();
    }

    public function loadUsers()
    {
        if ($this->selectedUser) {
            // Jika user dipilih, tampilkan hanya user dengan ID yang dipilih
            $this->users = User::where('id', $this->selectedUser)->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            // Jika Super Admin → Tampilkan semua user
            $this->users = User::orderBy('name', 'asc')->get();
        } elseif ($this->selectedUnit) {
            // Jika user biasa → Filter user berdasarkan unit
            $this->users = User::where('unit_id', $this->selectedUnit)
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $this->users = collect([]);
        }
    }

    public function loadData()
    {
        $this->bulan = (int) $this->bulan;
        $this->tahun = (int) $this->tahun;

        if (auth()->user()->hasRole('Super Admin') && !$this->selectedUnit) {
            $this->jadwals = [];
            $this->filteredShifts = [];
            return;
        }

        $this->tanggalJadwal = collect(range(1, cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun)))
            ->map(fn($day) => sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day))
            ->toArray();

        $unitId = $this->selectedUnit ?? auth()->user()->unit_id;

        $jadwalData = JadwalAbsensi::with(['user', 'shift'])
            ->whereYear('tanggal_jadwal', $this->tahun)
            ->whereMonth('tanggal_jadwal', $this->bulan)
            ->when($unitId, function ($query) use ($unitId) {
                $query->whereHas('user', function ($q) use ($unitId) {
                    $q->where('unit_id', $unitId);
                });
            })
            ->when($this->selectedUser, function ($query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->get();

        $this->jadwals = $jadwalData->groupBy('user_id')->map(fn($items) => $items->values());

        $this->filteredShifts = [];
        foreach ($jadwalData as $jadwal) {
            $this->filteredShifts[$jadwal->user_id][$jadwal->tanggal_jadwal] = optional($jadwal->shift)->nama_shift ?? '-';
        }
    }

    // Fungsi untuk menandai tanggal merah (libur nasional atau Minggu)
    public function isHoliday($date)
    {
        $carbonDate = Carbon::parse($date);

        // Tandai merah jika hari Minggu
        if ($carbonDate->isSunday()) {
            return true;
        }

        // Cek di database jika tanggal termasuk libur nasional
        $holiday = Holidays::where('date', $carbonDate->format('Y-m-d'))->exists();

        if ($holiday) {
            return true;
        }

        return false;
    }


    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', // Validasi file
        ]);

        Excel::import(new JadwalImport, $this->file);


        session()->flash('success', 'Jadwal berhasil diimport!');
        $this->loadData(); // Refresh data setelah import
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bulan', 'tahun'])) {
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.data-jadwal');
    }
}
