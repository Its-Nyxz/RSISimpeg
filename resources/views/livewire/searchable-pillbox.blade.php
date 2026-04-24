<div class="text-md focus:outline-none focus:ring-2 focus:ring-success-500 flex w-full max-w-80 sm:max-w-60">
    <div class="relative w-full" x-data="{ open: false }">
        <div 
            class="flex flex-wrap items-center gap-2 p-1.5 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-success-600 h-full text-gray-700 w-full cursor-pointer min-h-[42px]" 
            style="background-color: #e9e9ed;"
            @click="
                $wire.search = '';
                $wire.selectedItems = [];
                open = true;
                setTimeout(() => $refs.searchInput.focus(), 50);
            "
        >
            {{-- selected item --}}
            <div x-show="$wire.selectedItems.length > 0" class="w-full">
                @foreach($selectedItems as $item)
                    <div class="flex items-center w-full gap-1">
                        <span wire:key="pill-{{ $item[$valueKey] }}" class="w-full px-2 text-md truncate" title="{{ $item[$labelKey] }}">
                            <span class="text-gray-500">Unit - </span>
                            <span class="font-bold">{{ $item[$labelKey] }}</span> 
                        </span>
                    </div>
                @endforeach
            </div>
            {{-- Search Input --}}
            <input 
                x-ref="searchInput"
                x-show="$wire.selectedItems.length === 0"
                type="text"
                wire:model.live="search"
                @focus="open = true"
                @click.away="open = false"
                placeholder="{{ empty($selectedItems) ? $placeholder : '' }}"
                class="flex min-w-0 w-full px-2 text-md text-gray-700 placeholder-gray-700 bg-transparent border-none outline-none focus:ring-0"
            >
        </div>
        {{-- dropdown --}}
        @if(strlen($search) > 0 || empty($selectedItems))
            <div x-show="open" x-transition class="absolute z-50 mt-1 w-full max-h-[400px] overflow-y-auto border border-gray-200 rounded-md shadow-lg" style="background-color: #e9e9ed;">
                @forelse($filteredOptions as $option)
                    @php
                        // handle array
                        $optId = is_array($option) ? $option[$valueKey] : $option->{$valueKey};
                        $optLabel = is_array($option) ? $option[$labelKey] : $option->{$labelKey};
                    @endphp
                    <div
                        wire:key="opt-{{ $optId }}"
                        @click="
                            if (!$wire.selectedItems.some(i => i['{{ $valueKey }}'] == '{{ $optId }}')) {
                                $wire.selectedItems = [...$wire.selectedItems, { '{{ $valueKey }}': '{{ $optId }}', '{{ $labelKey }}': '{{ $optLabel }}' }];
                            }
                            $wire.search = '';
                            open = false;
                        "
                        class="px-4 py-2 text-md text-gray-700 cursor-pointer hover:bg-success-50 hover:text-success-700"
                    >
                        {{ $optLabel }}
                    </div>
                @empty
                    <div class="px-4 py-2 text-md text-gray-500 bg-gray-50">
                        Tidak ada hasil.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>