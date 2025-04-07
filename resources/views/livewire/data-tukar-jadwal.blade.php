<div x-data="{ open: false }" class="relative">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Approval Tukar Jadwal</h1>
    </div>

    <!-- Tabel Data Tukar Jadwal Karyawan -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">No.</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Tanggal Pergantian</th>
                    <th scope="col" class="px-6 py-3">Alasan</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $tukar)
                    <tr
                        class="
                {{ is_null($tukar->is_approved)
                    ? 'bg-gray-100'
                    : ($tukar->is_approved == 0
                        ? 'bg-red-200'
                        : 'odd:bg-success-50 even:bg-success-100') }}
                border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4"> {{ $tukar->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $tukar->user->kategorijabatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($tukar->tanggal) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $tukar->keterangan ?? '-' }}</td>
                        <td
                            class="px-6 py-4 font-extrabold whitespace-nowrap
                             {{ is_null($tukar->is_approved) ? 'text-gray-900' : ($tukar->is_approved ? 'text-green-900' : 'text-red-900') }}">
                            {{ is_null($tukar->is_approved) ? 'Menunggu' : ($tukar->is_approved ? 'Disetujui' : 'Ditolak') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if (is_null($tukar->is_approved))
                                    <button
                                        onclick="confirmAlert('Ingin menyetujui Tukar Jadwal ini?', 'Ya, Setujui!', () => @this.call('approveTukar', {{ $tukar->id }}, {{ $tukar->user->id }}))"
                                        class="bg-green-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i> Disetujui
                                    </button>
                                    <button
                                        onclick="confirmAlert('Ingin menolak Tukar Jadwal ini?', 'Ya, Tolak!', () => @this.call('rejectTukar', {{ $tukar->id }}, {{ $tukar->user->id }}))"
                                        class="bg-red-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-xmark"></i> Ditolak
                                    </button>
                                @else
                                    -
                                @endif
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center px-6 py-4">Tidak ada data Tukar Jadwal Karyawan.</td>
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
