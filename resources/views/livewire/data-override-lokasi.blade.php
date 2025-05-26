<div class="p-4 space-y-4">
    <h1 class="text-xl font-bold">Riwayat Absensi Manual (Lokasi Tidak Valid)</h1>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        {{-- Kolom pencarian --}}
        <div class="w-full sm:w-1/3">
            <input type="text" wire:model.live="search" placeholder="Cari nama..."
                class="border rounded px-3 py-2 w-full" />
        </div>

        {{-- Dropdown bulan & tahun --}}
        <div class="flex items-center space-x-2">
            <select wire:model="bulan" class="border rounded px-3 py-2">
                @foreach (range(1, 12) as $bln)
                    <option value="{{ str_pad($bln, 2, '0', STR_PAD_LEFT) }}">
                        {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model="tahun" class="border rounded px-3 py-2">
                @foreach (range(now()->year, now()->year - 5) as $th)
                    <option value="{{ $th }}">{{ $th }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <table class="w-full table-auto mt-4 border text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Tanggal</th>
                <th class="p-2 border">Alasan</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @forelse ($overrides as $index => $item)
                <tr class="border-t">
                    <td class="p-2 border">
                        {{ $loop->iteration + ($overrides->currentPage() - 1) * $overrides->perPage() }}</td>
                    <td class="p-2 border">{{ $item->user->name }}</td>
                    <td class="p-2 border">{{ $item->created_at->format('d M Y H:i') }}</td>
                    <td class="p-2 border">{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $overrides->links() }}
    </div>
</div>
