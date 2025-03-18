<div>
    <!-- Header -->
    <div class="flex justify-end mb-6">
        @can('tambah-history')
        <a href="#"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            + Tambah History
        </a>
        @endcan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="w-full">
            <x-card title="{{ $user->no_ktp ?? '-' }}" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div class="mb-4">
                        <strong>Nama</strong>: {{ $user->name ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Jabatan</strong>: {{ $user->kategorijabatan->nama ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tempat, Tanggal Lahir</strong>: {{ $user->tempat ?? '-' }},
                        {{ $user->tanggal_lahir ? formatDate($user->tanggal_lahir) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Tetap</strong>:
                        {{ $user->tanggal_tetap ? formatDate($user->tanggal_tetap) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Awal</strong>: {{ $deskripsiPendidikan ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Penyesuaian</strong>: {{ $user->pendidikan_penyesuaian ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Penyesuaian</strong>:
                        {{ $user->tgl_penyesuaian ? formatDate($user->tgl_penyesuaian) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Informasi Pensiun</strong>:
                        {{ $user->pensiun ? formatDate($user->pensiun) : '-' }}
                    </div>
                </div>
                <!-- Tombol Edit Karyawan -->
                @can('edit-data-karyawan')
                <div class="mt-4">
                    <a href="{{ route('editKaryawan.edit', ['id' => $user->id]) }}"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        Edit Karyawan
                    </a>
                </div>
                @endcan
            </x-card>
        </div>

        <div class="w-full">
            <x-card title="No recent" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div class="mb-4">
                        <strong>Nama</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Jabatan</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Tempat Tanggal Lahir</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Tetap</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Awal</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Penyesuaian</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Penyesuaian</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Naik Berkala</strong>: -
                    </div>
                    <div class="mb-4">
                        <strong>Naik Golongan</strong>: -
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
