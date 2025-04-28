<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Jatah Cuti Tahunan</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            {{-- <div>
                <select wire:model="year" wire:change="updateYear($event.target.value)"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600">
                    @foreach (range(now()->year - 3, now()->year) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div> --}}
            {{-- <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Hari..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div> --}}
            <a href="{{ route('jatahcuti.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
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
