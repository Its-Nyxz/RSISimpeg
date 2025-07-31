<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Khusus</h1>
        <!-- Kontrol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Tunjangan..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <!-- Tombol Tambah Khusus -->
            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Mobile Icon Only -->
                <a href="{{ route('khusus.create') }}"
                    class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah Khusus" data-tooltip-target="tooltip-khusus" data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <!-- Tooltip -->
                <div id="tooltip-khusus" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Khusus
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop Button -->
                <a href="{{ route('khusus.create') }}"
                    class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Khusus
                </a>
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">Kategori Penerima</th>
                    <th scope="col" class="px-6 py-3">Nominal</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($khususs as $khusus)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $khusus['nama'] }}
                        </td>
                        <td class="px-6 py-4">{{ rupiah($khusus['nominal']) }}</td>
                        <td class="px-6 py-4">{{ $khusus['deskripsi'] }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('khusus.edit', $khusus['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-khusus-{{ $khusus['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-khusus-{{ $khusus['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Tunjangan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data Khusus.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
