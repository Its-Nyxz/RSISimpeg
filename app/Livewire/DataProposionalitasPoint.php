<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProposionalitasPoint;

class DataProposionalitasPoint extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset halaman saat pencarian berubah
        $this->loadData();
    }

    public function loadData()
    {
        // Ambil data dengan pagination
        // $query = 
        return ProposionalitasPoint::with('proposable', 'unitkerja')
            ->when($this->search, function ($query) {
                $query->whereHasMorph('proposable', ['App\Models\MasterFungsi', 'App\Models\MasterUmum'], function ($q) {
                    $q->whereHas('kategorijabatan', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->paginate(15); // Pagination sebelum mapping

        // // Simpan pagination agar tetap bisa ditampilkan di Blade
        // $this->pagination = $query;

        // // Lakukan mapping setelah pagination
        // $this->items = $query->map(function ($proposionalitaspoint) {
        //     return [
        //         'id' => $proposionalitaspoint->id,
        //         'nama' => $proposionalitaspoint->proposable->kategorijabatan->nama ?? '-',
        //         'nama_unit' => $proposionalitaspoint->unitkerja->nama ?? '-',
        //         'poin' => $proposionalitaspoint->point,
        //     ];
        // })->toArray(); // Pastikan mengubah hasil ke array
    }

    public function render()
    {
        $items = $this->loadData(); // Ambil data paginasi
        return view('livewire.data-proposionalitas-point', [
            'items' => $items,
        ]);
    }
}
