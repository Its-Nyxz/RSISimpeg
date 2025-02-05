<header class="z-40 sm:z-50 fixed w-full">
    <nav class="bg-success-100 border-gray-200 px-2 lg:px-6 py-5 shadow-2xl">
        <div class="flex flex-wrap justify-between items-center mx-3">
            <div class="flex">
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2 text-md hover:text-success-100 ml-3 me-1 transition duration-100 text-success-950 rounded-lg sm:hidden hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="/" class="flex items-center">
                    <img src="{{ asset('img/logo.png') }}" class="mr-3 h-6 sm:h-9 hidden sm:flex" alt="Logo" />
                    <span
                        class="self-center text-[1.2rem] sm:text-xl font-bold whitespace-nowrap text-success-950">SIMPEG
                        <span class="font-medium">RSI
                            BANJARNEGARA</span></span>
                </a>
            </div>
            <div>
                <div class="hidden sm:flex items-center lg:order-2">
                    <a href="#">
                        <span class="font-medium text-success-950 me-2"
                            style="text-transform: capitalize;">{{ auth()->user()->name }}</span>
                        <span class="text-white bg-success-950 rounded-full me-2 hover:bg-success-700 px-3 py-2">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </a>
                    <a href="{{ route('logout') }}">
                        <span class="text-white bg-success-950 rounded-full hover:bg-success-700 px-3 py-2">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
