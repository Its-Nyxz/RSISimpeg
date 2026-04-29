<div x-data="{ open: false }" class="relative">
    
    {{-- judul --}}
    <div class="flex justify-between py-2 mb-3">
        {{-- ganti2 judul sesuai route --}}
        <h1 class="text-2xl font-bold text-success-900">
            {{ $isRiwayatCuti ? 'Riwayat Cuti Karyawan' : 'Approval Cuti' }}
        </h1>
    </div>

    {{-- filter --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-3">
        <div class="flex flex-wrap gap-3 justify-center md:justify-start w-full md:w-auto">
            <select wire:model.live="selectedUserAktif" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="1">Aktif</option>
                <option value="0">Non Aktif</option>
            </select>
            
            <livewire:searchable-pillbox wire:model.live="selectedUnit" :options="$units" label-key="nama" placeholder="Cari Unit" />

            <select wire:model.live="selectedJenisKaryawan" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="">Semua Jenis Karyawan</option>
                @foreach ($jenisKaryawans as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
            
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari..."
                class="flex-1 md:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
        </div>
    </div>
    {{-- tampilan selain route /riwayatcuti --}}
    @if (!$isRiwayatCuti)
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-center text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th class="px-6 py-3">No.</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Jabatan</th>
                        <th class="px-6 py-3">Tanggal Mulai</th>
                        <th class="px-6 py-3">Tanggal Selesai</th>
                        <th class="px-6 py-3">Jumlah Hari</th>
                        <th class="px-6 py-3">Jenis Cuti</th>
                        <th class="px-6 py-3">Status</th>
                        @if ($isKepegawaian)
                            <th class="px-6 py-3">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $cuti)
                        <tr class="{{ $cuti->status_cuti_id == 2 ? 'bg-red-200' : 'odd:bg-success-50 even:bg-success-100' }} border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4 font-medium text-success-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $cuti->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $cuti->user->kategorijabatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ formatDate($cuti->tanggal_mulai) ?? '-' }}</td>
                            <td class="px-6 py-4">{{ formatDate($cuti->tanggal_selesai) ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $cuti->jumlah_hari ?? '-' }} Hari</td>
                            <td class="px-6 py-4">{{ $cuti->jeniscuti->nama_cuti ?? '-' }}</td>
                            <td class="px-6 py-4 font-extrabold {{ $cuti->status_cuti_id == 1 ? 'text-success-900' : ($cuti->status_cuti_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                                {{ $cuti->statusCuti->nama_status ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    @if ($cuti->status_cuti_id == 3 || ($isKepegawaian && $cuti->status_cuti_id == 4))
                                        <button onclick="confirmAlert('Setujui cuti?', 'Ya!', () => @this.call('approveCuti', {{ $cuti->id }}, {{ $cuti->user->id }}))"
                                            class="bg-success-600 text-white px-3 py-1 rounded-lg flex items-center gap-2"><i class="fa-solid fa-check"></i> Disetujui</button>
                                        <button onclick="confirmRejectWithReason('Tolak cuti?', 'Ya!', (reason) => @this.call('rejectCuti', {{ $cuti->id }}, {{ $cuti->user->id }}, reason))"
                                            class="bg-red-600 text-white px-3 py-1 rounded-lg flex items-center gap-2"><i class="fa-solid fa-xmark"></i> Ditolak</button>
                                    @else - @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center px-6 py-4">Tidak ada data Approval Cuti.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- pagination --}}
        <div class="mt-4 flex gap-2 justify-center items-center">
            @if ($users && $users->hasPages())
                @php
                    $totalPages = $users->lastPage();
                    $currentPage = $users->currentPage();
                    $range = 2; // Adjust to show more/less buttons
                @endphp
                <button wire:click="previousPage" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200" {{ $users->onFirstPage() ? 'disabled' : '' }}>&laquo; Prev</button>
                @if ($currentPage > $range + 1)
                    <button wire:click="gotoPage(1)" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200">1</button>
                    @if ($currentPage > $range + 2) <span class="px-2 py-1 text-gray-500">...</span> @endif
                @endif
                @for ($page = max(1, $currentPage - $range); $page <= min($totalPages, $currentPage + $range); $page++)
                    <button wire:click="gotoPage({{ $page }})" class="px-2 py-1 {{ $page == $currentPage ? 'bg-success-600 text-white' : 'bg-success-100 text-success-900 hover:bg-success-200' }} rounded-md text-sm">{{ $page }}</button>
                @endfor
                @if ($currentPage < $totalPages - $range)
                    @if ($currentPage < $totalPages - $range - 1) <span class="px-2 py-1 text-gray-500">...</span> @endif
                    <button wire:click="gotoPage({{ $totalPages }})" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200">{{ $totalPages }}</button>
                @endif
                <button wire:click="nextPage" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200" {{ !$users->hasMorePages() ? 'disabled' : '' }}>Next &raquo;</button>
            @endif
        </div>
    @else
    {{-- tampilan route /riwayatcuti --}}
        <div class="mb-2">
            <h2 class="text-xl font-bold text-success-900 bg-transparent px-4 py-2">Karyawan</h2>
            {{-- data karyawan --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-success-200">
                <table class="w-full text-sm text-center text-gray-700">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th class="px-6 py-2">No</th>
                            <th class="px-6 py-2">Nama</th>
                            <th class="px-6 py-2">NIP</th>
                            <th class="px-6 py-2">Jabatan</th>
                            <th class="px-6 py-2">Unit</th>
                            <th class="px-6 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatUsers as $user)
                            <tr class="border-b border-success-300 hover:bg-success-300 transition duration-150 cursor-pointer {{ $user->id == $selectedRiwayatUserId ? 'bg-success-300 font-bold' : 'odd:bg-success-50 even:bg-success-100' }}" 
                                wire:click="selectRiwayatUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                <td class="px-6 py-3">{{ $riwayatUsers->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-3 text-success-900">{{ $user->name }}</td>
                                <td class="px-6 py-3">{{ $user->nip ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $user->kategorijabatan->nama ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $user->unitKerja->nama ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    <button class="bg-success-600 hover:bg-success-700 text-white px-3 py-1.5 rounded-lg text-md transition">
                                        <i class="fa-solid fa-eye text-md"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center px-6 py-4">Tidak ada data Karyawan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex gap-2 justify-center items-center">
                @if ($riwayatUsers && $riwayatUsers->hasPages())
                    @php
                        $totalPagesTop = $riwayatUsers->lastPage();
                        $currentPageTop = $riwayatUsers->currentPage();
                        $rangeTop = 2;
                    @endphp

                    <button wire:click="previousPage('usersPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200" {{ $riwayatUsers->onFirstPage() ? 'disabled' : '' }}>&laquo; Prev</button>

                    @if ($currentPageTop > $rangeTop + 1)
                        <button wire:click="gotoPage(1, 'usersPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200">1</button>
                        @if ($currentPageTop > $rangeTop + 2) <span class="px-2 py-1 text-gray-500">...</span> @endif
                    @endif

                    @for ($page = max(1, $currentPageTop - $rangeTop); $page <= min($totalPagesTop, $currentPageTop + $rangeTop); $page++)
                        <button wire:click="gotoPage({{ $page }}, 'usersPage')" class="px-2 py-1 {{ $page == $currentPageTop ? 'bg-success-600 text-white' : 'bg-success-100 text-success-900 hover:bg-success-200' }} rounded-md text-sm">{{ $page }}</button>
                    @endfor

                    @if ($currentPageTop < $totalPagesTop - $rangeTop)
                        @if ($currentPageTop < $totalPagesTop - $rangeTop - 1) <span class="px-2 py-1 text-gray-500">...</span> @endif
                        <button wire:click="gotoPage({{ $totalPagesTop }}, 'usersPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200">{{ $totalPagesTop }}</button>
                    @endif

                    <button wire:click="nextPage('usersPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm hover:bg-success-200" {{ !$riwayatUsers->hasMorePages() ? 'disabled' : '' }}>Next &raquo;</button>
                @endif
            </div>
        </div>
        {{-- riwayat cuti user selected --}}
        @if ($selectedRiwayatUserId)
            <div class="pt-6 rounded-lg px-2">
                <div class="flex justify-between items-center mb-1">
                    <h2 class="text-xl font-bold text-success-900 bg-transparent px-4 py-2">
                        Riwayat Cuti: <span class="text-blue-700">{{ $selectedRiwayatUserName }}</span>
                    </h2>
                    <button wire:click="closeRiwayatUser" class="flex flex-row items-center bg-gray-50 xt-sm text-red-600 hover:text-gray-50 font-semibold px-3 py-1 border border-red-200 hover:bg-red-600 rounded-lg gap-4">
                        <i class="fa-solid fa-xmark text-xl"></i>
                        {{-- <span class="text-xl font-bold">Tutup</span> --}}
                    </button>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-center text-gray-700">
                        <thead class="text-sm uppercase bg-success-400 text-success-900">
                            <tr>
                                <th class="px-6 py-3">No.</th>
                                <th class="px-6 py-3">Tgl Mulai</th>
                                <th class="px-6 py-3">Tgl Selesai</th>
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Riwayat Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatCutiDetail as $cuti)
                                <tr class="{{ $cuti->status_cuti_id == 2 ? 'bg-red-50' : 'bg-white' }} border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $riwayatCutiDetail->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4">{{ formatDate($cuti->tanggal_mulai) ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ formatDate($cuti->tanggal_selesai) ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $cuti->jumlah_hari ?? '-' }} Hari</td>
                                    <td class="px-6 py-4">{{ $cuti->jeniscuti->nama_cuti ?? '-' }}</td>
                                    <td class="px-6 py-4 font-bold {{ $cuti->status_cuti_id == 1 ? 'text-success-600' : ($cuti->status_cuti_id == 2 ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ $cuti->statusCuti->nama_status ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-left text-xs bg-gray-50">
                                        @php
                                            $approvals = \App\Models\RiwayatApproval::with('approver')->where('cuti_id', $cuti->id)->orderBy('approve_at', 'asc')->get();
                                        @endphp
                                        @forelse($approvals as $approval)
                                            <div class="mb-2 last:mb-0 pb-2 border-b border-gray-200 last:border-0">
                                                <span class="font-semibold text-gray-900">{{ $approval->approver->name ?? 'Unknown' }}</span> 
                                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ str_contains($approval->status_approval, 'ditolak') ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                                    {{ str_replace('_', ' ', $approval->status_approval) }}
                                                </span><br>
                                                <span class="text-gray-500">{{ \Carbon\Carbon::parse($approval->approve_at)->format('d M Y H:i') }}</span>
                                                @if($approval->catatan)
                                                    <br><span class="text-red-600 mt-1 block">Alasan: {{ $approval->catatan }}</span>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-gray-500 italic">Belum ada riwayat approval.</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center bg-gray-50 px-6 py-6 text-gray-500">Tidak ada riwayat cuti untuk karyawan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- pagination --}}
                <div class="mt-3 mb-6 flex gap-2 justify-center items-center">
                    @if ($riwayatCutiDetail && $riwayatCutiDetail->hasPages())
                        @php
                            $totalPagesBot = $riwayatCutiDetail->lastPage();
                            $currentPageBot = $riwayatCutiDetail->currentPage();
                            $rangeBot = 2;
                        @endphp
                        <button wire:click="previousPage('detailsPage')" class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300" {{ $riwayatCutiDetail->onFirstPage() ? 'disabled' : '' }}>&laquo; Prev</button>
                        @if ($currentPageBot > $rangeBot + 1)
                            <button wire:click="gotoPage(1, 'detailsPage')" class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">1</button>
                            @if ($currentPageBot > $rangeBot + 2) <span class="px-2 py-1 text-gray-500">...</span> @endif
                        @endif
                        @for ($page = max(1, $currentPageBot - $rangeBot); $page <= min($totalPagesBot, $currentPageBot + $rangeBot); $page++)
                            <button wire:click="gotoPage({{ $page }}, 'detailsPage')" class="px-2 py-1 {{ $page == $currentPageBot ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-md text-sm">{{ $page }}</button>
                        @endfor
                        @if ($currentPageBot < $totalPagesBot - $rangeBot)
                            @if ($currentPageBot < $totalPagesBot - $rangeBot - 1) <span class="px-2 py-1 text-gray-500">...</span> @endif
                            <button wire:click="gotoPage({{ $totalPagesBot }}, 'detailsPage')" class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">{{ $totalPagesBot }}</button>
                        @endif
                        <button wire:click="nextPage('detailsPage')" class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300" {{ !$riwayatCutiDetail->hasMorePages() ? 'disabled' : '' }}>Next &raquo;</button>
                    @endif
                </div>
            </div>
        @else
            <div class="mt-6 p-8 border-2 border-dashed border-gray-400 rounded-xl text-center bg-gray-50">
                <h3 class="text-lg font-medium text-gray-500">Tekan "Detail" di atas untuk melihat detail riwayat cuti.</h3>
            </div>
        @endif
    @endif
</div>