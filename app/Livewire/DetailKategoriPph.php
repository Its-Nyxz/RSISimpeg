<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxBracket;
use App\Models\Kategoripph;

class DetailKategoriPph extends Component
{
    public $kategori_id;
    public $kategori;
    public $search = '';
    public $editTaxId = null;
    public $upper_limit, $persentase;

    public $showForm = false;

    public function mount($kategori_id)
    {
        $this->kategori_id = $kategori_id;
        $this->loadKategori();
    }

    public function loadKategori()
    {
        $this->kategori = Kategoripph::with(['children', 'taxBrackets' => function ($query) {
            $query->orderBy('upper_limit');

            if ($this->search) {
                $search = $this->search;
                $query->where(function ($q) use ($search) {
                    $q->where('upper_limit', 'like', "%$search%")
                        ->orWhere('persentase', 'like', "%$search%");
                });
            }
        }])->findOrFail($this->kategori_id);
    }

    public function updatedSearch()
    {
        $this->loadKategori();
    }

    public function openTaxBracketForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editTaxBracket($id)
    {
        $tax = TaxBracket::findOrFail($id);
        $this->editTaxId = $tax->id;
        $this->upper_limit = $tax->upper_limit;
        $this->persentase = $tax->persentase * 100;
        $this->showForm = true;
    }

    public function saveTaxBracket()
    {
        $this->validate([
            'upper_limit' => 'required|numeric|min:0',
            'persentase' => 'required|numeric|min:0|max:100',
        ]);

        TaxBracket::updateOrCreate(
            ['id' => $this->editTaxId],
            [
                'kategoripph_id' => $this->kategori->id,
                'upper_limit' => $this->upper_limit,
                'persentase' => $this->persentase / 100,
            ]
        );

        $this->resetForm();
        $this->loadKategori();
    }

    public function resetForm()
    {
        $this->editTaxId = null;
        $this->upper_limit = '';
        $this->persentase = '';
        $this->showForm = false;
    }

    public function deleteTaxBracket($id)
    {
        TaxBracket::where('id', $id)->delete();

        $this->resetForm(); // Optional, if editing was open
        $this->loadKategori(); // Refresh table
    }

    public function render()
    {
        return view('livewire.detail-kategori-pph');
    }
}
