<div>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-3">
        <!-- Judul -->
        <h1 class="text-2xl font-bold text-success-900">
            Shift {{ Auth::user()->unitKerja->nama ?? 'Tidak Ada Unit' }}
        </h1>

        <!-- Kontrol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">

            <!-- Dropdown Unit (khusus Super Admin) -->
            @if (auth()->user()->hasRole('Super Admin'))
                <select wire:model.live="selectedUnit"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    <option value="">Pilih Unit</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif

            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Shift..."
                class="rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 w-full sm:w-64" />

            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Tombol Tambah Shift -->
                <div class="relative group">
                    <!-- Mobile Icon Only -->
                    <a href="{{ route('shift.create') }}"
                        class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                        aria-label="Tambah Shift" data-tooltip-target="tooltip-shift" data-tooltip-placement="top">
                        <i class="fa fa-plus"></i>
                    </a>
                    <!-- Tooltip -->
                    <div id="tooltip-shift" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                        Tambah Shift
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <!-- Desktop Button -->
                    <a href="{{ route('shift.create') }}"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                        + Tambah Shift
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto overflow-y-auto  max-h-[45rem] shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="sticky top-0 bg-success-400 text-success-900 z-10">
                <tr>
                    @if (auth()->user()->hasRole('Super Admin'))
                        <th scope="col" class="px-6 py-3">Nama Unit</th>
                    @endif
                    <th scope="col" class="px-6 py-3">Kode Shift</th>
                    <th scope="col" class="px-6 py-3">Jam Masuk</th>
                    <th scope="col" class="px-6 py-3">Jam Keluar</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shifts as $shift)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        @if (auth()->user()->hasRole('Super Admin'))
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ data_get($shift, 'unit_kerja.nama', '-') }}
                            </td>
                        @endif
                        <td class="px-6 py-4">{{ $shift['nama_shift'] }}</td>
                        <td class="px-6 py-4">{{ $shift['jam_masuk'] }}</td>
                        <td class="px-6 py-4">{{ $shift['jam_keluar'] }}</td>
                        <td class="px-6 py-4">{{ $shift['keterangan'] }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('shift.edit', $shift['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-shift-{{ $shift['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-shift-{{ $shift['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Shift
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <!-- Tombol Hapus -->
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus shift ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $shift['id'] }}))"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash"></i>
                                <div id="tooltip-destroy-{{ $shift['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md">
                                    Hapus Shift
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                            <div id="tooltip-destroy-{{ $shift['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Hapus Shift
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-6 py-4">Tidak ada data Shift.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="flex justify-center items-center space-x-4 bg-white shadow-md p-4 rounded-sm">
            <button wire:click="prevPage" @if ($currentPage === 1) disabled @endif
                class="px-4 py-2 rounded-md border text-sm bg-white text-gray-700 border-gray-300 hover:bg-gray-100 disabled:bg-gray-300 disabled:text-gray-500 disabled:border-gray-400">
                &larr; Prev
            </button>

            <span class="text-sm text-gray-700">
                Halaman <strong>{{ $currentPage }}</strong>
                @if ($totalShifts > 0)
                    dari {{ ceil($totalShifts / $perPage) }}
                @endif
            </span>

            <button wire:click="nextPage" @if ($currentPage * $perPage >= $totalShifts) disabled @endif
                class="px-4 py-2 rounded-md border text-sm bg-white text-gray-700 border-gray-300 hover:bg-gray-100 disabled:bg-gray-300 disabled:text-gray-500 disabled:border-gray-400">
                Next &rarr;
            </button>
        </div>
    </div>
</div>
