<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Pedidikan</h1>
        <!-- Kontrol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Pedidikan..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <!-- Tombol Tambah Pendidikan -->
            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Mobile Icon Only -->
                <a href="{{ route('pendidikan.create') }}"
                    class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah Pendidikan" data-tooltip-target="tooltip-pendidikan"
                    data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <!-- Tooltip -->
                <div id="tooltip-pendidikan" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Pendidikan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop Button -->
                <a href="{{ route('pendidikan.create') }}"
                    class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Pendidikan
                </a>
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Golongan Minimal</th>
                    <th scope="col" class="px-6 py-3">Golongan Maximal</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendidikans as $pendidikan)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $pendidikan->nama }}
                        </td>
                        <td class="px-6 py-4">{{ $pendidikan->minimGolongan->nama }}</td>
                        <td class="px-6 py-4">{{ $pendidikan->maximGolongan->nama }}</td>
                        <td class="px-6 py-4">{{ $pendidikan->deskripsi }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('pendidikan.edit', $pendidikan->id) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-pendidikan-{{ $pendidikan->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-pendidikan-{{ $pendidikan->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Pendidikan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button type="button"
                                onclick="confirmAlert('Yakin ingin menghapus Pendidikan ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $pendidikan['id'] }}))"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300 relative group">
                                <i class="fa-solid fa-trash"></i>
                                <div id="tooltip-destroy-{{ $pendidikan['id'] }}"
                                    class="absolute z-10 hidden group-hover:block bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md">
                                    Hapus
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data Pendidikan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
