    <div>
        <!-- Header -->
        <div class="flex justify-end space-x-2 mb-6">
            <button wire:click="exportPdf"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                <i class="fas fa-download"></i> Export
            </button>
            <a href="{{ route('aktivitasabsensi.index') }}"
                class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="w-full">
                <x-card title="Data Umum" class="mb-6 text-success-900">
                    <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                        <div class="mb-4"><strong>NIP</strong> : {{ $absen->user->nip ?? '-' }}</div>
                        <div class="mb-4"><strong>Nama</strong> : {{ $absen->user->name ?? '-' }}</div>
                        <div class="mb-4"><strong>Jabatan</strong> : {{ $absen->user->kategorijabatan->nama ?? '-' }}
                        </div>
                        <div class="mb-4">
                            <strong>Tempat, Tanggal Lahir</strong> : {{ $absen->user->tempat ?? '-' }},
                            {{ $absen->user->tanggal_lahir ? formatDate($absen->user->tanggal_lahir) : '-' }}
                        </div>
                        <div class="mb-4"><strong>Unit</strong> : {{ $absen->user->unitKerja->nama ?? '-' }}</div>
                        <div class="mb-4"><strong>Pendidikan</strong> :
                            {{ $absen->user->pendidikanUser->deskripsi ?? '-' }}
                        </div>
                        <div class="mb-4">
                            <strong>Jenis Kelamin</strong> :
                            {{ $absen->user->jk === null ? '-' : ($absen->user->jk == 1 ? 'Laki-Laki' : 'Perempuan') }}
                        </div>
                        <div class="mb-4"><strong>Alamat</strong> : {{ $absen->user->alamat ?? '-' }}</div>

                    </div>

                </x-card>
            </div>
            <div class="w-full">
                <x-card title="Data Absensi" class="mb-6 text-success-900">
                    <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                        <div class="mb-4"><strong>Hari, Tanggal</strong> : {{ $tanggalFormatted }}</div>
                        <div class="mb-4"><strong>Jam Kerja</strong> : {{ $jamKerjaFormatted }}</div>
                        <div class="mb-4"><strong>Real Time Jam Masuk</strong> : {{ $realMasukFormatted }}</div>
                        <div class="mb-4"><strong>Real Time Jam Keluar</strong> : {{ $realKeluarFormatted }}</div>
                        <div class="mb-4"><strong>Rencana Kerja</strong> :
                            {{ $absen->deskripsi_in ?? '-' }}</div>
                        <div class="mb-4"><strong>Laporan Kerja</strong> :
                            {{ $absen->deskripsi_out ?? '-' }}</div>
                        <div class="mb-4"><strong>Status Lembur</strong> : {{ $isLembur ? 'Ya' : 'Tidak' }}</div>
                        @if ($isLembur)
                            <div class="mb-4"><strong>Durasi Lembur</strong> : {{ $lemburFormatted }}</div>
                        @endif
                        <div class="mb-4"><strong>Feedback</strong> :
                            {{ $absen->feedback ?? '-' }}</div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
