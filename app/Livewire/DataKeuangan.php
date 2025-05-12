<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Livewire\WithPagination;
use App\Models\JenisKaryawan;
use Livewire\WithFileUploads;
use App\Imports\PotonganImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;
use App\Exports\PotonganTemplateExport;

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



    public function render()
    {
        $users = $this->loadData();
        return view('livewire.data-keuangan', [
            'users' => $users
        ]);
    }
}
