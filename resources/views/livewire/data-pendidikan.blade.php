<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Pendidikan</h1>
        
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Pendidikan..."
                class="flex-1 sm:w-64 rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <div class="flex items-center gap-2">
                <a href="{{ route('pendidikan.create') }}"
                    class="sm:hidden inline-flex items-center justify-center p-2.5 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shrink-0"
                    aria-label="Tambah Pendidikan" data-tooltip-target="tooltip-pendidikan"
                    data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <div id="tooltip-pendidikan" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Pendidikan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

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
                    <th scope="col" class="px-6 py-3">Gol. Minimal</th>
                    <th scope="col" class="px-6 py-3">Gol. Maximal</th>
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
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('pendidikan.edit', $pendidikan->id) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border border-success-300 hover:bg-slate-300 transition"
                                    data-tooltip-target="tooltip-edit-{{ $pendidikan->id }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-edit-{{ $pendidikan->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Pendidikan
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <button type="button"
                                    onclick="confirmAlert('Yakin ingin menghapus Pendidikan ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $pendidikan->id }}))"
                                    class="text-success-900 px-3 py-2 rounded-md border border-success-300 hover:bg-slate-300 transition relative group">
                                    <i class="fa-solid fa-trash"></i>
                                    <div class="absolute z-10 hidden group-hover:block bottom-full mb-2 left-1/2 -translate-x-1/2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md whitespace-nowrap">
                                        Hapus
                                        <div class="tooltip-arrow border-t-gray-900" style="left:50%; margin-left:-5px;"></div>
                                    </div>
                                </button>
                            </div>
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