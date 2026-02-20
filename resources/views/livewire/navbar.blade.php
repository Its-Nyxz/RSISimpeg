<header class="z-40 sm:z-50 fixed w-full shadow-lg">
    <nav class="bg-gradient-to-r from-yellow-300 via-green-500 to-green-700 text-white px-2 sm:px-4 py-2.5 sm:py-4 transition-all duration-300">
        <div class="flex justify-between items-center max-w-full mx-auto">
            
            <div class="flex items-center min-w-0 flex-shrink">
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2 text-lg text-success-950 rounded-lg sm:hidden hover:bg-success-700 hover:text-white focus:outline-none transition duration-150">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                
                <a href="/" class="flex items-center group min-w-0">
                    <img src="{{ asset('img/logo.png') }}" class="mr-2 h-7 sm:h-9 transition-transform group-hover:scale-105 flex-shrink-0" alt="Logo" />
                    <div class="flex flex-col sm:flex-row sm:items-baseline leading-none sm:leading-tight overflow-hidden">
                        <span class="text-xs sm:text-xl font-extrabold text-zinc-950 tracking-tight">SIMPEG</span>
                        <span class="text-[10px] sm:text-lg font-medium text-zinc-900 sm:ml-1 truncate">RSI BANJARNEGARA</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-3 ml-2 flex-shrink-0">
                
                <div class="hidden lg:flex items-center border-r border-white/20 pr-3 mr-1">
                    <span class="font-semibold text-white capitalize text-sm truncate max-w-[150px]">
                        {{ auth()->user()->name }}
                    </span>
                </div>

                <a href="{{ route('userprofile.index') }}"
                    class="text-white bg-success-950 rounded-full hover:bg-white hover:text-success-950 transition-all duration-200 p-0.5 sm:p-1 flex-shrink-0 shadow-md">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/photos/' . auth()->user()->photo) }}" 
                             alt="Profile" 
                             class="w-7 h-7 sm:w-9 sm:h-9 rounded-full object-cover border border-white/50">
                    @else
                        <div class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center bg-success-800 rounded-full border border-white/50 text-white">
                            <i class="fa-solid fa-user text-[10px] sm:text-sm"></i>
                        </div>
                    @endif
                </a>

                <div class="relative flex-shrink-0">
                    <livewire:notification />
                </div>

                <a href="{{ route('logout') }}"
                    title="Logout"
                    class="text-white bg-success-950 rounded-full hover:bg-red-500 hover:text-white px-2.5 py-2.5 sm:px-3.5 sm:py-2.5 transition-all duration-200 shadow-md flex items-center justify-center">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs sm:text-base"></i>
                </a>
            </div>
        </div>
    </nav>
</header>