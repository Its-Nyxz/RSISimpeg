<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Kategori Jabatan</h1>
        <!-- Kontrol Aksi -->
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Kategori Jabatan..."
                class="flex-1 sm:w-64 rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            <!-- Mobile Icon Only -->
            <a href="{{ route('katjab.create') }}"
                class="sm:hidden inline-flex items-center justify-center p-2.5 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shrink-0"
                aria-label="Tambah Kategori Jabatan" data-tooltip-target="tooltip-katjab"
                data-tooltip-placement="top">
                <i class="fa fa-plus"></i>
            </a>
            <!-- Tooltip -->
            <div id="tooltip-katjab" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                Tambah Kategori Jabatan
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <!-- Desktop Button -->
            <a href="{{ route('katjab.create') }}"
                class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                + Tambah Kategori Jabatan
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Kategori</th>
                    <th scope="col" class="px-6 py-3">Tunjangan</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($katjab as $kategori)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $kategori['nama'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4">{{ ucfirst($kategori['tunjangan']) }}</td>
                        <td class="px-6 py-4">{{ $kategori['keterangan'] }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('katjab.edit', $kategori['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-kategori-{{ $kategori['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-kategori-{{ $kategori['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Kategori Jabatan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus Jabatan ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $kategori['id'] }}))"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash"></i>
                                <div id="tooltip-destroy-{{ $kategori['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md">
                                    Hapus Kategori Jabatan
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                            <div id="tooltip-destroy-{{ $kategori['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Hapus Kategori Jabatan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-4">Tidak ada data Kategori Jabatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Controls -->
    <div class="mt-4 flex gap-2 justify-center items-center">
        {{-- Previous Page Link --}}
        @if (!$katjab->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        {{-- Pagination Numbers --}}
        @php
            $totalPages = $katjab->lastPage();
            $currentPage = $katjab->currentPage();
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
        @if ($katjab->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
