<header class="z-40 sm:z-50 fixed w-full">
    <nav class="bg-gradient-to-r from-yellow-300 via-green-500 to-green-700 text-white px-4 py-4 shadow-xl">
        <div class="flex justify-between items-center mx-3">
            <!-- Kiri: Sidebar toggle + Logo -->
            <div class="flex items-center gap-2 sm:gap-0">
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2 text-md hover:text-success-100 me-1 transition duration-100 text-success-950 rounded-lg sm:hidden hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="/" class="flex items-center group">
                    <img src="{{ asset('img/logo.png') }}" class="mr-2 sm:mr-3 h-8 sm:h-9 transition-transform group-hover:scale-105" alt="Logo" />
                    <span class="self-center text-sm sm:text-xl font-bold whitespace-nowrap text-zinc-950 flex flex-col sm:block leading-tight">
                        <span>SIMPEG</span>
                        <span class="font-medium sm:ml-1">RSI BANJARNEGARA</span>
                    </span>
                </a>
            </div>

            <!-- Kanan: Mobile & Desktop -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <!-- ✅ Nama dan Foto Profil -->
                <div class="flex items-center">
                    <span class="hidden md:block font-medium text-white me-2 capitalize text-sm sm:text-base">
                        {{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('userprofile.index') }}"
                        class="text-white bg-success-950 rounded-full me-2 hover:bg-gray-100 transition duration-150 hover:text-success-950 px-1 py-1">
                        {!! auth()->user()->photo
                            ? '<img src="' .
                                asset('storage/photos/' . auth()->user()->photo) .
                                '" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-300">'
                            : '<div class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full border border-gray-300"><i class="fa-solid fa-user"></i></div>' !!}
                    </a>
                </div>

                <!-- ✅ Notifikasi: tampil di semua mode -->
                <div class="relative inline-block">
                    <livewire:notification />
                </div>

                <!-- ✅ Logout: tampil di semua mode -->
                <a href="{{ route('logout') }}"
                    class="text-white bg-success-950 rounded-full hover:bg-gray-100 hover:text-success-950 px-2 py-1.5 sm:px-3 sm:py-2 transition-colors duration-200">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs sm:text-base"></i>
                </a>
            </div>
        </div>
    </nav>
</header>
