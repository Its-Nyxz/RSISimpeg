<aside id="default-sidebar"
    class="fixed top-0 left-0 z-50 sm:z-40 w-56 sm:w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidenav">
    <!-- Kontainer Scrollable -->
    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-50 sm:z-40 w-56 sm:w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidenav">
        <!-- Kontainer Scrollable -->
        <div class="h-full flex flex-col overflow-hidden">
            <!-- Header Logo dan Salam -->
            <div
                class="py-3 sm:py-24 px-3 h-full bg-gradient-to-b from-yellow-400 via-green-500 to-green-800 overflow-y-auto">
                {{-- <div class="py-3 sm:py-24 px-3 h-full bg-success-950 overflow-y-auto"> --}}
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
                        {{-- @can('add-user')
                    <livewire:side-link href="/users" title="Add User" icon="fa-solid fa-user-plus" />
                        @endcan --}}
                        <livewire:side-link title="Aktivitas Kerja" icon="fa-solid fa-user" :child="array_filter([
                            // auth()->user()->can('timer') ? ['title' => 'Timer', 'href' => '/timer'] : null,
                            auth()->user()->can('list-history')
                                ? [
                                    'title' => 'History',
                                    'href' => '/#',
                                    'child' => array_filter([
                                        ['title' => 'Slip Gaji', 'href' => '/slipgaji'],
                                        ['title' => 'Absensi', 'href' => '/aktivitasabsensi'],
                                        ['title' => 'Tukar Jadwal', 'href' => route('pengajuan.index', 'tukar_jadwal')],
                                        ['title' => 'Cuti', 'href' => route('pengajuan.index', 'cuti')],
                                        ['title' => 'Izin', 'href' => route('pengajuan.index', 'ijin')],
                                        ['title' => 'Peringatan', 'href' => '/peringatan'],
                                        auth()->user()->can('approval-cuti')
                                            ? ['title' => 'Approval', 'href' => '/riwayatapproval']
                                            : null,
                                        // auth()->user()->can('override-lokasi')
                                        //     ? ['title' => 'Kendala Lokasi', 'href' => '/overridelokasi']
                                        //     : null,
                                    ]),
                                ]
                                : null,
                            auth()->user()->can('absen')
                                ? [
                                    'title' => 'Absensi',
                                    'href' => '/#',
                                    'child' => [
                                        ['title' => 'Jadwal Absensi', 'href' => '/jadwal'],
                                        ['title' => 'Shift', 'href' => '/shift'],
                                        // ['title' => 'Opsi', 'href' => '/opsi'],
                                        // ['title' => 'Status', 'href' => '/status'],
                                        ['title' => 'Hari Libur Nasional', 'href' => '/liburnasional'],
                                    ],
                                ]
                                : null,
                        ])" />
                        @can('master-data')
                            <livewire:side-link title="Master Data" icon="fa-regular fa-address-book" :child="array_filter([
                                auth()->user()->can('tunjangan')
                                    ? [
                                        'title' => 'Tunjangan',
                                        'href' => '/#',
                                        'child' => [
                                            ['title' => 'Tunjangan Jabatan', 'href' => '/jabatan'],
                                            ['title' => 'Tunjangan Fungsional', 'href' => '/fungsional'],
                                            ['title' => 'Tunjangan Umum', 'href' => '/umum'],
                                            ['title' => 'Tunjangan Khusus', 'href' => '/khusus'],
                                            ['title' => 'Tunjangan Tidak Tetap', 'href' => '/trans'],
                                        ],
                                    ]
                                    : null,
                                auth()->user()->can('golongan') ? ['title' => 'Golongan', 'href' => '/golongan'] : null,
                                auth()->user()->can('gaji-pokok')
                                    ? [
                                        'title' => 'Gaji Pokok',
                                        'href' => '/#',
                                        'child' => [
                                            ['title' => 'Tetap', 'href' => '/gapok'],
                                            ['title' => 'Kontrak', 'href' => '/gapokkontrak'],
                                        ],
                                    ]
                                    : null,
                                auth()->user()->can('pendidikan')
                                    ? ['title' => 'Pendidikan', 'href' => '/pendidikan']
                                    : null,
                                auth()->user()->can('unit-kerja')
                                    ? ['title' => 'Unit Kerja', 'href' => '/unitkerja']
                                    : null,
                                auth()->user()->can('potongan') ? ['title' => 'Potongan', 'href' => '/potongan'] : null,
                                auth()->user()->can('tunjangan-kinerja')
                                    ? [
                                        'title' => 'Tunjangan Kinerja',
                                        'href' => '/#',
                                        'child' => [
                                            ['title' => 'Masa Kerja', 'href' => '/masakerja'],
                                            ['title' => 'Level Unit', 'href' => '/levelunit'],
                                            ['title' => 'Proposionaltias Poin', 'href' => '/proposionalitas'],
                                            ['title' => 'Poin Peran Fungsionalitas', 'href' => '/poinperan'],
                                            ['title' => 'Tunjangan Kinerja Jabatan', 'href' => '/tukinjabatan'],
                                        ],
                                    ]
                                    : null,
                                auth()->user()->can('kategori-jabatan')
                                    ? ['title' => 'Kategori Jabatan', 'href' => '/katjab']
                                    : null,
                                auth()->user()->can('jatah-cuti')
                                    ? ['title' => 'Jatah Cuti Tahunan', 'href' => '/jatahcuti']
                                    : null,
                                auth()->user()->can('penyesuaian')
                                    ? ['title' => 'Penyesuaian', 'href' => '/penyesuaian']
                                    : null,
                                auth()->user()->can('kategori-pph')
                                    ? ['title' => 'Kategori PPh', 'href' => '/pph']
                                    : null,
                            ])" />
                        @endcan
                        @can('view-kepegawaian')
                            <livewire:side-link title="Kepegawaian" icon="fa-solid fa-people-group" :child="array_filter([
                                ['title' => 'Data Karyawan', 'href' => '/datakaryawan'],
                                // auth()->user()->can('view-kenaikan')
                                //     ? ['title' => 'Kenaikan', 'href' => '/kenaikan']
                                //     : null,
                                auth()->user()->can('view-kenaikan')
                                    ? [
                                        'title' => 'Kenaikan',
                                        'href' => '/#',
                                        'child' => [
                                            ['title' => 'Karyawan Tetap', 'href' => '/kenaikan'],
                                            ['title' => 'Karyawan Kontrak', 'href' => '/kenaikankontrak'],
                                        ],
                                    ]
                                    : null,
                                auth()->user()->can('approval-cuti')
                                    ? ['title' => 'Approval Cuti', 'href' => '/approvalcuti']
                                    : null,
                                auth()->user()->can('approval-izin')
                                    ? ['title' => 'Approval Izin', 'href' => '/approvalizin']
                                    : null,
                                auth()->user()->can('approval-tukar-jadwal')
                                    ? ['title' => 'Approval Tukar Jadwal', 'href' => '/approvaltukar']
                                    : null,
                                // auth()->user()->can('view-import-gaji') ? ['title' => 'Import Gaji', 'href' => '#'] : null,
                                auth()->user()->can('view-poin-peran')
                                    ? ['title' => 'Poin Peran Fungsional', 'href' => '/peranfungsional']
                                    : null,
                                auth()->user()->can('view-poin-penilaian')
                                    ? ['title' => 'Poin Penilaian Pekerja', 'href' => '/penilaian']
                                    : null,
                            ])" />
                        @endcan
                        @can('view-keuangan')
                            <livewire:side-link href="/keuangan" title="Keuangan" icon="fa-solid fa-money-bills" />
                        @endcan
                        <livewire:side-link title="Pengaturan" icon="fa-solid fa-gear" :child="array_filter([
                            auth()->user()->can('hak-akses')
                                ? ['title' => 'Hak Akses & Perizinan', 'href' => '/jabatanperizinan']
                                : null,
                            ['title' => 'User', 'href' => '/userprofile'],
                        ])" />
                    </ul>
                </div>
            </div>
        </div>
    </aside>

    <!-- Header Logo dan Salam -->
    <div
        class="py-3 sm:py-24 px-3 h-full bg-gradient-to-b from-yellow-400 via-success-500 to-success-800 overflow-y-auto">
        <!-- Logo -->
        <div class="flex sm:hidden justify-center w-100 mb-4">
            <img src="{{ asset('img/logo.png') }}" class="mr-3 h-16 " alt="Logo" />
        </div>

        <!-- Welcome Message -->
        <div class="mb-5 px-2">
            <div class="font-light text-xl text-center sm:text-start text-white">Selamat Datang,</div>
            <div class="font-semibold text-xl text-center sm:text-start text-white" style="text-transform: capitalize;">
                {{ auth()->user()->name }}
            </div>
        </div>

        <!-- Konten Scrollable -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-4">
            <!-- Navigation -->
            <ul class="space-y-2 border-t pt-4">
                <livewire:side-link href="/dashboard" title="Home" icon="fa-solid fa-house" />
                {{-- @can('add-user')
                    <livewire:side-link href="/users" title="Add User" icon="fa-solid fa-user-plus" />
                        @endcan --}}
                <livewire:side-link title="Aktivitas Kerja" icon="fa-solid fa-user" :child="array_filter([
                    // auth()->user()->can('timer') ? ['title' => 'Timer', 'href' => '/timer'] : null,
                    auth()->user()->can('list-history')
                        ? [
                            'title' => 'History',
                            'href' => '/#',
                            'child' => array_filter([
                                ['title' => 'Slip Gaji', 'href' => '/slipgaji'],
                                ['title' => 'Absensi', 'href' => '/aktivitasabsensi'],
                                ['title' => 'Tukar Jadwal', 'href' => route('pengajuan.index', 'tukar_jadwal')],
                                ['title' => 'Cuti', 'href' => route('pengajuan.index', 'cuti')],
                                ['title' => 'Izin', 'href' => route('pengajuan.index', 'ijin')],
                                ['title' => 'Peringatan', 'href' => '/peringatan'],
                                auth()->user()->can('approval-cuti')
                                            ? ['title' => 'Approval', 'href' => '/riwayatapproval']
                                            : null,
                                // auth()->user()->can('override-lokasi')
                                //     ? ['title' => 'Kendala Lokasi', 'href' => '/overridelokasi']
                                //     : null,
                            ]),
                        ]
                        : null,
                    auth()->user()->can('absen')
                        ? [
                            'title' => 'Absensi',
                            'href' => '/#',
                            'child' => [
                                ['title' => 'Jadwal Absensi', 'href' => '/jadwal'],
                                ['title' => 'Shift', 'href' => '/shift'],
                                // ['title' => 'Opsi', 'href' => '/opsi'],
                                // ['title' => 'Status', 'href' => '/status'],
                                ['title' => 'Hari Libur Nasional', 'href' => '/liburnasional'],
                            ],
                        ]
                        : null,
                ])" />
                @can('master-data')
                    <livewire:side-link title="Master Data" icon="fa-regular fa-address-book" :child="array_filter([
                        auth()->user()->can('tunjangan')
                            ? [
                                'title' => 'Tunjangan',
                                'href' => '/#',
                                'child' => [
                                    ['title' => 'Tunjangan Jabatan', 'href' => '/jabatan'],
                                    ['title' => 'Tunjangan Fungsional', 'href' => '/fungsional'],
                                    ['title' => 'Tunjangan Umum', 'href' => '/umum'],
                                    ['title' => 'Tunjangan Khusus', 'href' => '/khusus'],
                                    ['title' => 'Tunjangan Tidak Tetap', 'href' => '/trans'],
                                ],
                            ]
                            : null,
                        auth()->user()->can('golongan') ? ['title' => 'Golongan', 'href' => '/golongan'] : null,
                        auth()->user()->can('gaji-pokok')
                            ? [
                                'title' => 'Gaji Pokok',
                                'href' => '/#',
                                'child' => [
                                    ['title' => 'Tetap', 'href' => '/gapok'],
                                    ['title' => 'Kontrak', 'href' => '/gapokkontrak'],
                                ],
                            ]
                            : null,
                        auth()->user()->can('pendidikan') ? ['title' => 'Pendidikan', 'href' => '/pendidikan'] : null,
                        auth()->user()->can('unit-kerja') ? ['title' => 'Unit Kerja', 'href' => '/unitkerja'] : null,
                        auth()->user()->can('potongan') ? ['title' => 'Potongan', 'href' => '/potongan'] : null,
                        auth()->user()->can('tunjangan-kinerja')
                            ? [
                                'title' => 'Tunjangan Kinerja',
                                'href' => '/#',
                                'child' => [
                                    ['title' => 'Masa Kerja', 'href' => '/masakerja'],
                                    ['title' => 'Level Unit', 'href' => '/levelunit'],
                                    ['title' => 'Proposionaltias Poin', 'href' => '/proposionalitas'],
                                    ['title' => 'Poin Peran Fungsionalitas', 'href' => '/poinperan'],
                                    ['title' => 'Tunjangan Kinerja Jabatan', 'href' => '/tukinjabatan'],
                                ],
                            ]
                            : null,
                        auth()->user()->can('kategori-jabatan')
                            ? ['title' => 'Kategori Jabatan', 'href' => '/katjab']
                            : null,
                        auth()->user()->can('jatah-cuti')
                            ? ['title' => 'Jatah Cuti Tahunan', 'href' => '/jatahcuti']
                            : null,
                        auth()->user()->can('penyesuaian')
                            ? ['title' => 'Penyesuaian', 'href' => '/penyesuaian']
                            : null,
                        auth()->user()->can('kategori-pph') ? ['title' => 'Kategori PPh', 'href' => '/pph'] : null,
                    ])" />
                @endcan
                @can('view-kepegawaian')
                    <livewire:side-link title="Kepegawaian" icon="fa-solid fa-people-group" :child="array_filter([
                        ['title' => 'Data Karyawan', 'href' => '/datakaryawan'],
                        auth()->user()->can('view-kenaikan') ? ['title' => 'Kenaikan', 'href' => '/kenaikan'] : null,
                        auth()->user()->can('approval-cuti')
                            ? ['title' => 'Approval Cuti', 'href' => '/approvalcuti']
                            : null,
                        auth()->user()->can('approval-cuti')
                            ? ['title' => 'Approval Izin', 'href' => '/approvalizin']
                            : null,
                        auth()->user()->can('approval-tukar-jadwal')
                            ? ['title' => 'Approval Tukar Jadwal', 'href' => '/approvaltukar']
                            : null,
                        // auth()->user()->can('view-import-gaji') ? ['title' => 'Import Gaji', 'href' => '#'] : null,
                        auth()->user()->can('view-poin-peran')
                            ? ['title' => 'Poin Peran Fungsional', 'href' => '/peranfungsional']
                            : null,
                        auth()->user()->can('view-poin-penilaian')
                            ? ['title' => 'Poin Penilaian Pekerja', 'href' => '/penilaian']
                            : null,
                    ])" />
                @endcan
                @can('view-keuangan')
                    <livewire:side-link href="/keuangan" title="Keuangan" icon="fa-solid fa-money-bills" />
                @endcan
                <livewire:side-link title="Pengaturan" icon="fa-solid fa-gear" :child="array_filter([
                    auth()->user()->can('hak-akses')
                        ? ['title' => 'Hak Akses & Perizinan', 'href' => '/jabatanperizinan']
                        : null,
                    ['title' => 'User', 'href' => '/userprofile'],
                ])" />
            </ul>
        </div>
    </div>
    </div>
</aside>
