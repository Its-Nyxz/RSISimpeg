<div class="container mx-auto mt-6 p-6 bg-white shadow-md rounded-lg">
    <!-- Header -->
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-green-700">List History Absensi</h1>
    </div>

    <!-- Filter dan Tombol Tambah -->
    <div class="flex justify-between items-center mb-3">
        <div class="flex space-x-4">
            @can('list-history-user')
                <select wire:model.live="selectedUserId" class="border-2 border-gray-700 rounded-md p-2">
                    @foreach ($subordinates as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            @endcan

            <select wire:model.live="month" class="border-2 border-gray-700 rounded-md p-2">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="year" class="border-2 border-gray-700 rounded-md p-2">
                @foreach (range(date('Y') - 3, date('Y') + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>

        </div>

        <div class="space-x-2">
            <button wire:click="exportPdfHistory"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                <i class="fas fa-download"></i> Export
            </button>
            <!-- Tombol Tambah -->
            @can('list-history-create')
                <a href="{{ route('aktivitasabsensi.create', ['user_id' => $selectedUserId]) }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    + Tambah
                </a>
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
                    @can('list-history-edit')
                        <th scope="col" class="px-6 py-3">Action</th>
                    @endcan
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
