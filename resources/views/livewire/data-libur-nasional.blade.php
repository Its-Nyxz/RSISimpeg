<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Status Absensi</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Status..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>
            <!-- Tombol Tambah Status -->
            <a href="{{ route('liburnasional.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Hari Libur
            </a>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($holidays as $holiday)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                            {{ $holiday['date'] ? \Carbon\Carbon::parse($holiday['date'])->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $holiday['description'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('liburnasional.edit', $holiday['id']) }}"
                                class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                                <i class="fa-solid fa-pen" style="margin-right: 30px;"></i>
                            </a>
                            <button wire:click="confirmDelete({{ $holiday['id'] }})"
                                class="text-red-600 px-3 py-2 rounded-md border border-red-600 hover:bg-red-600 hover:text-white">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-4">Tidak ada data Status Absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    @if ($holidayIdToDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-lg font-semibold text-gray-900">Apakah Anda yakin ingin menghapus data ini?</h2>
                <div class="mt-4 flex justify-center gap-4">
                    <button wire:click="deleteHoliday"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Ya, Hapus</button>
                    <button wire:click="$set('holidayIdToDelete', null)"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Batal</button>
                </div>
            </div>
        </div>
    @endif
</div>
