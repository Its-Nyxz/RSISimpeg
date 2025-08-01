<?php

namespace App\Livewire;

use App\Models\Kategoripph;
use Livewire\Component;
use Livewire\WithPagination;

class DataKategoriPph extends Component
{
    use WithPagination;
    public $search = '';

    public function loadData()
    {
        return Kategoripph::with('children')
            ->whereNull('parent_id')
            ->where(function ($q) {
                $q->whereHas('children', function ($childQuery) {
                    $childQuery->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('keterangan', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(15);
    }
    public function updateSearch($value)
    {

        $this->search = $value;
        $this->loadData(); // Reset to page 1 whenever the search term is updated
    }
    public function render()
    {
        return view('livewire.data-kategori-pph', [
            'pphs' => $this->loadData(), // Pass the paginated data to the view
        ]);
    }
}
