<div class="container mx-auto mt-6 p-6 bg-white shadow-md rounded-lg">
    <!-- Header -->
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-green-700 mb-2">List History</h1>
        <div class="flex gap-4 mb-4">
    <select wire:model.live="bulan" class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
        @foreach ($bulanOptions as $m)
            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
        @endforeach
    </select>

    <select wire:model.live="tahun" class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
        @foreach ($tahunOptions as $y)
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
                    <th scope="col" class="px-6 py-3">
                        Jam Kerja
                    </th>
                    <th scope="col" class="px-6 py-3">Rencana Kerja</th>
                    <th scope="col" class="px-6 py-3">Laporan Kerja</th>
                    <th scope="col" class="px-6 py-3">Feedback</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr class="odd:bg-green-50 even:bg-green-100 border-b border-green-300 hover:bg-green-200">
                        <td class="px-6 py-4 font-medium text-green-900 whitespace-nowrap">{{ $item['hari'] }}</td>
                        <td class="px-6 py-4">{{ $item['tanggal'] }}</td>
                        <td class="px-6 py-4">{{ $item['jam_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['rencana_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['laporan_kerja'] }}</td>
                        <td class="px-6 py-4">{{ $item['feedback'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>