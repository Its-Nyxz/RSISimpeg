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

    public $pillPrefix = 'Unit - ';

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

        $exists = collect($this->selectedItems)->contains(function ($item) use ($id) {
            $itemId = is_array($item) ? data_get($item, $this->valueKey) : $item;
            return (string) $itemId === (string) $id;
        });

        if (!$exists) {
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
            $itemId = is_array($item) ? data_get($item, $this->valueKey) : $item;
            return (string) $itemId !== (string) $id;
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
            // Handle case where selectedItems might be an array even in single mode
            $currentValue = is_array($this->selectedItems)
                ? (collect($this->selectedItems)->first() ?? null)
                : $this->selectedItems;

            $selectedIds = filled($currentValue)
                ? [(string) $currentValue]
                : [];

            $selectedOption = collect($this->options)->first(function ($option) use ($currentValue) {
                return (string) $this->getValue($option) === (string) $currentValue;
            });

            $selectedLabel = $selectedOption
                ? $this->getLabel($selectedOption)
                : null;
        } else {
            if (!is_array($this->selectedItems)) {
                $this->selectedItems = [];
            }

            $selectedIds = collect($this->selectedItems)
                ->map(fn($item) => is_array($item) ? (string) data_get($item, $this->valueKey) : (string) $item)
                ->filter()
                ->values()
                ->all();
        }

        $filteredOptions = collect($this->options)
            ->filter(function ($option) use ($selectedIds) {
                $id = $this->getValue($option);
                $label = $this->getLabel($option);

                // Ensure they are strings before using them
                $idStr = is_array($id) ? json_encode($id) : (string) $id;
                $labelStr = is_array($label) ? json_encode($label) : (string) $label;

                $matchSearch = $this->search === ''
                    || stripos($labelStr, $this->search) !== false;

                return $matchSearch && !in_array($idStr, $selectedIds, true);
            })
            ->values();

        return view('livewire.searchable-pillbox', [
            'filteredOptions' => $filteredOptions,
            'selectedLabel' => $selectedLabel,
        ]);
    }
}
