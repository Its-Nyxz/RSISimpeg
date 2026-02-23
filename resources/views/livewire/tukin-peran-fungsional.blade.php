<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Point Peran Fungsional</h1>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="flex-1 sm:w-64">
                <input type="text" wire:keyup="updateSearch($event.target.value)" 
                    placeholder="Cari Point..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <div class="flex items-center gap-2">
                <a href="#"
                    class="sm:hidden inline-flex items-center justify-center p-2.5 rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition shrink-0"
                    aria-label="Tambah Point" data-tooltip-target="tooltip-tambah-point"
                    data-tooltip-placement="top">
                    <i class="fa fa-plus"></i>
                </a>
                <div id="tooltip-tambah-point" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Tambah Point Peran
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <a href="#"
                    class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm rounded-lg font-medium whitespace-nowrap bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah Point Peran
                </a>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3 w-16">No</th>
                    <th scope="col" class="px-6 py-3">Nama Peran</th>
                    <th scope="col" class="px-6 py-3">Point</th>
                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300 transition">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $item['nama'] }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-success-800">
                            {{ $item['poin'] }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <a href="#"
                                    class="text-success-900 px-3 py-2 rounded-md border border-success-300 hover:bg-slate-300 transition"
                                    data-tooltip-target="tooltip-edit-{{ $item['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-edit-{{ $item['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Point
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-10 text-gray-500 italic">
                            Tidak ada data Point Peran Fungsional.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>