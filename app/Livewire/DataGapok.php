<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MasterGapok;
use Livewire\WithPagination;

class DataGapok extends Component
{
    use WithPagination; // Gunakan trait
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    // public $gapoks = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // $this->gapoks =
        return MasterGapok::with('golongan')->when($this->search, function ($query) {
            $query->where('nominal_gapok', 'like', '%' . $this->search . '%')
                ->orWhereHas('golongan', function ($subQuery) {
                    $subQuery->where('nama', 'like', '%' . $this->search . '%');
                });
        })
            // ->get()
            // ->toArray();
            ->paginate(15);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        $gapoks = $this->loadData(); // Ambil data paginasi
        return view('livewire.data-gapok', [
            'gapoks' => $gapoks, // Kirim data ke tampilan
        ]);
    }
}
