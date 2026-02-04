<div class="mb-4">
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">History Approval</h1>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-3">

        <div class="flex flex-wrap gap-3 justify-center md:justify-start w-full md:w-auto">
            <select wire:model.live="hari"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm">
                <option value="">-- Tgl --</option>
                @foreach (range(1, 31) as $d)
                    <option value="{{ $d }}">{{ sprintf('%02d', $d) }}</option>
                @endforeach
            </select>

            <select wire:model.live="bulan"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm">
                <option value="">-- Pilih Bulan --</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">
                        {{ \Carbon\Carbon::createFromFormat('!m', $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="tahun"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm">
                <option value="">-- Pilih Tahun --</option>
                @foreach (range(now()->year - 2, now()->year + 2) as $y)
                    {{-- Range disesuaikan agar lebih fleksibel --}}
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>

            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->id == 87)
                <select wire:model.live="selectedUnit"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm">
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <!-- Search & Tambah -->
        <div class="flex items-center gap-3 w-full md:w-auto">

            <!-- Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                class="flex-1 md:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
        </div>
    </div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3" rowspan="2">No</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Tgl Approval</th>
                    <th scope="col" class="px-6 py-3" colspan="3">Informasi Pengaju</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Approver</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Status</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Catatan</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Karyawan</th>
                    <th scope="col" class="px-6 py-3">Unit Kerja</th>
                    <th scope="col" class="px-6 py-3">Tgl Cuti</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayats as  $row)
                    <tr
                        class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-200 transition-colors">
                        <td class="px-6 py-4 font-medium text-success-900">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $row->approve_at ? \Carbon\Carbon::parse($row->approve_at)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            {{ $row->cuti->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->cuti->user->unitKerja->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->cuti->tanggal_mulai ? \Carbon\Carbon::parse($row->cuti->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->approver->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($row->status_approval == 'disetujui_final')
                                <span
                                    class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-green-800 bg-green-200 rounded-lg border border-green-300">
                                    <i class="fa-solid fa-check-double mr-1"></i> Disetujui
                                </span>
                            @elseif($row->status_approval == 'disetujui_intermediate')
                                <span
                                    class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-green-800 bg-green-200 rounded-lg border border-green-300">
                                    <i class="fa-solid fa-check mr-1"></i> Disetujui
                                </span>
                            @elseif($row->status_approval == 'ditolak')
                                <span
                                    class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-red-800 bg-red-200 rounded-lg border border-red-300">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak
                                </span>
                            @else
                                <span
                                    class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-gray-800 bg-gray-200 rounded-lg border border-gray-300">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    {{ ucwords(str_replace('_', ' ', $row->status_approval)) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 italic text-gray-500">
                            "{{ $row->catatan ?? 'Tidak ada catatan' }}"
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 cursor-pointer hover:text-success-700 hover:underline"
                            wire:click="openDetail({{ $row->cuti_id }})">
                            <i class="fa-solid fa-eye"></i>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center bg-white text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-inbox fa-3x mb-3 text-gray-300"></i>
                                <p>Belum ada riwayat approval yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex gap-2 justify-center items-center">
        {{-- Previous Page Link --}}
        @if (!$riwayats->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        {{-- Pagination Numbers --}}
        @php
            $totalPages = $riwayats->lastPage();
            $currentPage = $riwayats->currentPage();
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
        @if ($riwayats->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>


    @if ($showModalDetailCuti)
        <div class="fixed inset-0 z-50 bg-gray-600/40 backdrop-blur-sm flex items-center justify-center px-4">

            <div
                class="bg-white rounded-lg shadow-xl w-full max-w-lg relative max-h-[90vh] overflow-hidden flex flex-col border border-success-400">

                <div class="px-5 py-3 bg-success-400 flex justify-between items-center">
                    <div class="flex items-center gap-2 text-success-900">
                        <i class="fa-solid fa-file-lines"></i>
                        <h2 class="text-sm font-bold uppercase tracking-tight">Detail & History Approval</h2>
                    </div>
                    <button wire:click="$set('showModalDetailCuti', false)"
                        class="text-success-900 hover:text-red-600 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-5 overflow-y-auto space-y-4 bg-white text-[13px]">
                    @if ($detailCuti)

                        <div class="border border-success-200 rounded-md overflow-hidden shadow-sm">
                            <div class="bg-success-50 p-3 border-b border-success-100">
                                <span class="block text-[9px] font-bold text-success-700 uppercase">Karyawan</span>
                                <p class="font-bold text-gray-800">{{ $detailCuti->user->name }}</p>
                                <p class="text-[11px] text-gray-500">{{ $detailCuti->user->unitKerja->nama ?? '-' }}
                                </p>
                            </div>

                            <div class="p-3 bg-white flex justify-between items-center border-b border-success-100">
                                <div>
                                    <span class="block text-[9px] font-bold text-gray-400 uppercase">Periode
                                        Cuti</span>
                                    <p class="font-bold text-success-800">
                                        {{ \Carbon\Carbon::parse($detailCuti->tanggal_mulai)->format('d/m/y') }}
                                        <span class="text-gray-300 mx-1">→</span>
                                        {{ \Carbon\Carbon::parse($detailCuti->tanggal_selesai)->format('d/m/y') }}
                                    </p>
                                </div>
                                <div class="bg-success-200 text-success-900 px-2 py-1 rounded text-[10px] font-black">
                                    {{ $detailCuti->jumlah_hari }} HARI
                                </div>
                            </div>

                            <div class="p-3 bg-white">
                                <span class="block text-[9px] font-bold text-gray-400 uppercase">Alasan
                                    ({{ $detailCuti->jenisCuti->nama_cuti }})</span>
                                <p class="text-gray-600 italic">"{{ $detailCuti->keterangan ?? '-' }}"</p>
                            </div>
                        </div>

                        <div class="space-y-2 pt-1">
                            <h3 class="text-[10px] font-bold text-success-800 uppercase flex items-center gap-1">
                                <i class="fa-solid fa-history"></i> Log Persetujuan
                            </h3>
                            <div class="overflow-hidden border border-success-300 rounded-md shadow-sm text-center">
                                <table class="w-full text-[11px] text-gray-700">
                                    <thead class="bg-success-400 text-success-900 uppercase font-bold">
                                        <tr>
                                            <th class="px-2 py-2 border-b border-success-300 w-8 text-center">No</th>
                                            <th class="px-2 py-2 border-b border-success-300 text-left">Approver</th>
                                            <th class="px-2 py-2 border-b border-success-300">Status</th>
                                            <th class="px-2 py-2 border-b border-success-300 text-left">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($detailCuti->riwayatApprovals as $index => $history)
                                            <tr
                                                class="{{ $loop->odd ? 'bg-success-50' : 'bg-success-100' }} border-b border-success-200 hover:bg-success-200 transition-colors">
                                                <td class="px-2 py-2 font-medium text-success-900">
                                                    {{ $loop->iteration }}</td>
                                                <td class="px-2 py-2 text-left">
                                                    <p class="font-bold leading-none">
                                                        {{ $history->approver->name ?? 'System' }}</p>
                                                    <span
                                                        class="text-[9px] text-gray-400">{{ $history->approve_at ? \Carbon\Carbon::parse($history->approve_at)->format('d/m/y H:i') : '-' }}</span>
                                                </td>
                                                <td class="px-2 py-2">
                                                    @if ($history->status_approval == 'disetujui_final')
                                                        <span
                                                            class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-green-800 bg-green-200 rounded-lg border border-green-300">
                                                            <i class="fa-solid fa-check-double mr-1"></i>
                                                        </span>
                                                    @elseif($history->status_approval == 'disetujui_intermediate')
                                                        <span
                                                            class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-green-800 bg-green-200 rounded-lg border border-green-300">
                                                            <i class="fa-solid fa-check mr-1"></i>
                                                        </span>
                                                    @elseif($history->status_approval == 'ditolak')
                                                        <span
                                                            class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-red-800 bg-red-200 rounded-lg border border-red-300">
                                                            <i class="fa-solid fa-circle-xmark mr-1"></i>
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-gray-800 bg-gray-200 rounded-lg border border-gray-300">
                                                            <i class="fa-solid fa-clock mr-1"></i>
                                                            {{ ucwords(str_replace('_', ' ', $history->status_approval)) }}
                                                        </span>
                                                    @endif

                                                </td>
                                                <td
                                                    class="px-2 py-2 text-left italic text-gray-500 text-[10px] leading-tight">
                                                    {{ $history->catatan ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-4 text-gray-400 italic">No history.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button wire:click="$set('showModalDetailCuti', false)"
                        class="bg-success-600 hover:bg-success-700 text-white px-5 py-1.5 rounded text-[11px] font-bold uppercase tracking-widest shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
