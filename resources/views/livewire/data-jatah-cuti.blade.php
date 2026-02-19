<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Jatah Cuti Tahunan</h1>
        <!-- Kontrol Aksi -->
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <!-- Mobile Icon Only -->
            <a href="{{ route('jatahcuti.create') }}"
                class="sm:hidden inline-flex items-center justify-center p-2.5 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shrink-0"
                aria-label="Tambah Jatah Cuti Tahunan" data-tooltip-target="tooltip-cuti" data-tooltip-placement="top">
                <i class="fa fa-plus"></i>
            </a>
            <!-- Tooltip -->
            <div id="tooltip-cuti" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                Tambah Jatah Cuti Tahunan
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <!-- Desktop Button -->
            <a href="{{ route('jatahcuti.create') }}"
                class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                + Tambah Jatah Cuti Tahunan
            </a>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Tahun</th>
                    <th scope="col" class="px-6 py-3">Jumlah</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($cutis as $cuti)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $cuti['tahun'] ? \Carbon\Carbon::createFromDate($cuti['tahun'], 1, 1)->translatedFormat('Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $cuti['jumlah_cuti'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('jatahcuti.edit', $cuti['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-cuti-{{ $cuti['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-cuti-{{ $cuti['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                Ubah Data Jatah Cuti
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-4">Tidak ada data Jatah Cuti.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
