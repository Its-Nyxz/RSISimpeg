<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Opsi Absen</h1>
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-3">

            <!-- Input Pencarian -->
            <div class="w-full sm:flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Opsi Absen..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <!-- Tombol Tambah Opsi Absen -->
            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Mobile (ikon saja) -->
                <a href="{{ route('opsi.create') }}"
                    class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah Opsi Absen" data-tooltip-target="tooltip-opsi" data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>

                <!-- Tooltip -->
                <div id="tooltip-opsi" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Opsi
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop (teks penuh) -->
                <a href="{{ route('opsi.create') }}"
                    class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Opsi Absen
                </a>
            </div>
        </div>

    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Opsi Absen</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opsis as $opsi)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $opsi['name'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('opsi.edit', $opsi['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-opsi-{{ $opsi['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-opsi-{{ $opsi['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Opsi Absen
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-6 py-4">Tidak ada data Opsi Absen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
