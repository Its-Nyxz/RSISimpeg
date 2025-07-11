<div class="container mx-auto mt-6 p-6 bg-white shadow-md rounded-lg">
    <!-- Header -->
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-green-700">List History Absensi</h1>
    </div>

    <!-- Filter & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-6 mb-4">

        <!-- Filter Dropdown -->
        <div class="flex flex-wrap gap-3">
            @can('list-history-user')
                <select wire:model.live="selectedUserId"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500">
                    @foreach ($subordinates as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            @endcan

            <select wire:model.live="month"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="year"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500">
                @foreach (range(date('Y') - 3, date('Y') + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center gap-2">
            <!-- Tombol Export -->
            <div class="relative group">
                <!-- Mobile Icon -->
                <button wire:click="exportPdfHistory"
                    class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition"
                    aria-label="Export PDF">
                    <i class="fas fa-download text-lg"></i>
                </button>
                <!-- Tooltip -->
                <div
                    class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                    Export PDF
                </div>
                <!-- Desktop Full -->
                <button wire:click="exportPdfHistory"
                    class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
            </div>

            <!-- Tombol Export Excel -->
            <div class="relative group">
                <!-- Mobile Icon -->
                <button wire:click="exportExcelHistory"
                    class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition"
                    aria-label="Export Excel">
                    <i class="fas fa-file-excel text-lg"></i>
                </button>
                <!-- Tooltip -->
                <div
                    class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                    Export Excel
                </div>
                <!-- Desktop Full -->
                <button wire:click="exportExcelHistory"
                    class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </button>
            </div>

            @can('list-history-create')
                <!-- Tombol Tambah -->
                <div class="relative group">
                    <!-- Mobile Icon -->
                    <a href="{{ route('aktivitasabsensi.create', ['user_id' => $selectedUserId]) }}"
                        class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                        aria-label="Tambah Jadwal">
                        <i class="fa fa-plus text-lg"></i>
                    </a>
                    <!-- Tooltip -->
                    <div
                        class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                        Tambah Jadwal
                    </div>
                    <!-- Desktop Full -->
                    <a href="{{ route('aktivitasabsensi.create', ['user_id' => $selectedUserId]) }}"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                        + Tambah
                    </a>
                </div>
            @endcan
        </div>

    </div>

    <!-- Absensi Table -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-center text-sm bg-green-300 text-green-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Hari</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Jam Kerja</th>
                    <th scope="col" class="px-6 py-3">Rencana Kerja</th>
                    <th scope="col" class="px-6 py-3">Laporan Kerja</th>
                    <th scope="col" class="px-6 py-3">Jam Lembur</th>
                    <th scope="col" class="px-6 py-3">Deskripsi Lembur</th>
                    <th scope="col" class="px-6 py-3">Feedback</th>
                    {{-- @can('list-history-edit') --}}
                    <th scope="col" class="px-6 py-3">Action</th>
                    {{-- @endcan --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr
                        class="{{ $item['is_holiday']
                            ? 'bg-red-200'
                            : ($item['is_lembur']
                                ? 'bg-yellow-200'
                                : ($item['is_dinas']
                                    ? 'bg-blue-200'
                                    : ($item['late']
                                        ? 'bg-red-400'
                                        : ($loop->even
                                            ? 'bg-green-100'
                                            : 'bg-green-50')))) }} border-b border-green-300 hover:bg-green-200">
                        <td class="px-6 py-4 font-medium text-green-900 whitespace-nowrap">
                            {{ $item['hari'] }}
                        </td>
                        <td class="px-6 py-4">{{ $item['tanggal'] }}</td>
                        <td class="px-6 py-4">{{ $item['jam_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['rencana_kerja'] }}</td>
                        <td class="px-6 py-4">
                            {{ $item['laporan_kerja'] }}
                            {{-- <div class="text-sm text-gray-600 mt-1"> <!-- Tambahkan div untuk keterangan -->
                                <span class="font-medium">Keterangan:</span> {{ $item['keterangan'] ?? '-' }}
                            </div> --}}
                        </td>
                        <th class="px-6 py-4">{{ $item['jam_lembur'] }}</th>
                        <th class="px-6 py-4"> {!! $item['laporan_lembur'] !!}</th>
                        <td class="px-6 py-4">{{ $item['feedback'] }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                @can('list-history-edit')
                                    @if (!is_null($item['id']))
                                        <a href="{{ route('aktivitasabsensi.edit', $item['id']) }}"
                                            class="text-success-900 px-3 py-2 rounded-md border hover:bg-success-300"
                                            data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    @endif
                                @endcan

                                @if (!is_null($item['id']))
                                    <a href="{{ route('aktivitasabsensi.show', $item['id']) }}"
                                        class="text-success-900 px-3 py-2 rounded-md border hover:bg-success-300"
                                        data-tooltip-target="tooltip-show-{{ $item['id'] }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @endif
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
