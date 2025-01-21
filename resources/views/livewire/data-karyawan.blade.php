<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Data Karyawan</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Karyawan..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>
            <a href="{{ route('datakaryawan.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Karyawan
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">NIP</th>
                    <th scope="col" class="px-6 py-3">Alamat</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Formasi</th>
                    <th scope="col" class="px-6 py-3">Unit</th>
                    <th scope="col" class="px-6 py-3">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">{{ $user->nip ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->alamat ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->roles->pluck('name')->implode(', ') ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->jabatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->unitKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('detailkaryawan.show', ['detailkaryawan' => $user->id]) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data Karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4 flex justify-center items-center">
            <nav class="inline-flex shadow rounded-md" aria-label="Pagination">
                <!-- Tombol "Sebelumnya" -->
                @if ($users->onFirstPage())
                    <span class="px-3 py-2 border rounded-l-md bg-gray-300 text-gray-500 cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <button wire:click="previousPage"
                        class="px-3 py-2 border rounded-l-md bg-success-600 text-white hover:bg-success-700">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @endif

                <!-- Nomor Halaman -->
                @foreach ($users->links()->elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if (
                                $page == $users->currentPage() ||
                                    $page == 1 ||
                                    $page == $users->lastPage() ||
                                    abs($page - $users->currentPage()) <= 1)
                                @if ($page == $users->currentPage())
                                    <span class="px-3 py-2 border bg-success-600 text-white">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="px-3 py-2 border bg-white text-success-600 hover:bg-success-200">
                                        {{ $page }}
                                    </button>
                                @endif
                            @elseif ($page == 2 && $users->currentPage() > 3)
                                <span class="px-3 py-2 border bg-white text-success-600">...</span>
                            @elseif ($page == $users->lastPage() - 1 && $users->currentPage() < $users->lastPage() - 2)
                                <span class="px-3 py-2 border bg-white text-success-600">...</span>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                <!-- Tombol "Selanjutnya" -->
                @if ($users->hasMorePages())
                    <button wire:click="nextPage"
                        class="px-3 py-2 border rounded-r-md bg-success-600 text-white hover:bg-success-700">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                @else
                    <span class="px-3 py-2 border rounded-r-md bg-gray-300 text-gray-500 cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>

</div>
