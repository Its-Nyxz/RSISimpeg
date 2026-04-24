<div class="container mx-auto mt-4 sm:mt-6 p-4 sm:p-6 bg-white shadow-md rounded-lg">
    <div class="py-2 mb-4 border-b border-gray-100">
        <h1 class="text-xl sm:text-2xl font-bold text-green-700">List History Absensi</h1>
    </div>

    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-6">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 w-full xl:w-auto">
            @can('list-history-user')
                <div class="col-span-2 md:col-span-1">
                    @if ($canAccessAllUnits)
                        <x-dropdown-unit :units="$units" model="selectedUnitId" />
                    @else
                        <div class="border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50 text-gray-600 w-full truncate">
                            <i class="fa-solid fa-building mr-1 text-xs"></i> {{ auth()->user()->unitKerja->nama ?? 'Unit Unknown' }}
                        </div>
                    @endif
                </div>

                <div class="col-span-2 md:col-span-1">
                    <select wire:model.live="selectedUserId"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 h-full w-full transition duration-150">
                        @forelse ($subordinates as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @empty
                            <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                        @endforelse
                    </select>
                </div>
            @endcan

            <select wire:model.live="month"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 w-full transition duration-150">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">
                        {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="year"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-success-500 w-full transition duration-150">
                @foreach (range(date('Y') - 3, date('Y') + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-start sm:justify-end">
            <button wire:click="exportPdfHistory"
                wire:loading.attr="disabled" 
                class="flex items-center justify-center gap-2 px-3 py-2 text-sm rounded-lg font-medium bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                title="Export PDF">
                
                <i class="fas fa-file-pdf" wire:loading.remove wire:target="exportPdfHistory"></i>
                
                <i class="fas fa-spinner fa-spin" wire:loading wire:target="exportPdfHistory"></i>
                
                <span class="hidden sm:inline">
                    <span wire:loading.remove wire:target="exportPdfHistory">PDF</span>
                    <span wire:loading wire:target="exportPdfHistory">Exporting...</span>
                </span>
            </button>

            <button wire:click="exportExcelHistory"
                wire:loading.attr="disabled"
                class="flex items-center justify-center gap-2 px-3 py-2 text-sm rounded-lg font-medium bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                title="Export Excel">
                
                <i class="fas fa-file-excel" wire:loading.remove wire:target="exportExcelHistory"></i>
                
                <i class="fas fa-spinner fa-spin" wire:loading wire:target="exportExcelHistory"></i>
                
                <span class="hidden sm:inline">
                    <span wire:loading.remove wire:target="exportExcelHistory">Excel</span>
                    <span wire:loading wire:target="exportExcelHistory">Exporting...</span>
                </span>
            </button>

            @if ($canExportAll)
                <button wire:click="openExportAllModal"
                    class="flex items-center justify-center gap-2 px-3 py-2 text-sm rounded-lg font-medium bg-indigo-100 text-indigo-900 hover:bg-indigo-600 hover:text-white transition shadow-sm"
                    title="Export Semua Pegawai">
                    <i class="fas fa-users-gear"></i>
                    <span class="hidden sm:inline">All</span>
                </button>
            @endif

            @can('list-history-create')
                <a href="{{ route('aktivitasabsensi.create', ['user_id' => $selectedUserId]) }}"
                    class="flex items-center justify-center gap-2 px-3 py-2 text-sm rounded-lg font-medium bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shadow-sm"
                    title="Tambah Jadwal">
                    <i class="fa fa-plus"></i>
                    <span class="hidden sm:inline">Tambah</span>
                </a>
            @endcan
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-200">
        <table class="w-full text-xs sm:text-sm text-center text-gray-700">
            <thead class="bg-green-300 text-green-900 uppercase font-bold sticky top-0">
                <tr>
                    <th class="px-3 py-3 whitespace-nowrap">Hari</th>
                    <th class="px-3 py-3 whitespace-nowrap">Tanggal</th>
                    <th class="px-3 py-3 whitespace-nowrap">Jam Kerja</th>
                    <th class="px-3 py-3 min-w-[200px]">Rencana</th>
                    <th class="px-3 py-3 min-w-[200px]">Laporan</th>
                    <th class="px-3 py-3 whitespace-nowrap">Lembur</th>
                    <th class="px-3 py-3 min-w-[150px]">Ket. Lembur</th>
                    <th class="px-3 py-3 min-w-[150px]">Feedback</th>
                    <th class="px-3 py-3 whitespace-nowrap sticky right-0 bg-green-300 z-10 shadow-[-4px_0_10px_rgba(0,0,0,0.05)]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-green-200">
           
                @forelse ($items as $item)

                    <tr class="{{ $item['is_holiday'] ? 'bg-red-100' : ($item['is_lembur'] ? 'bg-yellow-100' : ($item['is_dinas'] ? 'bg-blue-100' : ($item['late'] ? 'bg-red-300' : ($loop->even ? 'bg-green-50' : 'bg-white')))) }} hover:bg-green-200 transition duration-75">
                        <td class="px-3 py-4 font-semibold text-green-900">{{ $item['hari'] }}</td>
                        <td class="px-3 py-4 whitespace-nowrap">{{ $item['tanggal'] }}</td>
                        <td class="px-3 py-4 whitespace-nowrap">{!! $item['jam_kerja'] !!}</td>
                        <td class="px-3 py-4 text-left leading-relaxed">{!! $item['rencana_kerja'] !!}</td>
                        <td class="px-3 py-4 text-left leading-relaxed">
                            {!! $item['nama_shift'] == "L" ? '<span class="italic text-red-600 font-medium">Libur</span>' : $item['laporan_kerja'] !!}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">{!! $item['jam_lembur'] !!}</td>
                        <td class="px-3 py-4 text-left">{!! $item['laporan_lembur'] !!}</td>
                        <td class="px-3 py-4 text-left italic text-gray-500">{!! $item['feedback'] !!}</td>
                        <td class="px-3 py-4 sticky right-0 bg-inherit z-10 shadow-[-4px_0_10px_rgba(0,0,0,0.05)]">
                            <div class="flex gap-1.5 justify-center">
                                @can('list-history-edit')
                                    @if ($item['id'])
                                        <a href="{{ route('aktivitasabsensi.edit', $item['id']) }}"
                                            class="bg-white/80 p-2 rounded-md border border-success-200 text-success-900 hover:bg-success-600 hover:text-white transition shadow-sm">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                    @endif
                                @endcan
                                @if ($item['id'])
                                    <a href="{{ route('aktivitasabsensi.show', $item['id']) }}"
                                        class="bg-white/80 p-2 rounded-md border border-success-200 text-success-900 hover:bg-success-600 hover:text-white transition shadow-sm">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-gray-400 italic bg-gray-50">
                            <i class="fa-solid fa-inbox block text-2xl mb-2"></i>
                            Tidak ada data absensi ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showExportModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="bg-success-700 p-4 text-white">
                    <h2 class="text-lg font-bold">Export Laporan Pegawai</h2>
                </div>
                <div class="p-6">
                    <div class="max-h-60 overflow-y-auto border border-gray-100 rounded-lg p-3 bg-gray-50 mb-4">
                        @foreach ($allUsers as $id => $name)
                            <label class="flex items-center gap-3 p-2 hover:bg-white rounded cursor-pointer transition">
                                <input type="checkbox" wire:model="exportSelectedUsers" value="{{ $id }}"
                                    class="w-4 h-4 text-success-600 border-gray-300 rounded focus:ring-success-500 accent-success-700">
                                <span class="text-sm text-gray-700">{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-2">
                        <button wire:click="toggleSelectAll" class="text-sm font-medium text-blue-600 hover:text-blue-800 px-3">
                            Pilih Semua
                        </button>
                        <div class="flex gap-2">
                            <button wire:click="$set('showExportModal', false)"
                                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold transition">
                                Batal
                            </button>
                            <button wire:click="exportSelected"
                                class="px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-semibold transition shadow-md">
                                Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>