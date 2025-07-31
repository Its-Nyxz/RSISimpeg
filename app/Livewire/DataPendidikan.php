<?php

namespace App\Livewire;

use App\Models\MasterPendidikan;
use Livewire\Component;

class DataPendidikan extends Component
{

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $pendidikans = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData($searchGolonganName = null)
    {
        $this->pendidikans = MasterPendidikan::with(['minimGolongan', 'maximGolongan']) // Load relasi ke golongan
            // ->when($this->search, function ($query) {
            //     $query->where('nama', 'like', '%' . $this->search . '%')
            //         ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            // })
            // ->when($searchGolonganName, function ($query) use ($searchGolonganName) {
            //     $query->whereHas('minimGolongan', function ($subQuery) use ($searchGolonganName) {
            //         $subQuery->where('nama', 'like', '%' . $searchGolonganName . '%');
            //     })
            //         ->orWhereHas('maximGolongan', function ($subQuery) use ($searchGolonganName) {
            //             $subQuery->where('nama', 'like', '%' . $searchGolonganName . '%');
            //         });
            // })
            ->get();
    }


    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.data-pendidikan');
    }
}
