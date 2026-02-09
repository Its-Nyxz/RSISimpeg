<div class="mb-4">
    <!-- FILTER -->
    <div class="flex flex-col md:flex-row justify-between items-center py-4 mb-4 gap-4">
        <h1 class="text-2xl font-bold text-success-900 w-full md:w-auto text-left">Data Karyawan</h1>

        <div class="flex flex-col xl:flex-row w-full md:w-auto items-stretch xl:items-center gap-3">
            
            <!-- Filters Group -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <select wire:model.live="selectedUserAktif"
                    class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                    <option value="1">Aktif</option>
                    <option value="0">Non Aktif</option>
                </select>

                @if (auth()->user()->hasRole(['Super Admin', 'Administrator']) || auth()->user()->unitKerja->id == 87  || auth()->user()->can('create-data-karyawan'))
                    <select wire:model.live="selectedUnit"
                        class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                        <option value="">-- Pilih Unit --</option>
                        @foreach ($units as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                @endif

                <select wire:model.live="selectedJenisKaryawan"
                    class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                    <option value="">-- Semua Jenis Karyawan --</option>
                    @foreach ($jenisKaryawans as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions Group -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto items-stretch sm:items-center">

                @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->id === 87 || auth()->user()->can('create-data-karyawan')  )
                    <!-- EXPORT -->
                    <div class="relative group w-full sm:w-auto">
                        <a href="{{ route('datakaryawan.export') }}" target="_blank"
                            class="flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm rounded-lg font-medium bg-success-100 text-success-900 override:bg-success-100 hover:bg-success-600 hover:text-white transition duration-200 ease-in-out shadow-sm border border-transparent hover:shadow-md">
                            <i class="fas fa-file-excel mr-2"></i> Export
                        </a>
                        <!-- Tooltip -->
                        <div role="tooltip"
                            class="absolute z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-300 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm whitespace-nowrap pointer-events-none">
                            Export Karyawan
                            <div class="tooltip-arrow absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>

                    <!-- IMPORT -->
                    <div x-data="{ open: false }" class="relative group w-full sm:w-auto">
                        <button @click="open = true"
                            class="flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm rounded-lg font-medium bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition duration-200 ease-in-out shadow-sm border border-transparent hover:shadow-md">
                            <i class="fas fa-upload mr-2"></i> Import
                        </button>
                         <!-- Tooltip -->
                         <div role="tooltip"
                            class="absolute z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-300 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm whitespace-nowrap pointer-events-none">
                            Import Karyawan
                            <div class="tooltip-arrow absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                        </div>

                        <!-- Modal Upload -->
                        <div x-show="open" @keydown.escape.window="open = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg" @click.away="open = false">
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
                <div class="w-full sm:w-64 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                        class="w-full rounded-lg pl-10 pr-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 shadow-sm transition duration-200 ease-in-out" />
                </div>

                <!-- TAMBAH -->
                @can('create-data-karyawan')
                    <div class="relative group w-full sm:w-auto">
                        <a href="{{ route('datakaryawan.create') }}"
                            class="flex items-center justify-center w-full sm:w-auto px-5 py-2.5 text-sm rounded-lg font-medium bg-success-600 text-white hover:bg-success-700 hover:shadow-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                            <i class="fa fa-plus mr-2"></i> Tambah
                        </a>
                        <!-- Tooltip -->
                        <div role="tooltip"
                            class="absolute z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-300 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm whitespace-nowrap pointer-events-none">
                            Tambah Karyawan
                            <div class="tooltip-arrow absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                @endcan

            </div>
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
