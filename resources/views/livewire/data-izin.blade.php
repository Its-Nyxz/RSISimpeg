<div x-data="{ open: false }" class="relative mb-6">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Approval Izin</h1>
    </div>

    <!-- Tabel Data izin Karyawan -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Tanggal Mulai</th>
                    <th scope="col" class="px-6 py-3">Tanggal Selesai</th>
                    <th scope="col" class="px-6 py-3">Jumlah Hari</th>
                    <th scope="col" class="px-6 py-3">Jenis Izin</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($userIzin as $izin)
                    <tr
                        class="{{ $izin->status_izin_id == 2 ? 'bg-red-200' : 'odd:bg-success-50 even:bg-success-100' }} border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $izin->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $izin->user->kategorijabatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($izin->tanggal_mulai) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ formatDate($izin->tanggal_selesai) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $izin->jumlah_hari ?? '-' }} Hari</td>
                        <td class="px-6 py-4">{{ $izin->jenisIzin->nama_izin ?? '-' }}</td>
                        <td
                            class="px-6 py-4 font-extrabold whitespace-nowrap {{ $izin->status_izin_id == 1 ? 'text-green-900' : ($izin->status_izin_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                            {{ $izin->statusIzin->nama_status ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if ($izin->status_izin_id == 3)
                                    <button type="button"
                                        onclick="confirmAlert('Ingin menyetujui izin ini?', 'Ya, Setujui!', () => @this.call('approveIzin', {{ $izin->id }}, {{ $izin->user->id }}))"
                                        class="bg-green-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i> Disetujui
                                    </button>
                                    <button
                                        onclick="confirmAlert('Ingin menolak izin ini?', 'Ya, Tolak!', () => @this.call('rejectIzin', {{ $izin->id }}, {{ $izin->user->id }}))"
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

    <!-- Navigasi Pagination -->
    <div class="mt-4 flex gap-2 justify-center items-center">
        @if ($userIzin->onFirstPage() == false)
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        @php
            $totalPages = $userIzin->lastPage();
            $currentPage = $userIzin->currentPage();
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

        @if ($userIzin->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
    @if (session()->has('message'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('message') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
</div>
