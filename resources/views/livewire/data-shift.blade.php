<div>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 py-3 mb-4">
        <h1 class="text-xl md:text-2xl font-bold text-success-900 leading-tight">
            Shift {{ Auth::user()->unitKerja->nama ?? 'Tidak Ada Unit' }}
        </h1>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            @if (auth()->user()->hasRole('Super Admin'))
                <select wire:model.live="selectedUnit"
                    class="w-full sm:w-48 rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm bg-white text-gray-700">
                    <option value="">Pilih Unit</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Shift..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 text-sm" />
                </div>
                @php
                    $isStaf = auth()
                        ->user()
                        ->roles->contains(function ($role) {
                            return str_contains(strtolower($role->name), 'staf');
                        });
                @endphp
                <div class="relative group shrink-0">
                    @if (!$isStaf)
                        <a href="{{ route('shift.create') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shadow-sm border border-success-200"
                            aria-label="Tambah Shift">
                            <i class="fa fa-plus"></i>
                            <span class="hidden sm:inline ml-2 text-sm font-medium whitespace-nowrap">Tambah
                                Shift</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-success-300">
        <table class="w-full text-sm text-left text-gray-700 whitespace-nowrap">
            <thead class="text-sm uppercase bg-success-400 text-success-900 font-bold">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    @if (auth()->user()->hasRole('Super Admin'))
                        <th scope="col" class="px-6 py-3">Nama Unit</th>
                    @endif
                    <th scope="col" class="px-6 py-3">Kode Shift</th>
                    <th scope="col" class="px-6 py-3 text-center">Jam Masuk</th>
                    <th scope="col" class="px-6 py-3 text-center">Jam Keluar</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    @if (!$isStaf)
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($shifts as $shift)
                    <tr
                        class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300 transition-colors">
                        <td class="px-6 py-4 text-center w-10">
                            {{ $loop->iteration + ($shifts->currentPage() - 1) * $shifts->perPage() }}
                        </td>
                        @if (auth()->user()->hasRole('Super Admin'))
                            <td class="px-6 py-4 font-medium text-success-900">
                                {{ data_get($shift, 'unit_kerja.nama', '-') }}
                            </td>
                        @endif
                        <td class="px-6 py-4 font-medium text-success-900">
                            {{ $shift['nama_shift'] }}
                        </td>
                        <td class="px-6 py-4 text-center tabular-nums">
                            {{ $shift['jam_masuk'] ?? '--:--' }}
                        </td>
                        <td class="px-6 py-4 text-center tabular-nums">
                            {{ $shift['jam_keluar'] ?? '--:--' }}
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate">
                            {{ $shift['keterangan'] ?? '-' }}
                        </td>
                        @if (!$isStaf)
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Edit: Style disamakan dengan tabel pendidikan --}}
                                    <a href="{{ route('shift.edit', $shift['id']) }}"
                                        class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 transition"
                                        data-tooltip-target="tooltip-shift-edit-{{ $shift['id'] }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <div id="tooltip-shift-edit-{{ $shift['id'] }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                        Ubah Shift
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    {{-- Tombol Delete: Style disamakan dengan tabel pendidikan --}}
                                    <button type="button"
                                        onclick="confirmAlert('Yakin ingin menghapus shift ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $shift['id'] }}))"
                                        class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group transition">
                                        <i class="fa-solid fa-trash"></i>
                                        <div id="tooltip-destroy-{{ $shift['id'] }}"
                                            class="absolute z-10 hidden group-hover:block bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md whitespace-nowrap">
                                            Hapus
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center px-6 py-12 text-gray-500 italic">
                            Tidak ada data Shift tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex flex-wrap justify-center items-center gap-2 pb-4">
        @if (!$shifts->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-3 py-1.5 bg-success-100 hover:bg-success-600 text-success-900 hover:text-white rounded-md text-xs font-medium transition">
                &laquo; Sebelumnya
            </button>
        @endif

        <div class="flex gap-1">
            @php
                $totalPages = $shifts->lastPage();
                $currentPage = $shifts->currentPage();
                $range = 1;
            @endphp

            @for ($page = max($currentPage - $range, 1); $page <= min($currentPage + $range, $totalPages); $page++)
                <button wire:click="gotoPage({{ $page }})"
                    class="px-3 py-1.5 rounded-md text-xs font-medium transition {{ $page == $currentPage ? 'bg-success-600 text-white' : 'bg-success-100 text-success-900 hover:bg-success-200' }}">
                    {{ $page }}
                </button>
            @endfor
        </div>

        @if ($shifts->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-3 py-1.5 bg-success-100 hover:bg-success-600 text-success-900 hover:text-white rounded-md text-xs font-medium transition">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
