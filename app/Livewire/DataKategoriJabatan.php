<?php

namespace App\Livewire;

use App\Models\KategoriJabatan;
use Livewire\Component;
use Livewire\WithPagination;

class DataKategoriJabatan extends Component
{
    use WithPagination;
    
    public $search = '';

    // Method to load data with pagination
    public function loadData()
    {
        return KategoriJabatan::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('tunjangan', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%');
        })
        ->paginate(15); // You can adjust the number of items per page as needed
    }

    // Method to update search value and reload data
    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage(); // Reset to page 1 whenever the search term is updated
    }

    public function render()
    {
        return view('livewire.data-kategori-jabatan', [
            'katjab' => $this->loadData(), // Pass the paginated data to the view
        ]);
    }
}
