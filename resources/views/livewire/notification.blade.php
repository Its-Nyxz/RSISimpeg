<div id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" data-dropdown-trigger="hover"
    class="text-white bg-success-950 rounded-full hover:bg-success-700 px-3 py-2 transition duration-200 uppercase relative">

    <!-- Wrapper Ikon Bell dan Badge -->
    <div class="relative inline-block">
        <!-- Ikon Bell -->
        <i class="fas fa-bell"></i>

        @if (auth()->user()->unreadNotifications->count() > 0)
            <!-- Badge untuk jumlah notifikasi -->
            <span
                class="absolute -top-4 right-2  bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </div>

    <!-- Dropdown Notifikasi -->
    <div id="dropdownNotification" class="z-10 hidden bg-white shadow-2xl max-w-lg w-96 ">

        <ul class="font-normal text-sm text-gray-700 capitalize max-h-60 w-full overflow-y-auto"
            aria-labelledby="dropdownNotificationButton">

            @forelse (auth()->user()->notifications as $notification)
                <li class="appearance-none list-none"> <!-- ✅ Tambahkan list-none di sini -->
                    <div wire:click="markAsRead('{{ $notification->id }}','{{ $notification->data['url'] }}')"
                        class="flex cursor-pointer group justify-between px-4 py-3 hover:bg-success-950 transition duration-200 hover:text-white ">

                        <!-- Isi Notifikasi -->
                        <div>{!! $notification->data['message'] !!}</div>

                        <!-- Status "Baru" -->
                        <div>
                            @if (!$notification->read_at)
                                <span class="text-xs text-green-500 group-hover:text-white font-semibold ml-2">
                                    Baru
                                </span>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-3 text-gray-500 text-sm text-center list-none"> <!-- ✅ Tambahkan list-none -->
                    Tidak ada notifikasi
                </li>
            @endforelse
        </ul>
    </div>
</div>
