<x-body>
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-2 mb-3">
            <h1 class="text-2xl font-bold text-success-900">Master Cuti</h1>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">No</th>
                        <th scope="col" class="px-6 py-3 text-left">Jenis Cuti</th>
                           <th scope="col" class="px-6 py-3 text-left">Status</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jenisCutis as $cuti)
                        <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4 font-medium text-success-900 whitespace-nowrap text-left">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-medium text-success-900 text-left">
                                {{ $cuti->nama_cuti }}
                            </td>

                            <td class="px-6 py-4 text-left">
                                @if ($cuti->aktif)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-900">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <form action="{{ route('mastercuti.status', $cuti->id) }}"
                                    method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="px-3 py-2 rounded-md border border-success-300 text-success-900 hover:bg-slate-300 transition">
                                        {{ $cuti->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center px-6 py-4">
                                Belum ada data jenis cuti.
                            </td>
                        </tr>  
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-body>
