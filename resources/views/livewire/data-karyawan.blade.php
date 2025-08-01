<div class="mb-4">
    <!-- FILTER -->
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Data Karyawan</h1>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-3">

        <!-- Filter Dropdowns -->
        <div class="flex flex-wrap gap-3 justify-center md:justify-start w-full md:w-auto">
            <select wire:model.live="selectedUserAktif"
                class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="1">Aktif</option>
                <option value="0">Non Aktif</option>
            </select>

            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama === 'KEPEGAWAIAN')
                <select wire:model.live="selectedUnit"
                    class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif

            <select wire:model.live="selectedJenisKaryawan"
                class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="">-- Semua Jenis Karyawan --</option>
                @foreach ($jenisKaryawans as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- AKSI -->
        <div class="flex flex-wrap justify-center md:justify-end items-center gap-3 w-full md:w-auto">

            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama === 'KEPEGAWAIAN')
                <!-- EXPORT -->
                <div class="relative group">
                    <a href="{{ route('datakaryawan.export') }}" target="_blank"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                        <i class="fas fa-file-excel mr-1"></i> Export
                    </a>
                    <a href="{{ route('datakaryawan.export') }}" target="_blank"
                        class="sm:hidden flex items-center justify-center w-10 h-10 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                        aria-label="Export" data-tooltip-target="tooltip-export">
                        <i class="fas fa-file-excel"></i>
                    </a>
                    <div id="tooltip-export" role="tooltip"
                        class="absolute z-10 invisible group-hover:visible px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                        Export Karyawan
                    </div>
                </div>

                <!-- IMPORT -->
                <div x-data="{ open: false }" class="relative group">
                    <button @click="open = true"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <button @click="open = true"
                        class="sm:hidden flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition"
                        aria-label="Import" data-tooltip-target="tooltip-import">
                        <i class="fas fa-upload"></i>
                    </button>
                    <div id="tooltip-import" role="tooltip"
                        class="absolute z-10 invisible group-hover:visible px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                        Import Karyawan
                    </div>

                    <!-- Modal Upload -->
                    <div x-show="open" @keydown.escape.window="open = false"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                            <h2 class="text-lg font-semibold mb-4">Import Data Karyawan</h2>
                            <form action="{{ route('datakaryawan.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" id="fileInput" accept=".xlsx"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                                <p class="text-sm text-gray-500 mt-1">Hanya file <strong>template_karyawan.xlsx</strong>
                                    yang diterima</p>
                                <div class="mt-4 flex justify-end gap-2">
                                    <button type="button" @click="open = false"
                                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SEARCH -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                class="flex-1 md:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <!-- TAMBAH -->
            @can('create-data-karyawan')
                <div class="relative group">
                    <!-- Desktop -->
                    <a href="{{ route('datakaryawan.create') }}"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                        <i class="fa fa-plus mr-1"></i> Tambah Karyawan
                    </a>
                    <!-- Mobile -->
                    <a href="{{ route('datakaryawan.create') }}"
                        class="sm:hidden flex items-center justify-center w-10 h-10 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                        aria-label="Tambah" data-tooltip-target="tooltip-tambah">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div id="tooltip-tambah" role="tooltip"
                        class="absolute z-10 invisible group-hover:visible px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                        Tambah Karyawan
                    </div>
                </div>
            @endcan

        </div>
    </div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">NIP</th>
                    <th scope="col" class="px-6 py-3">Alamat</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Unit</th>
                    <th scope="col" class="px-6 py-3">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">{{ $user->nip ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->alamat ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if ($user->kategorijabatan)
                                {{ $user->kategorijabatan->nama }}
                                @if ($user->kategorifungsional || $user->kategoriumum)
                                    ({{ $user->kategorifungsional?->nama }}{{ $user->kategorifungsional && $user->kategoriumum ? ' + ' : '' }}{{ $user->kategoriumum?->nama }})
                                @endif
                            @elseif ($user->kategorifungsional || $user->kategoriumum)
                                {{ $user->kategorifungsional?->nama }}{{ $user->kategorifungsional && $user->kategoriumum ? ' + ' : '' }}{{ $user->kategoriumum?->nama }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $user->unitKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @can('detail-data-karyawan')
                                <a href="{{ route('detailkaryawan.show', ['detailkaryawan' => $user->id]) }}"
                                    class="bg-success-700 text-white font-medium rounded-md px-3 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-6 py-4">Tidak ada data Karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex gap-2 justify-center items-center">
        {{-- Previous Page Link --}}
        @if (!$users->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        {{-- Pagination Numbers --}}
        @php
            $totalPages = $users->lastPage();
            $currentPage = $users->currentPage();
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
        @if ($users->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
