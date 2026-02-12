<div>
    <div class="flex flex-col sm:flex-row justify-between py-2 mb-3 gap-3 sm:gap-0">
        <h1 class="text-xl sm:text-2xl font-bold text-success-900">Master Hari Libur Nasional</h1>
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 sm:gap-3 mb-3">

            <div>
                <select wire:model="year" wire:change="updateYear($event.target.value)"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 w-full sm:w-auto text-sm">
                    @foreach (range(now()->year - 3, now()->year) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Hari..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 text-sm" />
            </div>
            <!-- Tombol Tambah hari libur -->
            <div class="relative group mt-0">
                <!-- Mobile (ikon saja) -->
                <a href="{{ route('liburnasional.create') }}"
                    class="sm:hidden flex justify-center items-center p-2.5 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition w-full"
                    aria-label="Tambah hari libur">
                    <i class="fa fa-plus mr-2"></i> Tambah
                </a>

                <!-- Desktop (teks penuh) -->
                <a href="{{ route('liburnasional.create') }}"
                    class="hidden sm:flex items-center px-4 py-2 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Hari Libur
                </a>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
        <table class="w-full text-xs sm:text-sm text-left text-gray-700">
            <thead class="text-xs sm:text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 whitespace-nowrap w-[150px]">Tanggal</th>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 min-w-[200px]">Keterangan</th>
                    <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 whitespace-nowrap w-[100px] text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($holidays as $holiday)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300 transition duration-150">
                        <td scope="row" class="px-3 py-2 sm:px-6 sm:py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $holiday['date'] ? \Carbon\Carbon::parse($holiday['date'])->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td class="px-3 py-2 sm:px-6 sm:py-4">
                            {{ $holiday['description'] ?? '-' }}
                        </td>
                        <td class="px-3 py-2 sm:px-6 sm:py-4 flex gap-1 justify-center">
                            <a href="{{ route('liburnasional.edit', $holiday['id']) }}"
                                class="text-success-900 p-1.5 sm:px-3 sm:py-2 rounded-md border bg-white/50 hover:bg-slate-300 transition"
                                data-tooltip-target="tooltip-holiday-{{ $holiday['id'] }}">
                                <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                            </a>
                            <div id="tooltip-holiday-{{ $holiday['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus Hari Libur ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $holiday['id'] }}))"
                                class="text-success-900 p-1.5 sm:px-3 sm:py-2 rounded-md border bg-white/50 hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                                <div id="tooltip-destroy-{{ $holiday['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-md whitespace-nowrap right-0">
                                    Hapus
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-4 text-gray-500 italic">Tidak ada data Hari Libur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
