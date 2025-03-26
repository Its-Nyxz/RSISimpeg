<div x-data="{ open: false }" class="relative">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Approval Cuti</h1>
        <div class="relative">

            <!-- Modal Dropdown -->
            {{-- <div x-show="open" x-transition
                class="absolute right-0 mt-2 w-96 bg-green-100 p-6 rounded-lg shadow-lg z-50">
                <h2 class="text-lg font-bold mb-3 text-center">CUTI APPROVAL</h2>

                @forelse ($users as $user)
                    <!-- Modal Content -->
                    <div class="grid grid-cols-2 gap-6 text-sm text-gray-700">
                        <div class="font-semibold">Nama</div>
                        <div>: {{ $user->name }}</div>

                        <div class="font-semibold">Jabatan</div>
                        <div>: {{ $user->kategorijabatan->nama ?? '-' }}</div>

                        <div class="font-semibold">Tempat/TGL Lahir</div>
                        <div>: {{ $user->tempat_lahir ?? '-' }}, {{ $user->tgl_lahir ?? '-' }}</div>

                        <div class="font-semibold">Alasan Cuti</div>
                        <div>: {{ $user->cutiKaryawan->first()->alasan_cuti ?? '-' }}</div>

                        <div class="font-semibold">Pengambilan Cuti</div>
                        <div>: {{ $user->cutiKaryawan->first()->pengambilan_cuti ?? '-' }} Hari</div>

                        <div class="font-semibold">Sisa Cuti</div>
                        <div>: {{ $user->cutiKaryawan->first()->sisa_cuti ?? '-' }} Hari</div>
                    </div>

                    <!-- Modal Buttons -->
                    <div class="flex justify-end mt-4">
                        <button @click="open = false"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Tutup</button>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <i class="fa-solid fa-check"></i> Approve
                        </button>
                    </div>
                @empty
                    <p class="text-gray-600 text-center">Tidak ada data user.</p>
                @endforelse
            </div> --}}
        </div>
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
                    <th scope="col" class="px-6 py-3">Jenis Cuti</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $cuti)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>
                    
                        <td class="px-6 py-4"> {{ $cuti->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $cuti->user->kategorijabatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($cuti->tanggal_mulai) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($cuti->tanggal_selesai) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $cuti->jeniscuti->nama_cuti ?? '-' }}</td>
                        <td
                            class="px-6 py-4 font-extrabold whitespace-nowrap {{ $cuti->status_cuti_id == 1 ? 'text-green-900' : ($cuti->status_cuti_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                            {{ $cuti->statusCuti->nama_status ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if ($cuti->status_cuti_id == 3)
                                    <button wire:click="approveCuti({{ $cuti->id }}, {{ $cuti->user->id }})"
                                        class="bg-green-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i> Disetujui
                                    </button>

                                    <button wire:click="rejectCuti({{ $cuti->id }}, {{ $cuti->user->id }})"
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
