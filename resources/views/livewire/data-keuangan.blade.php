    <div>
        <div class="flex justify-between py-2 mb-3">
            <div class="mb-4">
                <!-- Tulisan Keuangan -->
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold text-success-900">Keuangan</h1>
                    <!-- Filter Bulan & Tahun -->
                    <div class="flex flex-wrap gap-2">
                        <select wire:model.live="bulan"
                            class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select wire:model.live="tahun"
                            class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                            @foreach (range(now()->year - 5, now()->year) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                        @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEUANGAN')
                            <button wire:click="downloadTemplate"
                                class="text-yellow-900 bg-yellow-100 hover:bg-yellow-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                <i class="fas fa-download"></i> Download Template
                            </button>

                            {{-- @can('import-jadwal') --}}
                            <!-- Input untuk Import -->
                            <input type="file" wire:model="file" class="hidden" id="uploadFile">
                            <button type="button" onclick="document.getElementById('uploadFile').click();"
                                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                <i class="fas fa-file-excel"></i> Import Excel
                            </button>

                            <!-- Menampilkan Nama File -->
                            @if ($file)
                                <div class="mt-2 flex items-center space-x-2">
                                    <span
                                        class="text-sm text-green-700 font-medium">{{ $file->getClientOriginalName() }}</span>

                                    <!-- Tombol Hapus File -->
                                    <button type="button" wire:click="$set('file', null)"
                                        class="text-red-500 hover:text-red-700 font-medium text-sm">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </div>
                            @endif

                            <!-- Menampilkan Progress Upload -->
                            <div wire:loading wire:target="file" class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full">
                                    <div class="bg-green-500 text-xs leading-none py-1 text-center text-white"
                                        style="width: 0%;" x-data="{ progress: 0 }" x-init="$watch('progress', value => {
                                            setInterval(() => {
                                                if (progress < 100) progress += 10;
                                            }, 200);
                                        })">
                                        Loading...
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit Import -->
                            @if ($file)
                                <button type="button" wire:click="import"
                                    class="mt-2 text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                    Submit Import
                                </button>
                            @endif
                            {{-- @endcan --}}
                    </div>
                    @endif
                </div>

                <!-- Kotak Pencarian -->
                <div class="flex flex-wrap items-center gap-2 mt-4">
                    {{-- Input Pencarian --}}
                    <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                        class="rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

                    {{-- Pilih Unit --}}
                    <select wire:model.live="selectedUnit"
                        class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                        <option value="">-- Pilih Unit --</option>
                        @foreach ($units as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>

                    {{-- Pilih Jenis Karyawan --}}
                    <select wire:model.live="selectedJenisKaryawan"
                        class="rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                        <option value="">-- Semua Jenis Karyawan --</option>
                        @foreach ($jenisKaryawans as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>

                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEUANGAN')
                        {{-- Tombol Generate Slip Gaji --}}
                        <div class="flex items-center gap-2">
                            {{-- Tombol Slip Gaji --}}
                            <div class="relative">
                                <button type="button" onclick="Livewire.dispatch('openGenerateModal')"
                                    class="w-10 h-10 flex items-center justify-center rounded-md bg-blue-100 hover:bg-blue-600 transition"
                                    data-tooltip-target="tooltip-generate">
                                    <i class="fas fa-file-invoice-dollar text-blue-900 text-lg"></i>
                                </button>
                                <div id="tooltip-generate" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip mt-2 left-1/2 -translate-x-1/2">
                                    Generate Slip Gaji
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>

                            {{-- Tombol Export Excel --}}
                            <div class="relative">
                                <a href="{{ route('keuangan.export', [
                                    'bulan' => $bulan,
                                    'tahun' => $tahun,
                                    'unit' => $selectedUnit,
                                    'jenis' => $selectedJenisKaryawan,
                                    'keyword' => $search,
                                ]) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-md bg-green-100 hover:bg-green-600 transition"
                                    data-tooltip-target="tooltip-export-excel">
                                    <i class="fas fa-file-excel text-green-900 text-lg"></i>
                                </a>
                                <div id="tooltip-export-excel" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip mt-2 left-1/2 -translate-x-1/2">
                                    Export Excel Data
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

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
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">NIK</th>
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
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $user->nama_bersih }}
                            </td>
                            <td class="px-6 py-4">{{ $user->nik ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $user->alamat ?? '-' }}</td>
                            {{-- <td class="px-6 py-4">{{ $user->roles->pluck('name')->implode(', ') ?? '-' }}</td> --}}
                            <td class="px-6 py-4">
                                {{ $user->kategorifungsional && $user->kategorijabatan
                                    ? $user->kategorijabatan->nama . ' + ' . $user->kategorifungsional->nama
                                    : $user->kategorijabatan->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $user->unitKerja->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center relative">
                                <a href="{{ route('detailkeuangan.show', ['detailkeuangan' => $user->id]) }}"
                                    class="flex items-center justify-center w-10 h-10 rounded bg-[#006633] hover:bg-[#004d26] transition"
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
