<aside id="default-sidebar"
    class="fixed top-0 left-0 z-50 sm:z-40 w-56 sm:w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidenav">
    <!-- Kontainer Scrollable -->
    <div class="h-full flex flex-col overflow-hidden">
        <!-- Header Logo dan Salam -->
        <div class="py-3 sm:py-24 px-3 h-full bg-success-950 overflow-y-auto">
            <!-- Logo -->
            <div class="flex sm:hidden justify-center w-100 mb-4">
                <img src="{{ asset('img/logo.png') }}" class="mr-3 h-16 " alt="Logo" />
            </div>

            <!-- Welcome Message -->
            <div class="mb-5 px-2">
                <div class="font-light text-xl text-center sm:text-start text-white">Selamat Datang,</div>
                <div class="font-semibold text-xl text-center sm:text-start text-white"
                    style="text-transform: capitalize;">
                    {{ auth()->user()->name }}
                </div>
            </div>

            <!-- Konten Scrollable -->
            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-4">
                <!-- Navigation -->
                <ul class="space-y-2 border-t pt-4">
                    <livewire:side-link href="/dashboard" title="Home" icon="fa-solid fa-house" />
                    <livewire:side-link href="/users" title="Add User" icon="fa-solid fa-user-plus" />
                    <livewire:side-link title="Aktivitas Kerja" icon="fa-solid fa-user" :child="[['title' => 'Timer', 'href' => '/timer'], ['title' => 'Absensi', 'href' => 'aktivitasabsensi']]" />
                    <livewire:side-link title="Master Data" icon="fa-regular fa-address-book" :child="[
                        [
                            'title' => 'Tunjangan',
                            'child' => [
                                ['title' => 'Tunjangan Jabatan', 'href' => '/jabatan'],
                                ['title' => 'Tunjangan Fungsional', 'href' => '/fungsional'],
                                ['title' => 'Tunjangan Umum', 'href' => '/umum'],
                                ['title' => 'Tunjangan Khusus', 'href' => '/khusus'],
                                ['title' => 'Tunjangan Tidak Tetap', 'href' => '/trans'],
                            ],
                        ],
                        ['title' => 'Golongan', 'href' => '/golongan'],
                        ['title' => 'Gaji Pokok', 'href' => '/gapok'],
                        ['title' => 'Pendidikan', 'href' => '/pendidikan'],
                        [
                            'title' => 'Absensi',
                            'href' => '/#',
                            'child' => [
                                ['title' => 'Jadwal Absensi', 'href' => '/jadwal'],
                                ['title' => 'Shift', 'href' => '/shift'],
                                ['title' => 'Opsi', 'href' => '/opsi'],
                                ['title' => 'Status', 'href' => '/status'],
                            ],
                        ],
                        ['title' => 'Unit Kerja', 'href' => '/unitkerja'],
                        ['title' => 'Potongan', 'href' => '/potongan'],
                        [
                            'title' => 'Tunjangan Kinerja',
                            'href' => '/#',
                            'child' => [
                                ['title' => 'Masa Kerja', 'href' => '/masakerja'],
                                ['title' => 'Level Unit', 'href' => '/levelunit'],
                                ['title' => 'Proposionaltias Poin', 'href' => '/proposionalitas'],
                                ['title' => 'Poin Peran Fungsionalitas', 'href' => '/poinperan'],
                                ['title' => 'Tunjangan Kinerja Jabatan', 'href' => '/tukinjabatan'],
                            ],
                        ],
                        ['title' => 'Kategori Jabatan', 'href' => '/katjab'],
                    ]" />
                    <livewire:side-link title="Kepegawaian" icon="fa-solid fa-people-group" :child="[
                        ['title' => 'Data Karyawan', 'href' => '/datakaryawan'],
                        ['title' => 'Kenaikan', 'href' => '/kenaikan'],
                        ['title' => 'Approval Cuti', 'href' => '#'],
                        ['title' => 'Import Gaji', 'href' => '#'],
                        ['title' => 'Poin Peran Fungsional', 'href' => '/peranfungsional'],
                        ['title' => 'Poin Penilaian Pekerja', 'href' => '/penilaian'],
                    ]" />
                    <livewire:side-link href="/keuangan" title="Keuangan" icon="fa-solid fa-money-bills" />
                    <livewire:side-link href="#" title="Pengaturan" icon="fa-solid fa-gear" :child="[
                        ['title' => 'Hak Akses & Perizinan', 'href' => '/jabatanperizinan'],
                        ['title' => 'User', 'href' => '/userprofile'],
                    ]" />
                </ul>
            </div>
        </div>
    </div>
</aside>
