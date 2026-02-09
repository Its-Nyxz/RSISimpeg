<div>
    <h2 class="text-xl sm:text-2xl font-bold mb-4 px-2 sm:px-0">{{ $judul }}</h2>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 mx-2 sm:mx-0">
        <table class="w-full text-xs sm:text-sm text-center bg-white text-gray-700">
            <thead class="text-xs sm:text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    @if ($tipe != 'tukar_jadwal')
                        <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">
                            Jenis {{ $tipe == 'cuti' ? ' Cuti' : ($tipe == 'ijin' ? 'Izin' : 'Tukar Jadwal') }}
                        </th>
                    @endif
                    @if ($tipe == 'tukar_jadwal')
                        <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">Tanggal Pergantian</th>
                    @else
                        <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">Tanggal Mulai</th>
                        <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">Tanggal Selesai</th>
                    @endif
                    @if (!$tipe == 'tukar_jadwal')
                        <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">Jumlah Hari</th>
                    @endif
                    <th class="border border-gray-300 p-2 sm:p-3 min-w-[150px]">Alasan</th>
                    @if ($tipe === 'tukar_jadwal')
                        <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">Shift</th>
                    @endif
                    <th scope="col" class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap">Status</th>
                    <th class="border border-gray-300 p-2 sm:p-3 whitespace-nowrap sticky right-0 bg-success-400 z-10 shadow-l">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataPengajuan as $pengajuan)
                    <tr class="hover:bg-success-300 transition duration-150">
                        @if ($tipe != 'tukar_jadwal')
                            <td class=" border border-gray-300 p-1.5 sm:p-3">
                                @if ($tipe == 'ijin')
                                    {{ $pengajuan->jenisIzin->nama_izin ?? '-' }}
                                @elseif($tipe == 'cuti')
                                    {{ $pengajuan->jenisCuti->nama_cuti ?? '-' }}
                                @endif
                            </td>
                        @endif
                        @if ($tipe == 'tukar_jadwal')
                            <td class="border border-gray-300 p-1.5 sm:p-3 whitespace-nowrap">{{ formatDate($pengajuan->tanggal) ?? '-' }}
                            </td>
                        @else
                            <td class="border border-gray-300 p-1.5 sm:p-3 whitespace-nowrap">{{ formatDate($pengajuan->tanggal_mulai) ?? '-' }}
                            </td>
                            <td class="border border-gray-300 p-1.5 sm:p-3 whitespace-nowrap">{{ formatDate($pengajuan->tanggal_selesai) ?? '-' }}
                            </td>
                        @endif
                        @if (!$tipe == 'tukar_jadwal')
                            <td class="border border-gray-300 p-1.5 sm:p-3">{{ $pengajuan->jumlah_hari ?? '-' }}</td>
                        @endif
                        <td class="text-left border border-gray-300 p-1.5 sm:p-3 min-w-[150px]">{{ $pengajuan->keterangan ?? '-' }}</td>
                        @if ($tipe === 'tukar_jadwal')
                            <td class="border border-gray-300 p-1.5 sm:p-3 whitespace-nowrap">
                                {{ $pengajuan->shift->nama_shift ?? '-' }}
                            </td>
                        @endif
                        @if ($tipe == 'ijin')
                            <td
                                class="border border-gray-300 p-1.5 sm:p-3 font-extrabold whitespace-nowrap {{ $pengajuan->status_izin_id == 1 ? 'text-success-900' : ($pengajuan->status_izin_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                                {{ $pengajuan->statusIzin->nama_status ?? '-' }}
                            </td>
                        @elseif($tipe == 'cuti')
                            <td
                                class="border border-gray-300 p-1.5 sm:p-3 font-extrabold whitespace-nowrap {{ $pengajuan->status_cuti_id == 1 ? 'text-success-900' : ($pengajuan->status_cuti_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                                {{ $pengajuan->statusCuti->nama_status ?? '-' }}
                            </td>
                        @else
                            <td
                                class="border border-gray-300 p-2 font-extrabold whitespace-nowrap
                                {{ is_null($pengajuan->is_approved) ? 'text-gray-900' : ($pengajuan->is_approved ? 'text-success-900' : 'text-red-900') }}">
                                {{ is_null($pengajuan->is_approved) ? 'Menunggu' : ($pengajuan->is_approved ? 'Disetujui' : 'Ditolak') }}
                            </td>
                        @endif
                        <td class="border border-gray-300 p-1.5 sm:p-3 sticky right-0 bg-white group-hover:bg-success-300 z-10 shadow-l">
                            @if (!$tipe == 'tukar_jadwal')
                                @if ($pengajuan->status_izin_id == 3 || $pengajuan->status_izin_id == 3)
                                    <button
                                        onclick="confirmAlert('Ingin menghapus data ini?', 'Ya, Hapus!', () => @this.call('delete', {{ $pengajuan->id }}, '{{ $this->tipe }}'))"
                                        class="text-success-900 px-2 py-1.5 sm:px-3 sm:py-2 rounded-md border hover:bg-slate-300 relative group bg-white/50">
                                        <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                                        <div id="tooltip-destroy-{{ $pengajuan->id }}"
                                            class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-xs sm:text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md whitespace-nowrap right-0">
                                            Hapus
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </button>
                                @else
                                    -
                                @endif
                            @else
                                <button
                                    onclick="confirmAlert('Ingin menghapus data ini?', 'Ya, Hapus!', () => @this.call('delete', {{ $pengajuan->id }}, '{{ $this->tipe }}'))"
                                    class="text-success-900 px-2 py-1.5 sm:px-3 sm:py-2 rounded-md border hover:bg-slate-300 relative group bg-white/50">
                                    <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                                    <div id="tooltip-tukarjadwal-{{ $pengajuan->id }}"
                                        class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-xs sm:text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md whitespace-nowrap right-0">
                                        Hapus
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tipe === 'tukar_jadwal' ? 8 : 7 }}" class="text-center py-4 text-gray-500 italic">
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