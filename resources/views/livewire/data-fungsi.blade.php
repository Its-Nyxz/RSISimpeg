<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Fungsional</h1>
        <!-- Kontrol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Tunjangan..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <!-- Tombol Tambah Fungsional -->
            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Mobile Icon Only -->
                <a href="{{ route('fungsional.create') }}"
                    class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah Fungsional" data-tooltip-target="tooltip-fungsional"
                    data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <!-- Tooltip -->
                <div id="tooltip-fungsional" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Fungsional
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop Button -->
                <a href="{{ route('fungsional.create') }}"
                    class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Fungsional
                </a>
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Nominal</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fungsionals as $fungsional)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $fungsional->kategorijabatan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">Rp {{ number_format($fungsional->nominal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $fungsional['deskripsi'] }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('fungsional.edit', $fungsional['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-fungsional-{{ $fungsional['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-fungsional-{{ $fungsional['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Fungsional
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus Jabatan Fungsional ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $fungsional['id'] }}))"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash"></i>
                                <div id="tooltip-destroy-{{ $fungsional['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md">
                                    Hapus Jabatan
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data Fungsional.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
