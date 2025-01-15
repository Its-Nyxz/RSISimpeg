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
                {{ auth()->user()->name }}
            </div>
        </div>
        <ul class="space-y-2 border-t pt-4">
            <livewire:side-link href="/dashboard" title="Home" icon="fa-solid fa-house" />
            <livewire:side-link href="/users" title="Add User" icon="fa-solid fa-user-plus" />
            <livewire:side-link title="Master Data" icon="fa-regular fa-address-book" :child="[
                // ['title' => 'Jabatan', 'href' => '/jabatan'],
                ['title' => 'Tunjangan', 'href' => '/tunjangan'],
                ['title' => 'Golongan', 'href' => '/golongan'],
                ['title' => 'Gaji Pokok', 'href' => '/gapok'],
                ['title' => 'Pendidikan', 'href' => '/pendidikan'],
                ['title' => 'Absensi', 'href' => '/absensi'],
                ['title' => 'Kenaikan Berkala dan Golongan', 'href' => '/kenaikan'],
                ['title' => 'Tunjangan Kinerja', 'href' => '/tukin'],
            ]" />
            <livewire:side-link title="Kepegawaian" icon="fa-solid fa-people-group" :child="[
                ['title' => 'Data Karyawan', 'href' => '#'],
                ['title' => 'Tunjangan', 'href' => '#'],
                ['title' => 'Approval Cuti', 'href' => '#'],
                ['title' => 'Import Gaji', 'href' => '#'],

                ['title' => 'Poin Peran Fungsional', 'href' => '/peranfungsional'],
                ['title' => 'Poin Penilaian Pekerja', 'href' => '/penilaian'],
            ]" />
            <livewire:side-link href="#" title="Keuangan" icon="fa-solid fa-money-bills" />
            <livewire:side-link href="#" title="Pengaturan" icon="fa-solid fa-gear" :child="[
                ['title' => 'Jabatan & Perizinan', 'href' => '/jabatanperizinan'],
            ]" />
        </ul>
    </div>
</aside>