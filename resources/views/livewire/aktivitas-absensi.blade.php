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
                    <!-- Filter Unit - Hanya tampil jika bisa akses semua unit -->
                    @if ($canAccessAllUnits)
                        <select wire:model.live="selectedUnitId"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500">
                            @foreach ($units as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    @else
                        <!-- Jika tidak bisa akses semua unit, tampilkan unit sendiri saja (readonly) -->
                        <div class="border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100">
                            {{ auth()->user()->unitKerja->nama ?? 'Unit Tidak Diketahui' }}
                        </div>
                    @endif

                    <!-- Filter Pegawai -->
                    <select wire:model.live="selectedUserId"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500">
                        @forelse ($subordinates as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @empty
                            <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                        @endforelse
                    </select>
                @endcan

                <select wire:model.live="month"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
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
                <!-- Export PDF -->
                <div class="relative group">
                    <button wire:click="exportPdfHistory"
                        class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                        <i class="fas fa-download text-lg"></i>
                    </button>
                    <div
                        class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                        Export PDF
                    </div>
                    <button wire:click="exportPdfHistory"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                </div>

                <!-- Export Excel (1 User) -->
                <div class="relative group">
                    <button wire:click="exportExcelHistory"
                        class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition">
                        <i class="fas fa-file-excel text-lg"></i>
                    </button>
                    <div
                        class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                        Export Excel
                    </div>
                    <button wire:click="exportExcelHistory"
                        class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>

                <!-- Export All Users -->
                @if ($canExportAll)
                    <div class="relative group">
                        <button wire:click="openExportAllModal"
                            class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-info-100 text-info-900 hover:bg-info-600 hover:text-white transition">
                            <i class="fas fa-file-excel text-lg"></i>
                        </button>
                        <div
                            class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                            Export Semua Pegawai
                        </div>
                        <button wire:click="openExportAllModal"
                            class="hidden sm:flex items-center px-5 py-2.5 text-sm rounded-lg font-medium bg-info-100 text-info-900 hover:bg-info-600 hover:text-white transition">
                            <i class="fas fa-file-excel mr-1"></i> Export All
                        </button>
                    </div>
                @endif
                @if ($showExportModal)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white w-96 rounded-lg shadow-lg p-6">
                            <h2 class="text-lg font-semibold mb-3">Pilih Pegawai untuk Export</h2>

                            <div class="max-h-60 overflow-y-auto border rounded p-3">
                                @foreach ($allUsers as $id => $name)
                                    <label class="flex items-center gap-2 mb-2">
                                        <input type="checkbox" wire:model="exportSelectedUsers"
                                            value="{{ $id }}"
                                            class="w-4 h-4 text-success-600 border-gray-300 rounded focus:ring-success-500 accent-green-700">
                                        <span>{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex justify-end mt-4 gap-2">
                                <button wire:click="toggleSelectAll"
                                    class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">
                                    @if (count($exportSelectedUsers) === count($allUsers))
                                        Pilih Semua
                                    @else
                                        Pilih Semua
                                    @endif
                                </button>

                                <button wire:click="$set('showExportModal', false)"
                                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                                    Batal
                                </button>

                                <button wire:click="exportSelected"
                                    class="px-4 py-2 rounded bg-success-600 text-white hover:bg-success-700">
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @can('list-history-create')
                    <!-- Tambah -->
                    <div class="relative group">
                        <a href="{{ route('aktivitasabsensi.create', ['user_id' => $selectedUserId]) }}"
                            class="sm:hidden w-12 h-12 flex items-center justify-center rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                            <i class="fa fa-plus text-lg"></i>
                        </a>
                        <div
                            class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                            Tambah Jadwal
                        </div>
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
                            <td class="px-6 py-4">{!! $item['jam_kerja'] !!}</td>
                            <td class="px-6 py-4">{!! $item['rencana_kerja'] !!}</td>
                            <td class="px-6 py-4">{!! $item['laporan_kerja'] !!}</td>
                            <td class="px-6 py-4">{!! $item['jam_lembur'] !!}</td>
                            <td class="px-6 py-4">{!! $item['laporan_lembur'] !!}</td>
                            <td class="px-6 py-4">{!! $item['feedback'] !!}</td>
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