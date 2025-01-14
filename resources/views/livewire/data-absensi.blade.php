<div>

    @if ($type === 'shift')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Shift</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Shift..."

    @if ($type === 'jadwalabsen')
        <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Jadwal Absensi</h1>
            <div class="flex justify-between items-center gap-4 mb-3">
                <!-- Input Pencarian -->
                <div class="flex-1">
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Jadwal..."

                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>

                <!-- Tombol Tambah Jadwal -->
                <a href="#"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">

                    + Tambah Shift

                    + Tambah Jadwal

                </a>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>

                        <th scope="col" class="px-6 py-3">Nama Shift</th>
                        <th scope="col" class="px-6 py-3">Jam Masuk</th>
                        <th scope="col" class="px-6 py-3">Jam Keluar</th>

                        <th scope="col" class="px-6 py-3">Nama User</th>
                        <th scope="col" class="px-6 py-3">Shift</th>
                        <th scope="col" class="px-6 py-3">Opsi Absen</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>

                        <th scope="col" class="px-6 py-3">Keterangan</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">

                                {{ $item['nama_shift'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $item['jam_masuk'] }}</td>
                            <td class="px-6 py-4">{{ $item['jam_keluar'] }}</td>
                            <td class="px-6 py-4">{{ $item['keterangan'] }}</td>
                            <td class="px-6 py-4">
                                <a href="/shift/edit/{{ $item['id'] }}"

                                {{ $item['user']['name'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $item['shift']['nama_shift'] ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $item['opsi_absens']['nama_opsi'] ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $item['tanggal_jadwal'] }}</td>
                            <td class="px-6 py-4">{{ $item['keterangan_absen'] ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="/jadwal/edit/{{ $item['id'] }}"

                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">

                                    Ubah Shift

                                    Ubah Jadwal

                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>

                            <td colspan="6" class="text-center px-6 py-4">Tidak ada data Shift.</td>

                            <td colspan="6" class="text-center px-6 py-4">Tidak ada data Jadwal Absensi.</td>

                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif


    @if ($type === 'opsi')
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Opsi Absen</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Opsi Absen..."

    @if ($type === 'status')
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Status Absensi</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Status..."

                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <!-- Tombol Tambah Status -->
            <a href="#"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">

                + Tambah Opsi Absen

                + Tambah Status

            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>

                    <th scope="col" class="px-6 py-3">Keterangan</th>

                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr
                        class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">

                            {{ $item['nama'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item['keterangan'] ?? '-' }}

                        </td>
                        <td class="px-6 py-4">
                            <a href="/status/edit/{{ $item['id'] }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">

                                Ubah Opsi Absen

                                Ubah Status

                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>

                        <td colspan="3" class="text-center px-6 py-4">Tidak ada data Opsi Absen.</td>

                        <td colspan="3" class="text-center px-6 py-4">Tidak ada data Status Absensi.</td>

                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

</div>

