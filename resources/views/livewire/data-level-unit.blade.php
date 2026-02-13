<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Level Unit</h1>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Level Unit..."
                class="flex-1 sm:w-64 rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            <!-- Mobile Icon Only -->
            <a href="{{ route('levelunit.create') }}"
                class="sm:hidden inline-flex items-center justify-center p-2.5 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shrink-0"
                aria-label="Tambah Level Unit">
                <i class="fa fa-plus"></i>
            </a>
            <!-- Desktop Button -->
            <a href="{{ route('levelunit.create') }}"
                class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                + Tambah Level Unit
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Unit Kerja</th>
                    <th scope="col" class="px-6 py-3">Level Unit Kerja</th>
                    <th scope="col" class="px-6 py-3">Point Unit</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($levelunit as $data)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td class="px-6 py-4">
                            {{ ($levelunit->currentPage() - 1) * $levelunit->perPage() + $loop->iteration }}
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $data->unitkerja->nama }}
                        </td>
                        <td class="px-6 py-4">{{ $data->levelpoint->nama }}</td>
                        <td class="px-6 py-4">{{ $data->levelpoint->point }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('levelunit.edit', $data['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data Level Unit.</td>
                    </tr>
                @endforelse
            </tbody>            
        </table>
    </div>

    <div class="mt-4 flex gap-2 justify-center items-center">
        @if (!$levelunit->onFirstPage())
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                &laquo; Sebelumnya
            </button>
        @endif

        @for ($page = max($levelunit->currentPage() - 3, 1); $page <= min($levelunit->currentPage() + 3, $levelunit->lastPage()); $page++)
            @if ($page == $levelunit->currentPage())
                <span class="px-2 py-1 bg-success-600 text-white rounded-md text-sm">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }})"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    {{ $page }}
                </button>
            @endif
        @endfor

        @if ($levelunit->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                Selanjutnya &raquo;
            </button>
        @endif
    </div>
</div>

