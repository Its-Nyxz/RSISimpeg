<div>
    <h2 class="text-2xl font-bold mb-4">{{ $judul }}</h2>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th class="border border-gray-300 p-2">Jenis Izin</th>
                    <th class="border border-gray-300 p-2">Tanggal Mulai</th>
                    <th class="border border-gray-300 p-2">Tanggal Selesai</th>
                    <th class="border border-gray-300 p-2">Jumlah Hari</th>
                    <th class="border border-gray-300 p-2">Alasan</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    @if ($tipe === 'tukar_jadwal')
                        <th class="border border-gray-300 p-2">Shift</th>
                    @endif
                    <th class="border border-gray-300 p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataPengajuan as $pengajuan)
                    <tr class="hover:bg-success-300">
                        <td class="text-start border border-gray-300 p-2">{{ $pengajuan->jenisIzin->nama_izin ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2">{{ formatDate($pengajuan->tanggal_mulai) ?? '-' }}</td>
                        <td class="border border-gray-300 p-2">{{ formatDate($pengajuan->tanggal_selesai) ?? '-' }}</td>
                        <td class="border border-gray-300 p-2">{{ $pengajuan->jumlah_hari ?? '-' }}</td>
                        <td class="text-start border border-gray-300 p-2">{{ $pengajuan->keterangan ?? '-' }}</td>
                        <td
                            class="border border-gray-300 p-2 font-extrabold whitespace-nowrap {{ $pengajuan->status_izin_id == 1 ? 'text-green-900' : ($pengajuan->status_izin_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                            {{ $pengajuan->statusIzin->nama_status ?? '-' }}
                        </td>
                        @if ($tipe === 'tukar_jadwal')
                            <td class="border border-gray-300 p-2">
                                {{ $pengajuan->shift ?? '-' }}
                            </td>
                        @endif
                        <td class="border border-gray-300 p-2">
                            @if ($pengajuan->status_izin_id == 3)
                                <button wire:click="delete({{ $pengajuan->id }}, '{{ $this->tipe }}')"
                                    class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tipe === 'tukar_jadwal' ? 6 : 5 }}" class="text-center py-4">
                            Tidak ada pengajuan {{ $tipe }} ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Navigasi Pagination -->
    <div class="mt-4 flex gap-2 justify-center items-center">
        @if ($dataPengajuan->onFirstPage() == false)
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        @php
            $totalPages = $dataPengajuan->lastPage();
            $currentPage = $dataPengajuan->currentPage();
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

        @if ($dataPengajuan->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
