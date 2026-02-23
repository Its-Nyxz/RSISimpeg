    <div>
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h1 class="text-2xl font-bold text-success-900">Keuangan</h1>

                <a href="{{ route('keuangan.urutan.user', $selectedJenisKaryawan) }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition shadow-sm group">
                    <i class="fas fa-sort-numeric-down text-gray-500 group-hover:text-white"></i>
                    <span class="font-medium">Edit No Urut</span>
                </a>
            </div>

            <div class="flex flex-wrap items-center gap-3 mb-4">
                <select wire:model.live="bulan"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <select wire:model.live="tahun"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    @foreach (range(now()->year - 5, now()->year) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>

                @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEUANGAN')
                    <button wire:click="downloadTemplate"
                        class="flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-yellow-100 text-yellow-900 hover:bg-yellow-600 hover:text-white transition">
                        <i class="fas fa-download mr-2"></i> Template
                    </button>

                    <div class="flex items-center gap-2">
                        <input type="file" wire:model="file" class="hidden" id="uploadFile">
                        <button type="button" onclick="document.getElementById('uploadFile').click();"
                            class="flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                            <i class="fas fa-file-excel mr-2"></i> Import
                        </button>
                    </div>
                @endif
            </div>

            @if ($file)
                <div class="flex items-center gap-2 mb-4 p-2 bg-success-50 rounded-lg border border-success-200 w-fit">
                    <span class="text-sm text-success-700 font-medium">{{ $file->getClientOriginalName() }}</span>
                    <button type="button" wire:click="import"
                        class="text-xs bg-success-600 text-white px-3 py-1 rounded hover:bg-success-700">Submit</button>
                    <button type="button" wire:click="$set('file', null)" class="text-red-500"><i
                            class="fas fa-times-circle"></i></button>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-3">
                {{-- Input Pencarian --}}
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                </div>

                {{-- Pilih Unit --}}
                <select wire:model.live="selectedUnit"
                    class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>

                {{-- Pilih Jenis Karyawan --}}
                <select wire:model.live="selectedJenisKaryawan"
                    class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    <option value="">-- Semua Jenis Karyawan --</option>
                    @foreach ($jenisKaryawans as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>

                @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEUANGAN')
                    <div class="flex items-center gap-2 ml-auto">
                        {{-- Tombol Generate --}}
                        <button type="button" onclick="Livewire.dispatch('openGenerateModal')"
                            class="px-4 h-10 flex items-center gap-2 rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition border border-blue-200">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span class="font-medium text-sm">Generate</span>
                        </button>

                        {{-- Tombol Export --}}
                        <a href="{{ route('keuangan.export', ['bulan' => $bulan, 'tahun' => $tahun, 'unit' => $selectedUnit, 'jenis' => $selectedJenisKaryawan, 'keyword' => $search]) }}"
                            class="px-4 h-10 flex items-center gap-2 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition border border-success-200">
                            <i class="fas fa-file-excel"></i>
                            <span class="font-medium text-sm">Export</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                window.addEventListener('openGenerateModal', () => {
                    window.dispatchEvent(new CustomEvent('open-generate-modal'));
                });
            </script>
        @endpush

        <div x-data="{ open: false }" x-show="open" x-cloak @open-generate-modal.window="open = true"
            class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">

            <div class="bg-white rounded-lg p-6 max-w-5xl w-full relative overflow-hidden">
                <h2 class="text-lg font-bold mb-4">Konfirmasi Slip Gaji</h2>

                <div class="overflow-x-auto max-h-[70vh] overflow-y-auto border border-gray-200 rounded">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-sm uppercase bg-success-400 text-success-900">
                            <tr>
                                <th class="p-2">Nama</th>
                                <th class="p-2">Unit</th>
                                <th class="p-2">Total Bruto</th>
                                <th class="p-2">Total Potongan</th>
                                <th class="p-2">Netto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @php
                                    $brutoModel = $user
                                        ->gajiBruto()
                                        ->where('bulan_penggajian', $bulan)
                                        ->where('tahun_penggajian', $tahun)
                                        ->first();

                                    $bruto = $brutoModel?->total_bruto ?? 0;
                                    $potongan = $brutoModel?->potongan->sum('nominal') ?? 0;
                                    $netto = $bruto - $potongan;
                                @endphp
                                <tr
                                    class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                    <td class="p-2">{{ $user->nama_bersih }}</td>
                                    <td class="p-2">{{ $user->unitKerja->nama ?? '-' }}</td>
                                    <td class="p-2">Rp {{ number_format($bruto, 0, ',', '.') }}</td>
                                    <td class="p-2">Rp {{ number_format($potongan, 0, ',', '.') }}</td>
                                    <td class="p-2">Rp {{ number_format($netto, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4 flex gap-2 justify-center items-center">
                    {{-- Previous Page Link --}}
                    @if (!$users->onFirstPage())
                        <button wire:click="previousPage" wire:loading.attr="disabled"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            &laquo; Sebelumnya
                        </button>
                    @endif

                    {{-- Pagination Numbers --}}
                    @php
                        $totalPages = $users->lastPage();
                        $currentPage = $users->currentPage();
                        $range = 3; // Range around current page
                    @endphp

                    {{-- First Page --}}
                    @if ($currentPage > $range + 1)
                        <button wire:click="gotoPage(1)"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            1
                        </button>
                        @if ($currentPage > $range + 2)
                            <span class="px-2 py-1 text-gray-500">...</span>
                        @endif
                    @endif

                    {{-- Pages Around Current Page --}}
                    @for ($page = max($currentPage - $range, 1); $page <= min($currentPage + $range, $totalPages); $page++)
                        @if ($page == $currentPage)
                            <span
                                class="px-2 py-1 bg-success-600 text-white rounded-md text-sm">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})"
                                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                                {{ $page }}
                            </button>
                        @endif
                    @endfor

                    {{-- Last Page --}}
                    @if ($currentPage < $totalPages - $range)
                        @if ($currentPage < $totalPages - $range - 1)
                            <span class="px-2 py-1 text-gray-500">...</span>
                        @endif
                        <button wire:click="gotoPage({{ $totalPages }})"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            {{ $totalPages }}
                        </button>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            Selanjutnya &raquo;
                        </button>
                    @endif
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button @click="open = false"
                        class="text-red-900 bg-red-100 hover:bg-red-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Cancel</button>
                    <button type="button"
                        onclick="confirmGenerate('Ingin generate slip gaji untuk dikirim ke Karyawan?', () => @this.call('confirmGenerateNetto'))"
                        class="text-blue-900 bg-blue-100 hover:bg-blue-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Generate</button>
                </div>
            </div>
        </div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-center text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">NIP</th>
                        <th scope="col" class="px-6 py-3">Alamat</th>
                        <th scope="col" class="px-6 py-3">Jabatan</th>
                        <th scope="col" class="px-6 py-3">Divisi / Unit Kerja</th>
                        <th scope="col" class="px-6 py-3">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">{{ $user->urutanKeuangan->urutan ?? '-' }}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $user->nama_bersih }}
                            </td>
                            <td class="px-6 py-4">{{ $user->nip ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $user->alamat ?? '-' }}</td>
                            {{-- <td class="px-6 py-4">{{ $user->roles->pluck('name')->implode(', ') ?? '-' }}</td> --}}
                            <td class="px-6 py-4">
                                @if ($user->kategorijabatan)
                                    {{ $user->kategorijabatan->nama }}
                                    @if ($user->kategorifungsional || $user->kategoriumum)
                                        ({{ $user->kategorifungsional?->nama }}{{ $user->kategorifungsional && $user->kategoriumum ? ' + ' : '' }}{{ $user->kategoriumum?->nama }})
                                    @endif
                                @elseif ($user->kategorifungsional || $user->kategoriumum)
                                    {{ $user->kategorifungsional?->nama }}{{ $user->kategorifungsional && $user->kategoriumum ? ' + ' : '' }}{{ $user->kategoriumum?->nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $user->unitKerja->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center relative">
                                <a href="{{ route('detailkeuangan.show', ['detailkeuangan' => $user->id]) }}"
                                    class="bg-success-700 text-white font-medium rounded-md px-3 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300"
                                    style="margin-left: 40px; border-radius: 20%;"
                                    data-tooltip-target="tooltip-keuangan-{{ $user->id }}">
                                    <i class="fa-solid fa-magnifying-glass text-lg text-white"></i>
                                </a>
                                <div id="tooltip-keuangan-{{ $user->id }}" role="tooltip"
                                    class="absolute left-1/2 -translate-x-1/2 mt-2 z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Detail
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4">Tidak ada data Keuangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex gap-2 justify-center items-center">
            {{-- Previous Page Link --}}
            @if (!$users->onFirstPage())
                <button wire:click="previousPage" wire:loading.attr="disabled"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    &laquo; Sebelumnya
                </button>
            @endif

            {{-- Pagination Numbers --}}
            @php
                $totalPages = $users->lastPage();
                $currentPage = $users->currentPage();
                $range = 3; // Range around current page
            @endphp

            {{-- First Page --}}
            @if ($currentPage > $range + 1)
                <button wire:click="gotoPage(1)"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    1
                </button>
                @if ($currentPage > $range + 2)
                    <span class="px-2 py-1 text-gray-500">...</span>
                @endif
            @endif

            {{-- Pages Around Current Page --}}
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

            {{-- Last Page --}}
            @if ($currentPage < $totalPages - $range)
                @if ($currentPage < $totalPages - $range - 1)
                    <span class="px-2 py-1 text-gray-500">...</span>
                @endif
                <button wire:click="gotoPage({{ $totalPages }})"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    {{ $totalPages }}
                </button>
            @endif

            {{-- Next Page Link --}}
            @if ($users->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    Selanjutnya &raquo;
                </button>
            @endif
        </div>
    </div>
