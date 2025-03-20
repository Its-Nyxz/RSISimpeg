<header class="z-40 sm:z-50 fixed w-full">
    <nav class="bg-success-100 border-gray-200 px-2 lg:px-6 py-5 shadow-2xl relative">
        <div class="flex justify-between items-center mx-3">
            <div class="flex">
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2 text-md hover:text-success-100 ml-3 me-1 transition duration-100 text-success-950 rounded-lg sm:hidden hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="/" class="flex items-center">
                    <img src="{{ asset('img/logo.png') }}" class="mr-3 h-6 sm:h-9 hidden sm:flex" alt="Logo" />
                    <span
                        class="self-center text-[1.2rem] sm:text-xl font-bold whitespace-nowrap text-success-950">SIMPEG
                        <span class="font-medium">RSI BANJARNEGARA</span>
                    </span>
                </a>
            </div>

            <!-- ✅ Foto Profil dan Logout di Pojok Kanan Atas -->
            <div class="absolute top-0 right-0 flex items-center gap-x-2 p-3 z-50">
                <a href="{{ route('userprofile.index') }}" class="flex items-center">
                    {!! auth()->user()->photo
                        ? '<img src="' .
                            asset('storage/photos/' . auth()->user()->photo) .
                            '" 
                                                alt="Profile" 
                                                class="w-8 h-8 rounded-full object-cover border border-gray-300">'
                        : '<div class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full border border-gray-300">
                                                <i class="fa-solid fa-user"></i>
                                            </div>' !!}
                </a>

                <a href="{{ route('logout') }}"
                    class="text-white bg-success-950 rounded-full hover:bg-success-700 px-3 py-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </nav>
</header>
