<header class="z-40 sm:z-50 fixed w-full top-0 start-0">
    <nav
        class="bg-gradient-to-r from-yellow-400 via-success-500 to-success-700 text-white px-4 py-3 shadow-lg border-b border-white/10">
        <div class="flex justify-between items-center mx-auto w-full">
            <!-- Kiri: Sidebar toggle + Logo -->
            <div class="flex items-center gap-3">
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 transition-colors">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.png') }}"
                        class="h-9 w-auto sm:h-10 transition-transform duration-300 group-hover:scale-110" alt="Logo" />
                    <div class="flex flex-col hidden sm:flex">
                        <span
                            class="self-center text-lg sm:text-xl font-bold whitespace-nowrap text-white leading-none tracking-wide">
                            SIMPEG
                        </span>
                        <span class="text-[0.65rem] sm:text-xs font-medium text-white/90 leading-none tracking-wider">
                            RSI BANJARNEGARA
                        </span>
                    </div>
                </a>
            </div>

            <!-- Kanan: Mobile & Desktop -->
            <div class="flex items-center gap-3 sm:gap-4">

                <!-- User Profile -->
                <div class="flex items-center gap-3">
                    <span class="hidden sm:block font-medium text-white capitalize text-sm tracking-wide">
                        {{ auth()->user()->name }}
                    </span>

                    <a href="{{ route('userprofile.index') }}" class="relative group focus:outline-none">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/photos/' . auth()->user()->photo) }}"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-white/40 shadow-sm transition-all duration-300 group-hover:border-white group-hover:scale-105"
                                alt="Profile">
                        @else
                            <div
                                class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-white/10 backdrop-blur-sm rounded-full border-2 border-white/40 text-white shadow-sm transition-all duration-300 group-hover:bg-white/20 group-hover:border-white group-hover:scale-105">
                                <i class="fa-solid fa-user text-sm sm:text-base"></i>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Notifikasi -->
                <div class="relative flex items-center">
                    <livewire:notification />
                </div>

                <!-- Logout -->
                <a href="{{ route('logout') }}"
                    class="text-white bg-white/10 hover:bg-red-500 hover:text-white rounded-full w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center transition-all duration-300 border border-transparent hover:border-red-400 shadow-sm group"
                    title="Logout">
                    <i
                        class="fa-solid fa-arrow-right-from-bracket text-sm sm:text-base group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </nav>
</header>