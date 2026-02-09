@props(['data'])

<div class="mt-4 px-2 sm:px-0">
    {{-- Mobile Logic: Simple Previous/Next + Info --}}
    <div class="flex justify-between items-center sm:hidden">
        <button wire:click="previousPage" wire:loading.attr="disabled" @if ($data->onFirstPage()) disabled
            class="opacity-50 cursor-not-allowed px-3 py-2 bg-gray-100 text-gray-400 rounded-md text-sm border font-medium"
        @else
                class="px-3 py-2 bg-white text-gray-700 hover:bg-gray-50 rounded-md text-sm border font-medium transition-colors"
            @endif>
            « Prev
        </button>

        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
            Hal {{ $data->currentPage() }} / {{ $data->lastPage() }}
        </span>

        <button wire:click="nextPage" wire:loading.attr="disabled" @if (!$data->hasMorePages()) disabled
            class="opacity-50 cursor-not-allowed px-3 py-2 bg-gray-100 text-gray-400 rounded-md text-sm border font-medium"
        @else
                class="px-3 py-2 bg-white text-gray-700 hover:bg-gray-50 rounded-md text-sm border font-medium transition-colors"
            @endif>
            Next »
        </button>
    </div>

    {{-- Desktop Logic: Full Pagination --}}
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center gap-2">
        {{-- Previous Page Link --}}
        @if (!$data->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-3 py-1 bg-white hover:bg-success-50 text-gray-700 hover:text-success-700 rounded-md text-sm border transition-colors">
                «
            </button>
        @endif

        {{-- First Page --}}
        @php
            $totalPages = $data->lastPage();
            $currentPage = $data->currentPage();
            $range = 2; // Reduced range for cleaner look
        @endphp

        @if ($currentPage > $range + 1)
            <button wire:click="gotoPage(1)"
                class="px-3 py-1 bg-white hover:bg-success-50 text-gray-700 hover:text-success-700 rounded-md text-sm border transition-colors">
                1
            </button>
            @if ($currentPage > $range + 2)
                <span class="px-2 py-1 text-gray-400 text-sm">...</span>
            @endif
        @endif

        {{-- Pages Around Current Page --}}
        @for ($page = max($currentPage - $range, 1); $page <= min($currentPage + $range, $totalPages); $page++)
            @if ($page == $currentPage)
                <span
                    class="px-3 py-1 bg-success-600 text-white border border-success-600 rounded-md text-sm font-medium shadow-sm transition-colors">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }})"
                    class="px-3 py-1 bg-white hover:bg-success-50 text-gray-700 hover:text-success-700 rounded-md text-sm border transition-colors">
                    {{ $page }}
                </button>
            @endif
        @endfor

        {{-- Last Page --}}
        @if ($currentPage < $totalPages - $range)
            @if ($currentPage < $totalPages - $range - 1)
                <span class="px-2 py-1 text-gray-400 text-sm">...</span>
            @endif
            <button wire:click="gotoPage({{ $totalPages }})"
                class="px-3 py-1 bg-white hover:bg-success-50 text-gray-700 hover:text-success-700 rounded-md text-sm border transition-colors">
                {{ $totalPages }}
            </button>
        @endif

        {{-- Next Page Link --}}
        @if ($data->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-3 py-1 bg-white hover:bg-success-50 text-gray-700 hover:text-success-700 rounded-md text-sm border transition-colors">
                »
            </button>
        @endif
    </div>
</div>