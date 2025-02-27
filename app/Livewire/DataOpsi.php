<?php
namespace App\Livewire;
use App\Models\OpsiAbsen;
use Livewire\Component;
class DataOpsi extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $opsis = [];
    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $this->opsis = OpsiAbsen::when( $this->search, function($query){
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->get()
            ->toArray();
    }
    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.data-opsi');
    }
}