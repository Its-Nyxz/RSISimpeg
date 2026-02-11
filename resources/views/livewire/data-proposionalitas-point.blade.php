<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Proposionalitas Point</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Proposionalitas..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <a href="{{route('proposionalitas.create')}}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Proposionalitas
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Proposionalitas</th>
                    <th scope="col" class="px-6 py-3">Unit Kerja</th>
                    <th scope="col" class="px-6 py-3">Point</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $item['proposable']['kategorijabatan']['nama'] }}
                        </td>
                        <td class="px-6 py-4">{{ $item['unitkerja']['nama'] ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item['point'] }}</td>
                        <td class="px-6 py-4">
                            <a href="{{route('proposionalitas.edit', $item['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-jadwal-{{ $item['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Proposionalitas
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-6 py-4">Tidak ada data Proposionalitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex gap-2 justify-center items-center">
        {{-- Previous Page Link --}}
        @if (!$items->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        {{-- Pagination Numbers --}}
        @php
            $totalPages = $items->lastPage();
            $currentPage = $items->currentPage();
            $range = 3; // Range around current page
        @endphp

        {{-- First Page --}}
        @if ($currentPage > $range + 1)
            <button wire:click="gotoPage(1)"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                1
            </button>
            @if ($currentPage > $range + 2)
                <span class="px-2 py-1 text-gray-500">...</span>
            @endif
        @endif

        {{-- Pages Around Current Page --}}
        @for ($page = max($currentPage - $range, 1); $page <= min($currentPage + $range, $totalPages); $page++)
            @if ($page == $currentPage)
                <span class="px-2 py-1 bg-success-600 text-white rounded-md text-sm">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }})"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    {{ $page }}
                </button>
            @endif
        @endfor

        {{-- Last Page --}}
        @if ($currentPage < $totalPages - $range)
            @if ($currentPage < $totalPages - $range - 1)
                <span class="px-2 py-1 text-gray-500">...</span>
            @endif
            <button wire:click="gotoPage({{ $totalPages }})"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                {{ $totalPages }}
            </button>
        @endif

        {{-- Next Page Link --}}
        @if ($items->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
