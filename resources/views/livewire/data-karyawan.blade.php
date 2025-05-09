<div class="mb-4">
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Data Karyawan</h1>
    </div>
    <div class="flex flex-col md:flex-row justify-between items-center md:gap-4 space-y-3 md:space-y-0 mb-3">
        <!-- Bagian Dropdown -->
        <div id="1" class="flex space-x-4 w-full md:w-auto justify-center md:justify-start">
            <select wire:model.live="selectedUserAktif"
                class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="1">Aktif</option>
                <option value="0">Non Aktif</option>
            </select>
            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEPEGAWAIAN')
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

        <!-- Bagian Search dan Tambah (Tetap dalam satu baris) -->
        <div id="2" class="flex w-full md:w-auto items-center gap-3 md:gap-4">
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                class="flex-1 md:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            @can('create-data-karyawan')
                <a href="{{ route('datakaryawan.create') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 whitespace-nowrap">
                    + Tambah Karyawan
                </a>
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
                            {{ $user->kategorifungsional && $user->kategorijabatan
                                ? $user->kategorijabatan->nama . ' + ' . $user->kategorifungsional->nama
                                : $user->kategorijabatan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $user->unitKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @can('detail-data-karyawan')
                                <a href="{{ route('detailkaryawan.show', ['detailkaryawan' => $user->id]) }}"
                                    class="bg-green-700 text-white font-medium rounded-md px-3 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
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
