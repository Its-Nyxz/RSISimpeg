<div class="relative">
    <div class="flex justify-between py-2 mb-3">
        <h2 class="text-2xl font-bold text-success-900">History {{ ucfirst(str_replace('_', ' ', $tipe)) }}</h2>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Alasan</th>
                    @if ($tipe === 'tukar_jadwal')
                        <th scope="col" class="px-6 py-3">Shift</th>
                    @endif
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataPengajuan as $pengajuan)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">{{ $pengajuan->tanggal }}</td>
                        <td class="px-6 py-4">{{ $pengajuan->keterangan }}</td>
                        @if ($tipe === 'tukar_jadwal')
                            <td class="px-6 py-4">{{ $pengajuan->shift->nama_shift ?? '-' }}</td>
                        @endif
                        <td class="px-6 py-4">
                            {{ $pengajuan->statusCuti->nama_status ?? "-"}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tipe === 'tukar_jadwal' ? 4 : 3 }}" class="text-center px-6 py-4">
                            Tidak ada pengajuan {{ $tipe }} ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>