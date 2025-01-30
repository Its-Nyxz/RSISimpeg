<?php

namespace App\Livewire;

use App\Models\LevelUnit;
use Livewire\Component;
use Livewire\WithPagination;

class DataLevelUnit extends Component
{
    use WithPagination;

    public $search = '';

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function loadData()
    {
        return LevelUnit::with(['unitkerja', 'levelpoint'])
            ->when($this->search, function ($query) {
                $query->whereHas('unitkerja', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })->orWhereHas('levelpoint', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('point', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(15);
    }

    public function render()
    {
        $levelunit = $this->loadData();
        return view('livewire.data-level-unit', [
            'levelunit' => $levelunit
        ]);
    }
}
