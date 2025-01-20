<div>
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
                        + Tambah Jadwal
                    </a>
                </div>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama User</th>
                        <th scope="col" class="px-6 py-3">Shift</th>
                        <th scope="col" class="px-6 py-3">Opsi Absen</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Keterangan</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $jadwal)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $jadwal['user']['name'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $jadwal['shift']['nama_shift'] ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $jadwal['opsi_absens']['nama_opsi'] ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $jadwal['tanggal_jadwal'] }}</td>
                            <td class="px-6 py-4">{{ $jadwal['keterangan_absen'] ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('jadwal.edit', $jadwal['id']) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-jadwal-{{ $jadwal['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-jadwal-{{ $jadwal['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Jadwal
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama User</th>
                            <th scope="col" class="px-6 py-3">Shift</th>
                            <th scope="col" class="px-6 py-3">Opsi Absen</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Keterangan</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $jadwal)
                            <tr
                                class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                    {{ $jadwal['user']['name'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4">{{ $jadwal['shift']['nama_shift'] ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $jadwal['opsi_absens']['nama_opsi'] ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $jadwal['tanggal_jadwal'] }}</td>
                                <td class="px-6 py-4">{{ $jadwal['keterangan_absen'] ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <a href="/jadwal/edit/{{ $jadwal['id'] }}"
                                        class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                        data-tooltip-target="tooltip-jadwal-{{ $jadwal['id'] }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <div id="tooltip-jadwal-{{ $jadwal['id'] }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                        Ubah Jadwal
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center px-6 py-4">Tidak ada data Jadwal Absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>