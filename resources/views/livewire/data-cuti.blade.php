<div x-data="{ open: false }" class="relative">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Approval Cuti</h1>
        <div class="relative">
            <!-- Tombol Notification -->
            <button @click="open = !open"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 flex items-center gap-2 relative">
                <i class="fa-solid fa-bell"></i> Notification
            </button>

            <!-- Modal Dropdown -->
            <div x-show="open" x-transition 
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
                        <div>: {{ $user->alasan_cuti ?? '-' }}</div>

                        <div class="font-semibold">Pengambilan Cuti</div>
                        <div>: {{ $user->pengambilan_cuti ?? '-' }} Hari</div>

                        <div class="font-semibold">Sisa Cuti</div>
                        <div>: {{ $user->sisa_cuti ?? '-' }} Hari</div>
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
            </div>
        </div>
    </div>

    <!-- Tabel Data Cuti Karyawan -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Awal Masuk</th>
                    <th scope="col" class="px-6 py-3">Lama Bekerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">{{ $user->status ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->kategorijabatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->tgl_penyesuaian ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->masa_kerja ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-6 py-4">Tidak ada data Cuti Karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Navigasi Pagination -->
    <div class="mt-4 flex gap-2 justify-center items-center">
        @if (!$users->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif
        @php
            $totalPages = $users->lastPage();
            $currentPage = $users->currentPage();
            $range = 3;
        @endphp
        @if ($currentPage > $range + 1)
            <button wire:click="gotoPage(1)"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                1
            </button>
            @if ($currentPage > $range + 2)
                <span class="px-2 py-1 text-gray-500">...</span>
            @endif
        @endif
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
        @if ($currentPage < $totalPages - $range)
            @if ($currentPage < $totalPages - $range - 1)
                <span class="px-2 py-1 text-gray-500">...</span>
            @endif
            <button wire:click="gotoPage({{ $totalPages }})"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                {{ $totalPages }}
            </button>
        @endif
        @if ($users->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
