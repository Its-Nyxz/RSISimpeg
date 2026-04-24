<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class SearchablePillbox extends Component
{
    #[Modelable]
    public $selectedItems = []; 

    public $options = []; // The list of all available options to pick from
    public $search = '';
    public $placeholder = 'Search...';
    
    // Configurable keys just in case your database columns differ (e.g., 'nama' vs 'name')
    public $valueKey = 'id';
    public $labelKey = 'name';

    public function selectItem($id, $label)
    {
        // Prevent duplicates
        if (!collect($this->selectedItems)->contains($this->valueKey, $id)) {
            $this->selectedItems[] = [
                $this->valueKey => $id, 
                $this->labelKey => $label
            ];
        }
        $this->search = ''; // Reset search
    }

    public function removeItem($id)
    {
        // Filter out the clicked item
        $this->selectedItems = array_values(array_filter($this->selectedItems, function ($item) use ($id) {
            return $item[$this->valueKey] !== $id;
        }));
    }

    public function render()
    {
        $selectedIds = array_column($this->selectedItems, $this->valueKey);
        // Filter options based on search and exclude already selected items
        $filteredOptions = collect($this->options)
            ->filter(function($option) use ($selectedIds) {
                // Support both arrays and objects
                $id = is_array($option) ? $option[$this->valueKey] : $option->{$this->valueKey};
                $label = is_array($option) ? $option[$this->labelKey] : $option->{$this->labelKey};

                return stripos($label, $this->search) !== false && !in_array($id, $selectedIds);
            });

        return view('livewire.searchable-pillbox', [
            'filteredOptions' => $filteredOptions
        ]);
    }
}
