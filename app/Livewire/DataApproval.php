<?php

namespace App\Livewire;

use App\Models\CutiKaryawan;
use App\Models\RiwayatApproval;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class DataApproval extends Component
{
    use WithPagination;

    public $hari;
    public $bulan;
    public $tahun;
    public $search = '';
    public $units;
    public $isKepegawaian = false;
    public $selectedUnit = null;
    public $showModalDetailCuti = false;
    public $detailCuti;

    public function openDetail($id)
    {
        $this->detailCuti = CutiKaryawan::with(['user', 'jenisCuti', 'statusCuti', 'riwayatApprovals'])->find($id);
        $this->showModalDetailCuti = true;
    }


    public function mount()
    {
        $now = Carbon::now('Asia/Jakarta');

        $this->bulan = $now->format('n');
        $this->tahun = $now->format('Y');

        $this->units = UnitKerja::all();


        $unitKepegawaianId = 87;
        $user = auth()->user();

        $this->isKepegawaian = $user->unit_id == $unitKepegawaianId;
        $this->selectedUnit = $this->isKepegawaian ? null : $user->unitkerja?->id;
        // dd($this->selectedUnit);
    }

    public function loadData()
    {
        $query = RiwayatApproval::query()
            ->with(['cuti.user.unitKerja', 'approver'])
            // Filter Tanggal
            ->when($this->hari, fn($q) => $q->whereDay('approve_at', $this->hari))
            ->when($this->bulan, fn($q) => $q->whereMonth('approve_at', $this->bulan))
            ->when($this->tahun, fn($q) => $q->whereYear('approve_at', $this->tahun))

            // Filter Pencarian Nama Karyawan
            ->when($this->search, function ($q) {
                $q->whereHas('cuti.user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                });
            })

            // Filter Unit Kerja (Jika dipilih dari dropdown)
            ->when($this->selectedUnit, function ($q) {
                $q->whereHas('cuti.user', function ($u) {
                    $u->where('unit_id', $this->selectedUnit);
                });
            });


        $user = auth()->user();
        if (!$user->hasRole('Super Admin')) {
            $query->whereHas('approver', function ($q) use ($user) {
                $q->where('unit_id', $user->unit_id);
            });
        }




        return $query->latest('approve_at')->paginate(10);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.data-approval', [
            'riwayats' => $this->loadData(),
        ]);
    }
}
