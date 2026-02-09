    <div class="container mx-auto mt-4 sm:mt-6 p-4 sm:p-6 bg-white shadow-md rounded-lg">
        <!-- Header -->
        <div class="py-2 mb-3">
            <h1 class="text-xl sm:text-2xl font-bold text-green-700">List History Absensi</h1>
        </div>

        <!-- Filter & Tombol Aksi -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-4">

            <!-- Filter Dropdown -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 w-full xl:w-auto">
                @can('list-history-user')
                    <!-- Filter Unit - Hanya tampil jika bisa akses semua unit -->
                    @if ($canAccessAllUnits)
                        <select wire:model.live="selectedUnitId"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 w-full">
                            @foreach ($units as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    @else
                        <!-- Jika tidak bisa akses semua unit, tampilkan unit sendiri saja (readonly) -->
                        <div class="border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100 w-full truncate">
                            {{ auth()->user()->unitKerja->nama ?? 'Unit Tidak Diketahui' }}
                        </div>
                    @endif

                    <!-- Filter Pegawai -->
                    <select wire:model.live="selectedUserId"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 w-full">
                        @forelse ($subordinates as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @empty
                            <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                        @endforelse
                    </select>
                @endcan

                <select wire:model.live="month"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 w-full">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select wire:model.live="year"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 w-full">
                    @foreach (range(date('Y') - 3, date('Y') + 1) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-end">
                <!-- Export PDF -->
                <div class="relative group">
                    <button wire:click="exportPdfHistory"
                        class="sm:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                        <i class="fas fa-download text-base"></i>
                    </button>
                    <div
                        class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                        Export PDF
                    </div>
                    <button wire:click="exportPdfHistory"
                        class="hidden sm:flex items-center px-4 py-2 text-sm rounded-lg font-medium bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                </div>

                <!-- Export Excel (1 User) -->
                <div class="relative group">
                    <button wire:click="exportExcelHistory"
                        class="sm:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition">
                        <i class="fas fa-file-excel text-base"></i>
                    </button>
                    <div
                        class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                        Export Excel
                    </div>
                    <button wire:click="exportExcelHistory"
                        class="hidden sm:flex items-center px-4 py-2 text-sm rounded-lg font-medium bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>

                <!-- Export All Users -->
                @if ($canExportAll)
                    <div class="relative group">
                        <button wire:click="openExportAllModal"
                            class="sm:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-info-100 text-info-900 hover:bg-info-600 hover:text-white transition">
                            <i class="fas fa-file-excel text-base"></i>
                        </button>
                        <div
                            class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                            Export Semua Pegawai
                        </div>
                        <button wire:click="openExportAllModal"
                            class="hidden sm:flex items-center px-4 py-2 text-sm rounded-lg font-medium bg-info-100 text-info-900 hover:bg-info-600 hover:text-white transition">
                            <i class="fas fa-file-excel mr-1"></i> All
                        </button>
                    </div>
                @endif
                @if ($showExportModal)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
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

                            <div class="flex flex-col sm:flex-row justify-end mt-4 gap-2">
                                <button wire:click="toggleSelectAll"
                                    class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 text-sm">
                                    @if (count($exportSelectedUsers) === count($allUsers))
                                        Pilih Semua
                                    @else
                                        Pilih Semua
                                    @endif
                                </button>

                                <button wire:click="$set('showExportModal', false)"
                                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm">
                                    Batal
                                </button>

                                <button wire:click="exportSelected"
                                    class="px-4 py-2 rounded bg-success-600 text-white hover:bg-success-700 text-sm">
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
                            class="sm:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                            <i class="fa fa-plus text-base"></i>
                        </a>
                        <div
                            class="absolute z-10 hidden group-hover:flex bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-800 rounded shadow">
                            Tambah Jadwal
                        </div>
                        <a href="{{ route('aktivitasabsensi.create', ['user_id' => $selectedUserId]) }}"
                            class="hidden sm:flex items-center px-4 py-2 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                            + Tambah
                        </a>
                    </div>
                @endcan
            </div>


        </div>

        <!-- Absensi Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                <thead class="text-center text-xs sm:text-sm bg-green-300 text-green-900 uppercase">
                    <tr>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 whitespace-nowrap">Hari</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 whitespace-nowrap">Tanggal</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 whitespace-nowrap">Jam Kerja</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 min-w-[200px]">Rencana Kerja</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 min-w-[200px]">Laporan Kerja</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 whitespace-nowrap">Jam Lembur</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 min-w-[150px]">Deskripsi Lembur</th>
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 min-w-[150px]">Feedback</th>
                        {{-- @can('list-history-edit') --}}
                        <th scope="col" class="px-3 py-3 sm:px-6 sm:py-3 whitespace-nowrap sticky right-0 bg-green-300 z-10 shadow-l">Action</th>
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
                                                : 'bg-green-50')))) }} border-b border-green-300 hover:bg-green-200 transition duration-150">
                            <td class="px-3 py-2 sm:px-6 sm:py-4 font-medium text-green-900 whitespace-nowrap">
                                {{ $item['hari'] }}
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">{{ $item['tanggal'] }}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">{!! $item['jam_kerja'] !!}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 text-left">{!! $item['rencana_kerja'] !!}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 text-left">{!! $item['laporan_kerja'] !!}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">{!! $item['jam_lembur'] !!}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 text-left">{!! $item['laporan_lembur'] !!}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 text-left">{!! $item['feedback'] !!}</td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 sticky right-0 bg-inherit z-10 shadow-l">
                                <div class="flex gap-1 justify-center">
                                    @can('list-history-edit')
                                        @if (!is_null($item['id']))
                                            <a href="{{ route('aktivitasabsensi.edit', $item['id']) }}"
                                                class="text-success-900 p-1.5 sm:px-3 sm:py-2 rounded-md border bg-white/50 hover:bg-success-300 transition"
                                                data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                                <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    @if (!is_null($item['id']))
                                        <a href="{{ route('aktivitasabsensi.show', $item['id']) }}"
                                            class="text-success-900 p-1.5 sm:px-3 sm:py-2 rounded-md border bg-white/50 hover:bg-success-300 transition"
                                            data-tooltip-target="tooltip-show-{{ $item['id'] }}">
                                            <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500 italic">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

