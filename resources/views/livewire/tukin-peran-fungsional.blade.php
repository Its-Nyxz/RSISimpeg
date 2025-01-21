<div>
    <div class="flex justify-between py-2 mb-3">
                <h1 class="text-2xl font-bold text-success-900">Point Peran Fungsional</h1>
                <div class="flex justify-between items-center gap-4 mb-3">
                    <div class="flex-1">
                        <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Point Peran Fungsional..."
                            class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                    </div>
    
                    <a href="#"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        + Tambah Point Peran Fungsional
                    </a>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Point</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr
                                class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                    {{ $item['nama'] }}
                                </td>
                                <td class="px-6 py-4">{{ $item['poin'] }}</td>
                                <td class="px-6 py-4">
                                    <a href="/poposionalitas/edit/{{ $item['id'] }}"
                                        class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                        data-tooltip-target="tooltip-jadwal-{{ $item['id'] }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                        Ubah Point Peran Fungsional
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center px-6 py-4">Tidak ada data Peran Fungsional.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>