<div>
    <div class="flex flex-col md:flex-row justify-between items-center py-4 mb-4 gap-4">
        <h1 class="text-2xl font-bold text-success-900 w-full md:w-auto text-left">Master Hari Libur Nasional</h1>
        
        <div class="flex flex-col sm:flex-row w-full md:w-auto items-stretch sm:items-center gap-3">
            <!-- Select Tahun -->
            <div class="w-full sm:w-auto">
                <select wire:model="year" wire:change="updateYear($event.target.value)"
                    class="w-full sm:w-auto rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 bg-white shadow-sm transition duration-200 ease-in-out cursor-pointer hover:border-success-400">
                    @foreach (range(now()->year - 3, now()->year + 1) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Input -->
            <div class="w-full sm:w-64 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Hari..."
                    class="w-full rounded-lg pl-10 pr-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600 shadow-sm transition duration-200 ease-in-out" />
            </div>

            <!-- Tombol Tambah hari libur -->
            <div class="relative group w-full sm:w-auto">
                <a href="{{ route('liburnasional.create') }}"
                    class="flex items-center justify-center w-full sm:w-auto px-5 py-2.5 text-sm rounded-lg font-medium bg-success-600 text-white hover:bg-success-700 hover:shadow-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                    <i class="fa fa-plus mr-2"></i> Tambah
                </a>
                
                <!-- Tooltip (Optional, maybe not needed if text is clear) -->
                <div role="tooltip"
                    class="absolute z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-300 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm whitespace-nowrap pointer-events-none">
                    Tambah Hari Libur
                    <div class="tooltip-arrow absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($holidays as $holiday)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $holiday['date'] ? \Carbon\Carbon::parse($holiday['date'])->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $holiday['description'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('liburnasional.edit', $holiday['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-holiday-{{ $holiday['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-holiday-{{ $holiday['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Data Libur Nasional
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus Hari Libur ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $holiday['id'] }}))"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash"></i>
                                <div id="tooltip-destroy-{{ $holiday['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md">
                                    Hapus Hari Libur ini
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                            <div id="tooltip-destroy-{{ $holiday['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Hapus Hari Libur ini
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-4">Tidak ada data Hari Libur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
