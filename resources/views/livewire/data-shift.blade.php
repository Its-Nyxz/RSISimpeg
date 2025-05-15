<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Shift {{ Auth::user()->unitKerja->nama ?? 'Tidak Ada Unit' }}
        </h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            @if (auth()->user()->hasRole('Super Admin'))
                <!-- Input Pencarian -->
                <select wire:model.live="selectedUnit"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">>
                    <option value="">Pilih Unit</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Shift..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>


            <!-- Tombol Tambah Shift -->
            <a href="{{ route('shift.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Shift
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto overflow-y-auto  max-h-[45rem] shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="sticky top-0 bg-success-400 text-success-900 z-10">
                <tr>
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
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $shift['nama_shift'] ?? '-' }}
                        </td>
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
