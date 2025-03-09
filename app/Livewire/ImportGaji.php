<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\KategoriJabatan;
use Livewire\Component;

class ImportGaji extends Component
{
    public $search = '';
    public $users = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData(){
        $this->users = User::when($this->search,function($query){
            $query->where('name', 'like', '%' . $this->search . '$');
        })->get();
    }
    public function render()
    {
        return view('livewire.import-gaji');
    }
}
