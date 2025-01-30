<?php
namespace App\Livewire;
use App\Models\Shift;
use Livewire\Component;
class DataShift extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $shifts = [];
    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $this->shifts = Shift::when( $this->search, function($query){
            $query->where('nama_shift', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('jam_keluar', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-shift');
    }
}