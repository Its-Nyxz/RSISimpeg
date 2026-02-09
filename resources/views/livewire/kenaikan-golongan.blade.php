<div class="mb-4">
    <div class="flex flex-col md:flex-row justify-between items-center py-4 mb-4 gap-4">
        <h1 class="text-2xl font-bold text-success-900 w-full md:w-auto text-left">Kenaikan Data Karyawan</h1>

        <div class="flex flex-col sm:flex-row w-full md:w-auto items-stretch sm:items-center gap-3">
            
            <!-- Filters Group -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <select wire:model.live="bulan"
                    class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::createFromFormat('!m', $m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>

                <select wire:model.live="tahun"
                    class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach (range(now()->year, now()->year + 5) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>

                <select wire:model.live="selectedUserAktif"
                    class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                    <option value="1">Aktif</option>
                    <option value="0">Non Aktif</option>
                </select>

                @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->id == 87)
                    <select wire:model.live="selectedUnit"
                        class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                        <option value="">-- Pilih Unit --</option>
                        @foreach ($units as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <!-- Search Input -->
            <div class="w-full sm:w-64 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                    class="w-full rounded-lg pl-10 pr-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 shadow-sm transition duration-200 ease-in-out" />
            </div>
        </div>
    </div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3" rowspan="2">Nama</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Pendidikan</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">TMT</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Gol Awal</th>
                    <th scope="col" class="px-6 py-3" colspan="2">Masa Kerja</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Penyesuaian</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Gaji Sekarang</th>
                    <th scope="col" class="px-6 py-3" colspan="2">Kenaikan Gaji Berkala</th>
                    <th scope="col" class="px-6 py-3" colspan="3">Kenaikan Golongan</th>
                    <th scope="col" class="px-6 py-3" rowspan="2">Action</th>
                </tr>
                <tr>
                    <th scope="col" class="px-6 py-3">Tahun</th>
                    <th scope="col" class="px-6 py-3">Bulan</th>
                    <th scope="col" class="px-6 py-3">Waktu</th>
                    <th scope="col" class="px-6 py-3">Gaji</th>
                    <th scope="col" class="px-6 py-3">Waktu</th>
                    <th scope="col" class="px-6 py-3">Gol Baru</th>
                    <th scope="col" class="px-6 py-3">Gaji</th>
                </tr>

            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">{{ $user->pendidikanUser->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->tmt ? formatDate($user->tmt) : '-' }}</td>
                        <td class="px-6 py-4">{{ $user->golongan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->masa_kerja_tahun ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->masa_kerja_bulan ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->masa_kerja_golongan ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($user->gaji_sekarang ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            {{ $user->kenaikan_berkala_waktu ? formatDate($user->kenaikan_berkala_waktu) : '-' }}
                        </td>
                        <td class="px-6 py-4">Rp
                            {{ $user->kenaikan_berkala_gaji ? number_format($user->kenaikan_berkala_gaji, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->kenaikan_golongan_waktu)
                                <span class="{{ $user->golongan_tertinggi ? 'text-red-600 font-semibold' : '' }}">
                                    {{ formatDate($user->kenaikan_golongan_waktu) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $user->golonganBaruNama }}</td>
                        <td class="px-6 py-4">
                            @if ($user->kenaikan_golongan_gaji)
                                <span class="{{ $user->golongan_tertinggi ? 'text-red-600 font-semibold' : '' }}">
                                    Rp {{ number_format($user->kenaikan_golongan_gaji, 0, ',', '.') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{-- @if ($isKepegawaian && $user->pendingGolonganGapok)
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="confirmAlert('Ingin menyetujui user ini?', 'Ya, Setujui!', () => @this.call('approveKenaikan', {{ $user->id }}))"
                                        class="bg-success-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i>
                                    </button>

                                    <button
                                        onclick="confirmRejectWithReason('Ingin menolak user ini?', 'Ya, Tolak!', (reason) => @this.call('rejectKenaikan', {{ $user->id }} ,reason))"
                                        class="bg-red-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @else --}}
                            <a href="{{ route('detailkaryawan.show', ['detailkaryawan' => $user->id]) }}"
                                class="bg-success-700 text-white font-medium rounded-md px-3 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </a>
                        </td>
                        {{-- @endif --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-6 py-4">Tidak ada data Karyawan.</td>
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
