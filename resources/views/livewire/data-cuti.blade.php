<div x-data="{ open: false }" class="relative">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Approval Cuti</h1>
    </div>

    <!-- Tabel Data Cuti Karyawan -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">No.</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Tanggal Mulai</th>
                    <th scope="col" class="px-6 py-3">Tanggal Selesai</th>
                    <th scope="col" class="px-6 py-3">Jumlah Hari</th>
                    <th scope="col" class="px-6 py-3">Jenis Cuti</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $cuti)
                    <tr
                        class="{{ $cuti->status_cuti_id == 2 ? 'bg-red-200' : 'odd:bg-success-50 even:bg-success-100' }} border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4"> {{ $cuti->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $cuti->user->kategorijabatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($cuti->tanggal_mulai) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($cuti->tanggal_selesai) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $cuti->jumlah_hari ?? '-' }} Hari</td>
                        <td class="px-6 py-4">{{ $cuti->jeniscuti->nama_cuti ?? '-' }}</td>
                        <td
                            class="px-6 py-4 font-extrabold whitespace-nowrap {{ $cuti->status_cuti_id == 1 ? 'text-success-900' : ($cuti->status_cuti_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                            {{ $cuti->statusCuti->nama_status ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if ($cuti->status_cuti_id == 3 || ($isKepegawaian && $cuti->status_cuti_id == 4))
                                    <button
                                        onclick="confirmAlert('Ingin menyetujui cuti ini?', 'Ya, Setujui!', () => @this.call('approveCuti', {{ $cuti->id }}, {{ $cuti->user->id }}))"
                                        class="bg-success-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i> Disetujui
                                    </button>

                                    <button
                                        onclick="confirmRejectWithReason('Ingin menolak cuti ini?', 'Ya, Tolak!', (reason) => @this.call('rejectCuti', {{ $cuti->id }}, {{ $cuti->user->id }}, reason))"
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
                        <td colspan="8" class="text-center px-6 py-4">Tidak ada data Cuti Karyawan.</td>
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
