<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Shift {{ Auth::user()->unitKerja->nama ?? 'Tidak Ada Unit' }}
        </h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEPEGAWAIAN')
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
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
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
    </div>
</div>
