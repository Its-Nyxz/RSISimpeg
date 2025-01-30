<?php

namespace App\Livewire;

use App\Models\MasaKerja;
use Livewire\Component;
use Livewire\WithPagination;

class DataMasaKerja extends Component
{
    use WithPagination;
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        return MasaKerja::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('point', 'like', '%' . $this->search . '%');
        })->paginate(15);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function render()
    {
        $masakerja = $this->loadData();
        return view('livewire.data-masa-kerja', [
            'masakerja' => $masakerja,
        ]);
    }
}
