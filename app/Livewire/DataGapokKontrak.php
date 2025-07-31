<?php

namespace App\Livewire;

use App\Models\GapokKontrak;
use Livewire\Component;
use Livewire\WithPagination;

class DataGapokKontrak extends Component
{
    use WithPagination; // Gunakan trait
    public $search = '';
    // public $kontraks = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        return GapokKontrak::with(['kategoriJabatan', 'pendidikan', 'penyesuaian'])
            ->when($this->search, function ($query) {
                $query->where('nominal', 'like', '%' . $this->search . '%')
                    ->orWhereHas('kategoriJabatan', function ($subQuery) {
                        $subQuery->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->paginate(15);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        $kontraks = $this->loadData(); // Ambil data paginasi
        return view('livewire.data-gapok-kontrak', [
            'kontraks' => $kontraks, // Kirim data ke tampilan
        ]);
    }
}
