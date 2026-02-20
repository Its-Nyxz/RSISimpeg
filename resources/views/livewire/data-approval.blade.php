<div class="mb-4">
    <div class="py-2 mb-3">
        <h1 class="text-xl md:text-2xl font-bold text-success-900 text-center md:text-left">History Approval</h1>
    </div>

    <div class="flex flex-col gap-4 mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:flex md:flex-wrap gap-2 md:gap-3">
            <select wire:model.live="hari"
                class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm bg-white">
                <option value="">-- Tgl --</option>
                @foreach (range(1, 31) as $d)
                    <option value="{{ $d }}">{{ sprintf('%02d', $d) }}</option>
                @endforeach
            </select>

            <select wire:model.live="bulan"
                class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm bg-white">
                <option value="">-- Pilih Bulan --</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">
                        {{ \Carbon\Carbon::createFromFormat('!m', $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="tahun"
                class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm bg-white">
                <option value="">-- Pilih Tahun --</option>
                @foreach (range(now()->year - 2, now()->year + 2) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>

            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->id == 87)
                <select wire:model.live="selectedUnit"
                    class="col-span-2 sm:col-span-1 rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm bg-white">
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="w-full md:w-1/3">
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 text-sm" />
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-success-300">
        <table class="w-full text-sm text-center text-gray-700 min-w-[1000px]">
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
                    <th scope="col" class="px-6 py-3 border-t border-success-500/20">Nama Karyawan</th>
                    <th scope="col" class="px-6 py-3 border-t border-success-500/20">Unit Kerja</th>
                    <th scope="col" class="px-6 py-3 border-t border-success-500/20">Tgl Cuti</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayats as $row)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-200 transition-colors">
                        <td class="px-6 py-4 font-medium text-success-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $row->approve_at ? \Carbon\Carbon::parse($row->approve_at)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $row->cuti->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $row->cuti->user->unitKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $row->cuti->tanggal_mulai ? \Carbon\Carbon::parse($row->cuti->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $row->approver->name ?? '-' }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            @if ($row->status_approval == 'disetujui_final')
                                <i class="fa-solid fa-check-double text-green-600 text-lg" title="Disetujui Final"></i>
                            @elseif($row->status_approval == 'disetujui_intermediate')
                                <i class="fa-solid fa-check text-green-500 text-lg" title="Disetujui"></i>
                            @elseif($row->status_approval == 'ditolak')
                                <i class="fa-solid fa-circle-xmark text-red-600 text-lg" title="Ditolak"></i>
                            @else
                                <i class="fa-solid fa-clock text-gray-400 text-lg" title="{{ ucwords(str_replace('_', ' ', $row->status_approval)) }}"></i>
                            @endif
                        </td>

                        <td class="px-6 py-4 italic text-gray-500 min-w-[150px]">
                            "{{ $row->catatan ?? 'Tidak ada catatan' }}"
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="openDetail({{ $row->cuti_id }})" class="font-semibold text-gray-900 cursor-pointer hover:text-success-700 hover:underline">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center bg-white text-gray-500 italic">
                            Belum ada riwayat approval yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-wrap gap-2 justify-center items-center">
        @if (!$riwayats->onFirstPage())
            <button wire:click="previousPage" class="px-3 py-1 bg-success-100 hover:bg-success-600 hover:text-white text-success-900 rounded-md text-sm transition-colors">
                &laquo; Sblm
            </button>
        @endif

        <div class="flex flex-wrap gap-1 justify-center">
            @for ($page = max($riwayats->currentPage() - 2, 1); $page <= min($riwayats->currentPage() + 2, $riwayats->lastPage()); $page++)
                <button wire:click="gotoPage({{ $page }})" 
                    class="px-3 py-1 rounded-md text-sm {{ $page == $riwayats->currentPage() ? 'bg-success-600 text-white' : 'bg-success-100 text-success-900 hover:bg-success-200' }}">
                    {{ $page }}
                </button>
            @endfor
        </div>

        @if ($riwayats->hasMorePages())
            <button wire:click="nextPage" class="px-3 py-1 bg-success-100 hover:bg-success-600 hover:text-white text-success-900 rounded-md text-sm transition-colors">
                Lanjut &raquo;
            </button>
        @endif
    </div>

    @if ($showModalDetailCuti)
        <div class="fixed inset-0 z-50 bg-gray-600/40 backdrop-blur-sm flex items-center justify-center px-4 py-6">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg relative max-h-full overflow-hidden flex flex-col border border-success-400">
                <div class="px-5 py-3 bg-success-400 flex justify-between items-center">
                    <h2 class="text-sm font-bold uppercase text-success-900 flex items-center gap-2">
                        <i class="fa-solid fa-file-lines"></i> Detail & History
                    </h2>
                    <button wire:click="$set('showModalDetailCuti', false)" class="text-success-900 hover:text-red-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-4 overflow-y-auto space-y-4">
                    @if ($detailCuti)
                        <div class="border border-success-200 rounded-md overflow-hidden">
                            <div class="bg-success-50 p-3 border-b border-success-100">
                                <span class="block text-[10px] font-bold text-success-700 uppercase">Karyawan</span>
                                <p class="font-bold text-gray-800">{{ $detailCuti->user->name }}</p>
                            </div>
                            <div class="p-3 bg-white flex justify-between items-center text-sm border-b border-success-100">
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Periode</span>
                                    <p class="font-bold text-success-800">{{ \Carbon\Carbon::parse($detailCuti->tanggal_mulai)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($detailCuti->tanggal_selesai)->format('d/m/y') }}</p>
                                </div>
                                <div class="bg-success-200 text-success-900 px-2 py-1 rounded text-[10px] font-black uppercase">
                                    {{ $detailCuti->jumlah_hari }} Hari
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-[10px] font-bold text-success-800 uppercase flex items-center gap-1">
                                <i class="fa-solid fa-history"></i> Log Persetujuan
                            </h3>
                            <div class="overflow-hidden border border-success-300 rounded-md">
                                <table class="w-full text-[11px] text-center">
                                    <thead class="bg-success-400 text-success-900 uppercase font-bold">
                                        <tr>
                                            <th class="px-2 py-2 w-8">No</th>
                                            <th class="px-2 py-2 text-left">Approver</th>
                                            <th class="px-2 py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detailCuti->riwayatApprovals as $history)
                                            <tr class="{{ $loop->odd ? 'bg-success-50' : 'bg-success-100' }} border-b border-success-200">
                                                <td class="px-2 py-2">{{ $loop->iteration }}</td>
                                                <td class="px-2 py-2 text-left">
                                                    <p class="font-bold leading-none">{{ $history->approver->name ?? 'System' }}</p>
                                                    <span class="text-[9px] text-gray-400">{{ $history->approve_at ? \Carbon\Carbon::parse($history->approve_at)->format('d/m/y H:i') : '-' }}</span>
                                                </td>
                                                <td class="px-2 py-2">
                                                    @if ($history->status_approval == 'disetujui_final')
                                                        <i class="fa-solid fa-check-double text-green-600 text-lg"></i>
                                                    @elseif($history->status_approval == 'disetujui_intermediate')
                                                        <i class="fa-solid fa-check text-green-500 text-lg"></i>
                                                    @elseif($history->status_approval == 'ditolak')
                                                        <i class="fa-solid fa-circle-xmark text-red-600 text-lg"></i>
                                                    @else
                                                        <i class="fa-solid fa-clock text-gray-400 text-lg"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button wire:click="$set('showModalDetailCuti', false)" class="bg-success-600 text-white px-5 py-1.5 rounded text-[11px] font-bold uppercase shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>