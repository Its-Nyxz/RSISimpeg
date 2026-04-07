<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Peran Fungsional</h1>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Peran Fungsional..."
                class="flex-1 sm:w-64 rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Unit</th>
                    <th scope="col" class="px-6 py-3">Peran Pokok</th>
                    <th scope="col" class="px-6 py-3">Pelatihan</th>
                    <th scope="col" class="px-6 py-3">Keterlibatan Tim</th>
                    <th scope="col" class="px-6 py-3">Posisi Tim</th>
                    <th scope="col" class="px-6 py-3">Shift</th>
                    <th scope="col" class="px-6 py-3">PK</th>
                    <th scope="col" class="px-6 py-3">Profit Centre / Cost Centre</th>
                    <th scope="col" class="px-6 py-3">Total Peran Fungsional</th>
                    <th scope="col" class="px-6 py-3">Rincian Tim</th>
                    <th scope="col" class="px-6 py-3">Sertifikat Tim</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td class="px-6 py-4">
                            {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $item->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $item->unitKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">0</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">0</td>
                        <td class="px-6 py-4">-</td>
                        <td class="px-6 py-4">-</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center px-6 py-4">
                            Tidak ada data Peran Fungsional.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex gap-2 justify-center items-center">
        @if (!$data->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        @php
            $totalPages = $data->lastPage();
            $currentPage = $data->currentPage();
            $range = 3;
        @endphp

        @if ($currentPage > $range + 1)
            <button wire:click="gotoPage(1)"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                1
            </button>
            @if ($currentPage > $range + 2)
                <span class="px-2 py-1 text-gray-500">...</span>
            @endif
        @endif

        @for ($page = max($currentPage - $range, 1); $page <= min($currentPage + $range, $totalPages); $page++)
            @if ($page == $currentPage)
                <span class="px-2 py-1 bg-success-600 text-white rounded-md text-sm">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }})"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    {{ $page }}
                </button>
            @endif
        @endfor

        @if ($currentPage < $totalPages - $range)
            @if ($currentPage < $totalPages - $range - 1)
                <span class="px-2 py-1 text-gray-500">...</span>
            @endif
            <button wire:click="gotoPage({{ $totalPages }})"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                {{ $totalPages }}
            </button>
        @endif

        @if ($data->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>
