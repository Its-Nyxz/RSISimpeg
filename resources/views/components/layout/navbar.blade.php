<header class="z-40 sm:z-50 fixed w-full">
    <nav class="bg-primary-100 border-gray-200 px-2 lg:px-6 py-5 shadow-2xl">
        <div class="flex flex-wrap justify-between items-center mx-3">
            <div class="flex">
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2 text-md hover:text-primary-100 ml-3 me-1 transition duration-100 text-primary-950 rounded-lg sm:hidden hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="/" class="flex items-center">
                    <img src="{{ asset('build/assets/logo.png') }}" class="mr-3 h-6 sm:h-9 hidden sm:flex"
                        alt="Logo" />
                    <span
                        class="self-center text-[1.2rem] sm:text-xl font-bold whitespace-nowrap text-primary-950">SIMPEG
                        <span class="font-medium">RSI
                            BANJARNEGARA</span></span>
                </a>
            </div>
            <div>
                <div class="hidden sm:flex items-center  lg:order-2">
                    <a href="#">
                        <span class="font-medium text-primary-950 me-2"
                            style="text-transform: capitalize;">{{ Auth::user()->name }}</span>
                        <span class="text-white bg-primary-950 rounded-full me-2 hover:bg-primary-700  px-3  py-2"><i
                                class="fa-solid fa-user"></i></span></a>
                    <a href="{{ route('logout') }}">
                        <span class="text-white bg-primary-950 rounded-full hover:bg-primary-700  px-3  py-2  "><i
                                class="fa-solid fa-arrow-right-from-bracket"></i></span>
                    </a>
                </div>
            </div>

        </div>
    </nav>
</header>
