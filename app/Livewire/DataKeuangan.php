<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\GajiNetto;
use App\Models\UnitKerja;
use Livewire\WithPagination;
use App\Models\JenisKaryawan;
use Livewire\WithFileUploads;
use App\Imports\PotonganImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;
use App\Exports\PotonganTemplateExport;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DataKeuangan extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $units;
    public $selectedUnit = null;
    public $selectedJenisKaryawan = null;
    public $jenisKaryawans = [];
    public $bulan;
    public $tahun;
    public $importFile;
    public $file;
    protected $listeners = ['openGenerateModal' => '$refresh'];

    public function mount()
    {
        $this->units = UnitKerja::all();
        $this->jenisKaryawans = JenisKaryawan::all();
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadData();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function loadData()
    {
        return User::with(['kategorijabatan', 'unitKerja', 'roles'])->where('id', '>', '1')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('no_ktp', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedUnit, function ($query) {
                $unitIds = UnitKerja::where('id', $this->selectedUnit)
                    ->orWhere('parent_id', $this->selectedUnit)
                    ->pluck('id')
                    ->toArray();

                $query->whereIn('unit_id', $unitIds);
            })
            ->when($this->selectedJenisKaryawan, function ($query) {
                $query->where('jenis_id', $this->selectedJenisKaryawan);
            })->orderBy('name', 'asc')->paginate(15);
    }

    public function downloadTemplate()
    {
        $month = $this->bulan;
        $year = $this->tahun;
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');
        $filename = 'potongan_template_' . $monthName . '_' . $this->tahun . '.xlsx';

        return Excel::download(new PotonganTemplateExport(
            bulan: $this->bulan,
            tahun: $this->tahun,
            unitId: $this->selectedUnit,
            jenisId: $this->selectedJenisKaryawan,
            keyword: $this->search
        ), $filename);
    }


    public function import()
    {
        // Validasi file sebelum diupload
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', // Validasi file
        ]);

        $fileName = $this->file->getClientOriginalName();
        // Format bulan ke dalam nama (contoh: Maret)
        $monthName = Carbon::createFromDate($this->tahun, $this->bulan, 1)->format('F');

        // Format nama file
        $expectedFileName = 'potongan_template_' . $monthName . '_' . $this->tahun . '.xlsx';

        // dd($fileName, $expectedFileName, $fileName == $expectedFileName);

        // Cek apakah nama file sesuai format
        if ($fileName !== $expectedFileName) {
            return redirect()->route('keuangan.index')->with('error', 'masukan file sesuai bulan yang dipilih ');
        }

        try {
            // Lakukan proses import dengan filter bulan & tahun
            Excel::import(new PotonganImport($this->bulan, $this->tahun), $this->file->getRealPath());

            // Reset input file setelah sukses
            $this->reset('file');

            // Kirim notifikasi sukses ke Livewire
            return redirect()->route('keuangan.index')->with('success', 'Data Potongan berhasil dimasukan');
        } catch (\Exception $e) {
            return redirect()->route('keuangan.index')->with('error', 'Terjadi Kesalahan');
        }
    }

    public function confirmGenerateNetto()
    {
        $users = $this->loadData(); // sesuai filter & paginasi
        foreach ($users as $user) {
            $bruto = $user->gajiBruto()
                ->where('bulan_penggajian', $this->bulan)
                ->where('tahun_penggajian', $this->tahun)
                ->first();

            if (!$bruto) continue;

            $totalPotongan = $bruto->potongan->sum('nominal');
            $netto = $bruto->total_bruto - $totalPotongan;

            // Jangan simpan jika netto tidak valid
            if ($netto <= 0) continue;

            GajiNetto::updateOrCreate(
                ['bruto_id' => $bruto->id],
                [
                    'total_netto' => $netto,
                    'tanggal_transfer' => now(),
                    'status' => 'Pending',
                ]
            );

            $namaBulan = Carbon::createFromFormat('!m', $this->bulan)->locale('id')->isoFormat('MMMM');
            $message = 'Slip Gaji Anda bulan ' . $namaBulan . ' telah tersedia, <span class="text-green-600 font-bold">Silahkan Di Cek</span>.';
            $url = '/slipgaji'; // sesuaikan dengan route ke halaman slip

            Notification::send($user, new UserNotification($message, $url));
        }

        return redirect()->route('keuangan.index')->with('success', 'Slip Gaji Berhasil dikirim ke Karyawan!');
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-keuangan', [
            'users' => $users
        ]);
    }
}
