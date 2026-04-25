<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class SearchablePillbox extends Component
{
    #[Modelable]
    public $selectedItems = [];

    public $options = [];
    public $search = '';
    public $placeholder = 'Search...';

    public $valueKey = 'id';
    public $labelKey = 'name';

    public bool $single = false;

    private function getValue($option)
    {
        return data_get($option, $this->valueKey);
    }

    private function getLabel($option)
    {
        return data_get($option, $this->labelKey);
    }

    public function selectItem($id, $label = null)
    {
        if ($this->single) {
            $this->selectedItems = $id;
            $this->search = '';
            return;
        }

        if (!is_array($this->selectedItems)) {
            $this->selectedItems = [];
        }

        if (!collect($this->selectedItems)->contains($this->valueKey, $id)) {
            $this->selectedItems[] = [
                $this->valueKey => $id,
                $this->labelKey => $label,
            ];
        }

        $this->search = '';
    }

    public function removeItem($id)
    {
        if ($this->single) {
            $this->selectedItems = null;
            $this->search = '';
            return;
        }

        if (!is_array($this->selectedItems)) {
            $this->selectedItems = [];
            return;
        }

        $this->selectedItems = array_values(array_filter($this->selectedItems, function ($item) use ($id) {
            return (string) data_get($item, $this->valueKey) !== (string) $id;
        }));
    }

    public function clearSelection()
    {
        $this->selectedItems = $this->single ? null : [];
        $this->search = '';
    }

    public function render()
    {
        $selectedLabel = null;

        if ($this->single) {
            $selectedIds = filled($this->selectedItems)
                ? [(string) $this->selectedItems]
                : [];

            $selectedOption = collect($this->options)->first(function ($option) {
                return (string) $this->getValue($option) === (string) $this->selectedItems;
            });

            $selectedLabel = $selectedOption
                ? $this->getLabel($selectedOption)
                : null;
        } else {
            if (!is_array($this->selectedItems)) {
                $this->selectedItems = [];
            }

            $selectedIds = collect($this->selectedItems)
                ->map(fn($item) => (string) data_get($item, $this->valueKey))
                ->filter()
                ->values()
                ->all();
        }

        $filteredOptions = collect($this->options)
            ->filter(function ($option) use ($selectedIds) {
                $id = (string) $this->getValue($option);
                $label = (string) $this->getLabel($option);

                $matchSearch = $this->search === ''
                    || stripos($label, $this->search) !== false;

                return $matchSearch && !in_array($id, $selectedIds, true);
            })
            ->values();

        return view('livewire.searchable-pillbox', [
            'filteredOptions' => $filteredOptions,
            'selectedLabel' => $selectedLabel,
        ]);
    }
}
