<div>
    <h2 class="text-2xl font-bold mb-4">
        List Pengajuan {{ ucfirst(str_replace('_', ' ', $tipe)) }}
    </h2>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2">Tanggal</th>
                <th class="border border-gray-300 p-2">Alasan</th>
                @if ($tipe === 'tukar_jadwal')
                    <th class="border border-gray-300 p-2">Shift</th>
                @endif
                <th class="border border-gray-300 p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataPengajuan as $pengajuan)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $pengajuan->tanggal }}</td>
                    <td class="border border-gray-300 p-2">{{ $pengajuan->alasan }}</td>
                    @if ($tipe === 'tukar_jadwal')
                        <td class="border border-gray-300 p-2">
                            {{ $pengajuan->shift ?? '-' }}
                        </td>
                    @endif
                    <td class="border border-gray-300 p-2">
                        <button wire:click="delete({{ $pengajuan->id }})"
                            class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $tipe === 'tukar_jadwal' ? 4 : 3 }}" class="text-center py-4">
                        Tidak ada pengajuan {{ $tipe }} ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
