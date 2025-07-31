<div>
    @if ($type === 'jabatan')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Jabatan</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Jabatan..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>
                <!-- Tombol Tambah Merk -->
                <a href="{{ route('jabatan.create') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Jabatan
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Kualifikasi</th>
                        <th scope="col" class="px-6 py-3">Nominal</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ $item['kualifikasi'] }}</td>
                            <td class="px-6 py-4">{{ rupiah($item['nominal']) }}</td>
                            <td class="px-6 py-4">{{ $item['deskripsi'] }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('jabatan.edit', $item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Jabatan
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Jabatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif ($type === 'umum')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Tunjangan Umum</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)"
                        placeholder="Cari Tunjangan Umum..."
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
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ rupiah($item['nominal']) }}</td>
                            <td class="px-6 py-4">{{ $item['deskripsi'] }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('umum.edit', $item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Tunjangan Umum
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
    @elseif ($type == 'tidaktetap')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Tunjangan Tidak tetap</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)"
                        placeholder="Cari Tunjangan Tidak Tetap..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>

                <a href="{{route('trans.create')}}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Tunjangan Tidak Tetap
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Nominal Makan</th>
                        <th scope="col" class="px-6 py-3">Nominal Transport</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ rupiah($item['nom_makan']) }}</td>
                            <td class="px-6 py-4">{{ rupiah($item['nom_transport']) }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('trans.edit', $item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Tunjangan Tidak Tetap
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Tunjangan Tidak Tetap.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($type === 'fungsional')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Fungsional</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)"
                        placeholder="Cari Fungsional..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>
                <a href="{{ route('fungsional.create') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Fungsional
                </a>
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
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ rupiah($item['nominal']) }}</td>
                            <td class="px-6 py-4">{{ $item['deskripsi'] }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('fungsional.edit', $item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Jabatan
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Jabatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($type === 'khusus')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Khusus</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)"
                        placeholder="Cari Tunjangan..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

                </div>
                <a href="{{ route('khusus.create') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Tunjangan
                </a>
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
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ rupiah($item['nominal']) }}</td>
                            <td class="px-6 py-4">{{ $item['deskripsi'] }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('khusus.edit', $item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Khusus
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Jabatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
