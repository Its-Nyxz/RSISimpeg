<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Tunjangan Umum</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Tunjangan Umum..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <!-- Tombol Tambah Merk -->
            <a href="{{ route('umum.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Tunjangan Umum
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Jenis Jabatan</th>
                    <th scope="col" class="px-6 py-3">Nominal Tunjangan</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($umums as $umum)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $umum->kategorijabatan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">Rp {{ number_format($umum->nominal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $umum['deskripsi'] }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('umum.edit', $umum['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-umum-{{ $umum['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-jabatan-{{ $umum['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Tunjangan Umum
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus Jabatan ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $umum['id'] }}))"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash"></i>
                                <div id="tooltip-destroy-{{ $umum['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md">
                                    Hapus Jabatan
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                            <div id="tooltip-destroy-{{ $umum['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Hapus Jabatan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data Tunjangan Umum.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
