<div class="text-md focus:outline-none focus:ring-2 focus:ring-success-500 flex w-full max-w-80 sm:max-w-60">
    <div class="relative w-full" x-data="{ open: false }">
        <div class="flex flex-wrap items-center gap-2 p-1.5 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-success-600 h-full text-gray-700 w-full cursor-pointer min-h-[42px]"
            style="background-color: #e9e9ed;"
            @click="
                open = true;
                setTimeout(() => $refs.searchInput.focus(), 50);
            ">
            @if ($single)
                @if ($selectedLabel)
                    <div class="flex items-center w-full gap-1">
                        <span class="w-full px-2 text-md truncate" title="{{ $selectedLabel }}">
                            <span class="text-gray-500">Unit - </span>
                            <span class="font-bold">{{ $selectedLabel }}</span>
                        </span>

                        <button type="button" wire:click.stop="clearSelection"
                            class="px-2 text-red-500 hover:text-red-700">
                            &times;
                        </button>
                    </div>
                @endif
            @else
                @if (!empty($selectedItems))
                    <div class="w-full">
                        @foreach ($selectedItems as $item)
                            <div class="flex items-center w-full gap-1">
                                <span wire:key="pill-{{ data_get($item, $valueKey) }}"
                                    class="w-full px-2 text-md truncate" title="{{ data_get($item, $labelKey) }}">
                                    <span class="text-gray-500">Unit - </span>
                                    <span class="font-bold">{{ data_get($item, $labelKey) }}</span>
                                </span>

                                <button type="button" wire:click.stop="removeItem(@js(data_get($item, $valueKey)))"
                                    class="px-2 text-red-500 hover:text-red-700">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

            <input x-ref="searchInput" type="text" wire:model.live="search" @focus="open = true" @click.stop
                @click.away="open = false"
                placeholder="{{ $single && $selectedLabel ? 'Cari unit lain...' : $placeholder }}"
                class="flex min-w-0 w-full px-2 text-md text-gray-700 placeholder-gray-700 bg-transparent border-none outline-none focus:ring-0">
        </div>

        <div x-show="open" x-transition
            class="absolute z-50 mt-1 w-full max-h-[400px] overflow-y-auto border border-gray-200 rounded-md shadow-lg"
            style="background-color: #e9e9ed;">
            @forelse ($filteredOptions as $option)
                @php
                    $optId = data_get($option, $valueKey);
                    $optLabel = data_get($option, $labelKey);
                @endphp

                <div wire:key="opt-{{ $optId }}" wire:click="selectItem(@js($optId), @js($optLabel))"
                    @click="open = false"
                    class="px-4 py-2 text-md text-gray-700 cursor-pointer hover:bg-success-50 hover:text-success-700">
                    {{ $optLabel }}
                </div>
            @empty
                <div class="px-4 py-2 text-md text-gray-500 bg-gray-50">
                    Tidak ada hasil.
                </div>
            @endforelse
        </div>
    </div>
</div>
