<div>
    <div class="py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Notifikasi</h1>
    </div>

    <div class="flex flex-wrap gap-3 justify-start md:justify-start w-full md:w-auto mb-3">
        <select wire:model.live="filterStatus"
            class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
            <option value="all">Semua Pesan</option>
            <option value="unread">Belum Dibaca</option>
            <option value="read">Sudah Dibaca</option>
        </select>
    </div>

    <div class="bg-white shadow-2xl rounded-lg w-full overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Pesan
                    </th>
                    <th scope="col" class="px-6 py-3">Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3">Status
                    </th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if ($notifications && count($notifications) > 0)
                    @foreach ($notifications as $notification)
                        <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{!! $notification->data['message'] ?? 'Tidak ada pesan' !!}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $notification->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (is_null($notification->read_at))
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Belum Dibaca
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Sudah Dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if (isset($notification->data['url']))
                                    <button wire:click="markAsRead('{{ $notification->id }}','{{ $notification->data['url'] }}')"
                                        class="bg-success-700 text-white font-medium rounded-md px-3 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                @else
                                    <span class="text-gray-400">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada notifikasi
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <x-responsive-pagination :data="$notifications" />
</div>