<div>
    @if ($type === 'masakerja')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Masa Kerja</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1 flex gap-2">
                    <input type="text" wire:model.defer="search" wire:ignore placeholder="Cari..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                    <button wire:click="updateSearch(search)"
                        class="bg-success-600 text-white px-4 py-2 rounded-lg hover:bg-success-700 transition">
                        Go
                    </button>
                </div>
                @dump($search)

                <!-- Tombol Tambah Merk -->
                <a href="{{ route('masakerja.create') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Masa Kerja
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Poin</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ $item['point'] }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('masakerja.create',$item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Masa Kerja
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Masa Kerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif ($type === 'levelunit')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Level Unit</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text"wire:model.debounce.500ms="search" placeholder="Cari..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>
                @dump($search)

                <!-- Tombol Tambah Merk -->
                <a href="{{ route('levelunit.create') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Level Unit
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Unit Kerja</th>
                        <th scope="col" class="px-6 py-3">Level Unit Kerja</th>
                        <th scope="col" class="px-6 py-3">Point Unit</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama_unit'] }}
                            </td>
                            <td class="px-6 py-4">{{ $item['nama_level'] ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $item['poin'] }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('levelunit.edit', $item['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Level Unit Kerja
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Level Unit Kerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif ($type === 'proposionalitas')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Proposionalitas Point</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)"
                        placeholder="Cari Proposionalitas Point..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>

                <!-- Tombol Tambah Merk -->
                <a href="#"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Proposionalitas Point
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Proposionalitas</th>
                        <th scope="col" class="px-6 py-3">Point</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ $item['poin'] }}</td>
                            <td class="px-6 py-4">
                                <a href="#"
                                    cxlass="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Proposionalitas
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Proposionalitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif ($type === 'pointperan')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Point Peran Fungsional</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)"
                        placeholder="Cari Point Peran Fungsional..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>

                <!-- Tombol Tambah Merk -->
                <a href="#"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Point Peran Fungsional
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Point</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ $item['poin'] }}</td>
                            <td class="px-6 py-4">
                                <a href="#"
                                    cxlass="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Point Peran Fungsional
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4">Tidak ada data Point Peran Fungsional.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif ($type === 'tukinjabatan')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Jabatan</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Jabatan"
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>

                <!-- Tombol Tambah Jabatan -->
                <a href="#"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah Jabatan
                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama Jabatan</th>
                        <th scope="col" class="px-6 py-3">Point</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $item['nama'] }}
                            </td>
                            <td class="px-6 py-4">{{ $item['poin'] }}</td>
                            <td class="px-6 py-4">
                                <a href="#"
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
                            <td colspan="4" class="text-center px-6 py-4">Tidak ada data Jabatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif


</div>

