<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Kategori PPh</h1>

        <!-- Kontrol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <!-- Tombol Tambah Data -->
            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Mobile Icon Only -->
                <a href="{{ route('pph.create') }}"
                    class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah Data" data-tooltip-target="tooltip-pph" data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <!-- Tooltip -->
                <div id="tooltip-pph" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Data
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop Button -->
                <a href="{{ route('pph.create') }}"
                    class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Data
                </a>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Kategori</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pphs as $parent)
                    <tr class="bg-success-300 font-bold border-b border-success-400">
                        <td class="px-6 py-4" colspan="3">
                            {{ $parent->nama }} {{-- Nama kategori induk --}}
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('pph.show', $parent->id) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-view-{{ $parent->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-view-{{ $parent->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Lihat Data
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>

                    @forelse ($parent->children as $child)
                        <tr class="odd:bg-white even:bg-gray-100 border-b hover:bg-success-100">
                            <td class="px-6 py-4 pl-12 text-sm text-gray-700">
                                -
                            </td>
                            <td class="px-6 py-4">{{ $child->nama }}</td>
                            <td class="px-6 py-4">{{ $child->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('pph.edit', $child->id) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-pph-{{ $child->id }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-pph-{{ $child->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Edit Data
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 pl-12 text-sm text-gray-500 italic" colspan="4">
                                Tidak ada subkategori.
                            </td>
                        </tr>
                    @endforelse
                @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-4">Tidak ada data Kategori PPh.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    <!-- Pagination Controls -->
    <x-responsive-pagination :data="$pphs" />
</div>