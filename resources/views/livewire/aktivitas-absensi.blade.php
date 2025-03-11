<div class="container mx-auto mt-6 p-6 bg-white shadow-md rounded-lg">
    <!-- Header -->
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-green-700 mb-2">List History Absensi</h1>
        <div class="flex space-x-4">
            <!-- Pilihan User (Hanya jika user adalah parent) -->
            @if ($isParent)
                <select wire:model.live="selectedUserId" class="border-2 border-gray-700 rounded-md p-2">
                    @foreach ($subordinates as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            @endif

            <!-- Pilihan Bulan -->
            <select wire:model.live="month" class="border-2 border-gray-700 rounded-md p-2">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <!-- Pilihan Tahun -->
            <select wire:model.live="year" class="border-2 border-gray-700 rounded-md p-2">
                @foreach (range(date('Y') - 3, date('Y') + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
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
                    <th scope="col" class="px-6 py-3">Feedback</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr
                        class="{{ $item['is_holiday'] ? 'bg-red-200' : ($loop->even ? 'bg-green-100' : 'bg-green-50') }} border-b border-green-300 hover:bg-green-200">
                        <td class="px-6 py-4 font-medium text-green-900 whitespace-nowrap">
                            {{ $item['hari'] }}
                        </td>
                        <td class="px-6 py-4">{{ $item['tanggal'] }}</td>
                        <td class="px-6 py-4">{{ $item['jam_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['rencana_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['laporan_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['feedback'] }}</td>
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