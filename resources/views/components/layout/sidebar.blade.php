<aside id="default-sidebar"
    class="fixed top-0 left-0 z-50 sm:z-40 w-56 sm:w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidenav">
    <div class="overflow-y-hidden py-3 sm:py-24 px-3 h-full bg-success-950">
        <div class="flex sm:hidden justify-center w-100 mb-4">
            <img src="{{ asset('build/assets/logo.png') }}" class="mr-3 h-16 " alt="Logo" />
        </div>
        <div class="mb-5 px-2">
            <div class="font-light text-xl text-center sm:text-start text-white">Selamat Datang,</div>
            <div class="font-semibold text-xl text-center sm:text-start text-white" style="text-transform: capitalize;">
                {{ Auth::user()->name }}</div>
        </div>
        <ul class="space-y-2 border-t pt-4">
            <x-side-link title="Dashboard" href="#" icon="fa-solid fa-house" />
            <x-side-link title="Add User" href="#" icon="fa-solid fa-user-plus" />
            <x-side-link title="Master Data" href="#" icon="fa-regular fa-address-book" :child="[
                ['title' => 'Tunjangan', 'href' => '#'],
                ['title' => 'Golongan', 'href' => '#'],
                ['title' => 'Gaji Pokok', 'href' => '#'],
                ['title' => 'Pendidikan', 'href' => '#'],
                ['title' => 'Jam', 'href' => '#'],
            ]" />
            <x-side-link title="Kepegawaian" icon="fa-solid fa-people-group" :child="[
                ['title' => 'Data Karyawan', 'href' => '#'],
                ['title' => 'Tunjangan', 'href' => '#'],
                ['title' => 'Approval Cuti', 'href' => '#'],
                ['title' => 'Import Gaji', 'href' => '#'],
            ]" />
            <x-side-link title="Keuangan" href="#" icon="fa-solid fa-money-bills" />
            <x-side-link title="Pengaturan" href="#" icon="fa-solid fa-gear" />
        </ul>
    </div>

</aside>
