<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Potongan</h1>
        <!-- Kontrol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Potongan..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

            <!-- Tombol Tambah Potongan -->
            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <!-- Mobile Icon Only -->
                <a href="{{ route('potongan.create') }}"
                    class="sm:hidden p-3 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah Potongan" data-tooltip-target="tooltip-potongan" data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <!-- Tooltip -->
                <div id="tooltip-potongan" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Potongan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop Button -->
                <a href="{{ route('potongan.create') }}"
                    class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Potongan
                </a>
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Potongan</th>
                    <th scope="col" class="px-6 py-3">Nominal Potongan</th>
                    {{-- <th scope="col" class="px-6 py-3">Jenis Potongan</th> --}}
                    <th scope="col" class="px-6 py-3">Wajib</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($potongans as $potongan)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $potongan['nama'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $potongan['nominal'] }}</td>
                        {{-- <td class="px-6 py-4">{{ $potongan['jenis'] ?? '-' }}</td> --}}
                        <td class="px-6 py-4">
                            {{ $potongan['is_wajib'] ? 'iya' : 'tidak' }}
                        </td>
                        <td class="px-6 py-4 flex items-center gap-2">

                            {{-- Tombol Edit --}}
                            <div class="relative group">
                                <a href="{{ route('potongan.edit', $potongan['id']) }}"
                                    class="inline-flex items-center justify-center w-10 h-10 text-success-900 border rounded-md hover:bg-slate-300">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div
                                    class="absolute z-10 bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md 
                    opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    Ubah Potongan
                                </div>
                            </div>

                            {{-- Tombol Hapus --}}
                            <div class="relative group">
                                <button type="button"
                                    onclick="confirmAlert('Yakin ingin menghapus Data Potongan ini?', 'Ya, hapus!', () => @this.call('destroy', {{ $potongan['id'] }}))"
                                    class="inline-flex items-center justify-center w-10 h-10 text-success-900 border rounded-md hover:bg-slate-300">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <div
                                    class="absolute z-10 bottom-full mb-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-md 
                    opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    Hapus Potongan
                                </div>
                            </div>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-6 py-4">Tidak ada data potongan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
