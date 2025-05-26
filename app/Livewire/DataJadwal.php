<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use Livewire\Component;
use App\Models\Holidays;
use App\Models\UnitKerja;
use App\Imports\JadwalImport;
use App\Models\JadwalAbsensi;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;

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
    public $showModalShift = false;
    public $dataShifts = [];

    public $showModalDetailShift = false;
    public $shiftNama;
    public $shiftJamMasuk;
    public $shiftJamKeluar;
    public $shiftKeterangan;

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

        $jadwalData = JadwalAbsensi::with(['user.jenis', 'shift'])
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
            $this->filteredShifts[$jadwal->user_id][$jadwal->tanggal_jadwal] = [
                'nama_shift' => optional($jadwal->shift)->nama_shift,
                'jam_masuk' => optional($jadwal->shift)->jam_masuk,
                'jam_keluar' => optional($jadwal->shift)->jam_keluar,
                'keterangan' => optional($jadwal->shift)->keterangan,
            ];
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

    public function openShiftModal()
    {
        $unitId = auth()->user()->unit_id;

        $this->dataShifts = Shift::with('unitKerja')
            ->where('unit_id', $unitId)
            ->get();

        $this->showModalShift = true;
    }

    public function closeShiftModal()
    {
        $this->showModalShift = false;
    }


    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        // Set bahasa Indonesia
        Carbon::setLocale('id'); // Tambahkan ini

        $uploadedFileName = $this->file->getClientOriginalName();
        $monthName = Carbon::createFromDate($this->tahun, $this->bulan, 1)->translatedFormat('F');

        // Dapatkan nama unit berdasarkan role
        $canSelectUnit = auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama === 'KEPEGAWAIAN';

        $unitNama = $canSelectUnit
            ? UnitKerja::find($this->selectedUnit)?->nama
            : auth()->user()->unitKerja->nama;

        if (!$unitNama) {
            return redirect()->route('jadwal.index')->with('error', 'Unit tidak ditemukan.');
        }

        $expectedFileName = 'jadwal_template_' . $unitNama . '_' . $monthName . '_' . $this->tahun . '.xlsx';
        // Validasi nama file
        if ($uploadedFileName !== $expectedFileName) {
            return redirect()->route('jadwal.index')->with('error', "Nama file tidak sesuai. Harus: {$expectedFileName}");
        }

        try {
            // Jalankan proses import
            Excel::import(new JadwalImport($this->bulan, $this->tahun), $this->file->getRealPath());

            $this->reset('file');

            return redirect()->route('jadwal.index')->with('success', 'Data Jadwal Berhasil Diinput');
        } catch (\Throwable $e) {
            Log::error('Import Gagal: ' . $e->getMessage());
            return redirect()->route('jadwal.index')->with('error', 'Terjadi kesalahan saat mengimpor file.');
        }
    }


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bulan', 'tahun'])) {
            $this->loadData();
        }
    }

    public function showShiftDetail($nama_shift, $jam_masuk, $jam_keluar, $keterangan)
    {
        $this->shiftNama = $nama_shift;
        $this->shiftJamMasuk = $jam_masuk;
        $this->shiftJamKeluar = $jam_keluar;
        $this->shiftKeterangan = $keterangan;
        $this->showModalDetailShift = true;
    }

    public function render()
    {
        return view('livewire.data-jadwal');
    }
}
