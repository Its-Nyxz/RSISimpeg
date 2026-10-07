<div>
    <!-- Header -->
    <div class="flex justify-end flex-wrap gap-2 mb-6 items-center">
        @can('tambah-sp')
            <div class="relative group">
                <!-- Mobile -->
                <button x-on:click="$dispatch('open-modal', 'modal-SP')"
                    class="sm:hidden w-11 h-11 flex items-center justify-center rounded-lg bg-red-100 text-red-900 hover:bg-red-600 hover:text-white transition"
                    aria-label="Tambah SP">
                    <i class="fa-solid fa-file-circle-plus text-lg"></i>
                </button>
                <div
                    class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                    Tambah SP
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop -->
                <button x-on:click="$dispatch('open-modal', 'modal-SP')"
                    class="hidden sm:flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-red-100 text-red-900 hover:bg-red-600 hover:text-white transition">
                    + Tambah SP
                </button>
            </div>
        @endcan

        @if (auth()->user()->can('tambah-history') && $user->jenis?->nama === 'Tetap')
            <div class="flex gap-2">
                <!-- Mobile -->
                <a href="{{ route('pengajuan.createbyid', ['tipe' => 'cuti', 'userid' => $user->id]) }}"
                    class="sm:hidden w-11 h-11 flex items-center justify-center rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition"
                    aria-label="Tambah Cuti">
                    <i class="fa-solid fa-calendar-plus text-lg"></i>
                </a>
                <div
                    class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                    Tambah Cuti
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop -->
                <a href="{{ route('pengajuan.createbyid', ['tipe' => 'cuti', 'userid' => $user->id]) }}"
                    class="hidden sm:flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-blue-100 text-blue-900 hover:bg-blue-600 hover:text-white transition">
                    + Tambah Cuti
                </a>

            <div class="relative group">
                <!-- Mobile -->
                <button x-on:click="$dispatch('open-modal', 'modal-History')"
                    class="sm:hidden w-11 h-11 flex items-center justify-center rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition"
                    aria-label="Tambah History">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                </button>
                <div
                    class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                    Tambah History
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Desktop -->
                <button x-on:click="$dispatch('open-modal', 'modal-History')"
                    class="hidden sm:flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition">
                    + Tambah History
                </button>
            </div>
            </div>
        @endif

        {{-- Tombol Isi Form KPI (Warna Kuning & Teks Selalu Tampil) --}}
        <div class="relative group">
            <!-- Mobile -->
            <a href="{{ route('kpi.create', ['userId' => $user->id]) }}"
                class="sm:hidden w-11 h-11 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 transition shadow-sm"
                aria-label="Isi Form KPI">
                <i class="fa-solid fa-file-signature text-lg"></i>
            </a>
            <div
                class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-20">
                Isi Form KPI
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <!-- Desktop -->
            <a href="{{ route('kpi.create', ['userId' => $user->id]) }}"
                class="hidden sm:flex items-center gap-2 px-5 py-2.5 text-sm font-medium rounded-lg bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 transition shadow-sm">
                <i class="fa-solid fa-file-signature text-base"></i>
                <span class="font-medium">Isi Form KPI</span>
            </a>
        </div>

        <div class="relative group">
            <!-- Mobile -->
            <a href="{{ route('datakaryawan.index') }}"
                class="sm:hidden w-11 h-11 flex items-center justify-center rounded-lg bg-success-700 text-white hover:bg-success-800 transition"
                aria-label="Kembali">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <div
                class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded shadow opacity-0 group-hover:opacity-100 transition">
                Kembali
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <!-- Desktop -->
            <a href="{{ route('datakaryawan.index') }}"
                class="hidden sm:flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-success-700 text-white hover:bg-success-800 transition">
                Kembali
            </a>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="w-full">
            <x-card title="No KTP : {{ $user->no_ktp ?? '-' }}" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <table class="table-auto w-full text-md text-left">
                        <tbody>
                            <tr>
                                <td class="font-semibold w-1/3">Nama</td>
                                <td>: {{ $user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold w-1/3">Email</td>
                                <td>: {{ $user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold w-1/3">No Hp</td>
                                <td>: {{ $user->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Jabatan Struktural</td>
                                <td>: {{ $user->kategorijabatan->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Jabatan Fungsional</td>
                                <td>
                                    : {{ $user->kategorifungsional?->nama ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Jabatan Umum</td>
                                <td>
                                    : {{ $user->kategoriumum?->nama ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Tempat, Tanggal Lahir</td>
                                <td>: {{ $user->tempat ?? '-' }},
                                    {{ $user->tanggal_lahir ? formatDate($user->tanggal_lahir) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">No Rek</td>
                                <td>: {{ $user->no_rek ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Pendidikan</td>
                                <td>: {{ $user->pendidikanUser->deskripsi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Institusi</td>
                                <td>: {{ $user->institusi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Jenis Kelamin</td>
                                <td>: {{ $user->jk === null ? '-' : ($user->jk == 1 ? 'Laki-Laki' : 'Perempuan') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Alamat</td>
                                <td>: {{ $user->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Jenis Karyawan</td>
                                <td>: {{ $user->jenis->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">TMT</td>
                                <td>:
                                    {{ $user->tmt ? formatDate($user->tmt) : ($user->tmt_masuk ? formatDate($user->tmt_masuk) : '-') }}
                                </td>
                            </tr>
                            @php
                                $canSeeSisaCuti =
                                    auth()->check() &&
                                    auth()
                                        ->user()
                                        ->getRoleNames()
                                        ->contains(function ($role) {
                                            $role = strtolower($role);

                                            return str_contains($role, 'kepegawaian') ||
                                                str_contains($role, 'super admin');
                                        });
                            @endphp

                            <tr>
                                <td class="font-semibold">Hak Akses</td>
                                <td>:
                                    {{ $user->getRoleNames()->isNotEmpty() ? $user->getRoleNames()->join(', ') : '-' }}
                                </td>
                            </tr>

                            @if ($canSeeSisaCuti)
                                <tr>
                                    <td class="font-semibold">Sisa Cuti Tahunan</td>
                                    <td>:
                                        {{ $user->sisaCutiTahunan()?->where('tahun', now('Asia/Jakarta')->year)->first()->sisa_cuti ?? '0' }}
                                        kali
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Tombol Aksi -->
                @can('edit-data-karyawan')
                    <div class="mt-4 flex gap-2">
                        <!-- Edit -->
                        <a href="{{ route('editKaryawan.edit', ['id' => $user->id]) }}"
                            class="px-5 py-2.5 text-md font-medium text-white bg-success-600 rounded-lg hover:bg-success-700 transition duration-200">
                            Edit Karyawan
                        </a>

                        @can('resign-kerja')
                            <!-- Resign / Kembali Kerja -->
                            <button x-on:click="$dispatch('open-modal', 'modal-resign')"
                                class="px-5 py-2.5 text-md font-medium rounded-lg transition duration-200
                                {{ $statusKaryawan == 1
                                    ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-600 hover:text-white'
                                    : 'bg-red-100 text-red-800 hover:bg-red-600 hover:text-white' }}">
                                {{ $statusKaryawan == 1 ? 'Resign Kerja' : 'Kembali Kerja' }}
                            </button>
                        @endcan
                    </div>
                @endcan
            </x-card>
        </div>

        <div class="w-full">
            <x-card title="No Recent" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <table class="table-auto w-full text-md text-left">
                        <tbody>
                            <tr>
                                <td class="font-semibold w-1/3">Nama</td>
                                <td>: {{ $viewPendAwal?->user?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Jabatan</td>
                                <td>: {{ $viewPendAwal?->user?->kategorijabatan?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Tempat, Tanggal Lahir</td>
                                <td>: {{ $viewPendAwal?->user?->tempat ?? '-' }},
                                    {{ $viewPendAwal?->user?->tanggal_lahir ? formatDate($viewPendAwal->user->tanggal_lahir) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Tanggal Tetap</td>
                                <td>: {{ $viewPendAwal?->user?->tmt ? formatDate($viewPendAwal->user->tmt) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Pendidikan Sebelumnya</td>
                                <td>: {{ $viewPendAwal?->penyesuaian?->pendidikanAwal?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Pendidikan Penyesuaian</td>
                                <td>: {{ $viewPendAwal?->penyesuaian?->pendidikanPenyesuaian?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Tanggal Penyesuaian</td>
                                <td>:
                                    {{ $viewPendAwal?->tanggal_penyesuaian ? formatDate($viewPendAwal->tanggal_penyesuaian) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Golongan Awal</td>
                                <td>: {{ $viewPendAwal?->golonganAwal?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Naik Golongan</td>
                                <td>: {{ $viewPendAwal?->golonganAkhir?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Gapok Sebelumnya</td>
                                <td>: Rp {{ number_format($gapokSebelumnya?->nominal_gapok ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Gapok Penyesuaian</td>
                                <td>: Rp {{ number_format($gapokPenyesuaian?->nominal_gapok ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <x-modal name="modal-resign" maxWidth="lg" :show="false">
            <form class="mx-5 py-5" wire:submit.prevent="{{ $statusKaryawan == 1 ? 'resignKerja' : 'kembaliKerja' }}">
                <h2 class="text-lg font-semibold mb-4">Resign Kerja</h2>
                <div class="mb-4">
                    <label for="alasan_resign" class="block text-sm font-medium text-gray-700 mb-2">Alasan
                        Resign</label>
                    <textarea id="alasanResign" wire:model.live="alasanResign"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5 "
                        placeholder="Masukkan Alasan Resign" rows="3"></textarea>
                    @error('alasanResign')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    @if ($statusKaryawan == 1)
                        <button type="button" x-on:click="$dispatch('close-modal', 'modal-resign')"
                            class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                        <button type="submit" wire:click="resignKerja()"
                            class="px-4 py-2 bg-success-600 text-white rounded-lg">Kirim</button>
                    @else
                        <button type="submit" wire:click="kembaliKerja()"
                            class="px-4 py-2 bg-success-600 text-white rounded-lg">Kembali Bekerja</button>
                    @endif
                </div>
            </form>
        </x-modal>

        <x-modal name="modal-History" :show="false">
            <form class="mx-5 py-5" wire:submit.prevent="tambahHistory">
                <h2 class="text-lg font-semibold mb-4">Tambah History</h2>
                <div class="mb-4 flex space-x-4">
                    <div class="w-1/2">
                        <label for="tmt" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tetap /
                            Tanggal Mulai Kerja</label>
                        <input type="text" id="tmt" wire:model.live="tmt"
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                            placeholder="Belum Di Isi Silahkan isi Di Edit Karyawan" readonly>
                        @error('tmt')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <label for="pend_awal" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan
                            Awal</label>
                        <input type="text" id="pend_awal" wire:model.live="pend_awal"
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                            placeholder="Belum Di Isi Silahkan isi Di Edit Karyawan" readonly>
                        @error('pend_awal')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="pend_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan
                        Penyesuaian</label>
                    <select id="pend_penyesuaian" wire:model.live="pend_penyesuaian" wire:input='pend_penyesuaian'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5">
                        <option value="">Pilih Pendidikan</option>
                        @foreach ($pendidikans as $pendidikan)
                            <option value="{{ $pendidikan->id }}">
                                {{ $pendidikan->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('pend_penyesuaian')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="tanggal_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Penyesuaian</label>
                    <input type="date" id="tanggal_penyesuaian" wire:model.live="tanggal_penyesuaian"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5 "
                        placeholder="Tanggal Penyesuaian">
                    @error('tanggal_penyesuaian')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-History')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-success-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </x-modal>

        <x-modal name="modal-SP" :show="false">
            <form class="mx-5 py-5" wire:submit.prevent="tambahSP">
                <h2 class="text-lg font-semibold mb-4">Tambah Surat Peringatan</h2>

                <div class="mb-4">
                    <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2">Tingkat SP</label>
                    <select id="tingkat" wire:model="tingkat"
                        class="w-full p-2 border rounded-lg bg-gray-50 text-gray-900">
                        <option value="">-- Pilih Tingkat SP --</option>
                        <option value="I">I (Ringan)</option>
                        <option value="II">II (Sedang) / Surat Peringatan 1</option>
                        <option value="III">III (Berat) / Surat Peringatan 2</option>
                        <option value="IV">IV (Sangat Berat)</option>
                    </select>
                    @error('tingkat')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenis_pelanggaran" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                        Pelanggaran</label>
                    <input type="text" wire:model="jenis_pelanggaran" id="jenis_pelanggaran"
                        class="w-full p-2 border rounded-lg bg-gray-50 text-gray-900"
                        placeholder="Contoh: Terlambat datang">
                    @error('jenis_pelanggaran')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_sp" class="block text-sm font-medium text-gray-700 mb-2">Tanggal SP</label>
                    <input type="date" wire:model="tanggal_sp" id="tanggal_sp"
                        class="w-full p-2 border rounded-lg bg-gray-50 text-gray-900">
                    @error('tanggal_sp')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="file_sp" class="block text-sm font-medium text-gray-700 mb-2">Upload File SP
                        (PDF/JPG)</label>
                    <input type="file" wire:model="file_sp" id="file_sp"
                        class="w-full p-2 border rounded-lg bg-gray-50 text-gray-900">
                    @error('file_sp')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan
                        Tambahan</label>
                    <textarea wire:model="keterangan" id="keterangan" class="w-full p-2 border rounded-lg bg-gray-50 text-gray-900"
                        rows="3" placeholder="Opsional..."></textarea>
                    @error('keterangan')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-SP')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-success-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </x-modal>

        {{-- Modal Preview Dokumen Bawaan --}}
        <x-modal name="modal-preview-dokumen" maxWidth="2xl" :show="false">
            <div class="flex flex-col max-h-[90vh] font-sans">
                {{-- Header Modal --}}
                <div class="px-5 py-3.5 bg-success-400 text-success-950 flex justify-between items-center rounded-t-lg flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-lg text-success-950"></i>
                        <h3 class="font-extrabold text-sm sm:text-base uppercase tracking-tight text-success-950">Pratinjau Dokumen</h3>
                    </div>
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-preview-dokumen')"
                        class="text-success-950 hover:text-red-700 text-xl font-bold p-1 leading-none transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Box Info Nama Dokumen (Jelas & Kontras) --}}
                <div class="bg-success-50 p-4 border-b border-success-200 flex-shrink-0">
                    <span class="block text-[10px] font-bold text-success-800 uppercase tracking-wider">Nama File / Dokumen</span>
                    <h4 class="text-base font-extrabold text-gray-900 leading-tight mt-0.5 break-all">{{ $previewName ?? 'Dokumen' }}</h4>
                </div>

                {{-- Konten Dokumen --}}
                <div class="p-4 overflow-y-auto flex-1 flex items-center justify-center bg-gray-100 min-h-[350px]">
                    @if ($previewUrl)
                        @if (in_array($previewExtension, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                            <img src="{{ $previewUrl }}" alt="{{ $previewName }}" class="max-h-[65vh] max-w-full rounded-lg shadow-md object-contain border border-gray-200" />
                        @elseif ($previewExtension === 'pdf')
                            <iframe src="{{ $previewUrl }}" class="w-full h-[65vh] rounded-lg border border-gray-300 bg-white"></iframe>
                        @else
                            <div class="text-center p-6 space-y-3">
                                <i class="fa-solid fa-file-arrow-down text-4xl text-gray-400"></i>
                                <p class="text-sm text-gray-700 font-medium">Dokumen (.{{ $previewExtension }}) tidak dapat ditampilkan langsung.</p>
                                <a href="{{ $previewUrl }}" download="{{ $previewName }}" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-success-600 text-white rounded-lg text-xs font-bold hover:bg-success-700 transition">
                                    <i class="fa-solid fa-download"></i> Unduh File
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-10 text-gray-500 font-medium text-sm">
                            Memuat pratinjau dokumen...
                        </div>
                    @endif
                </div>

                {{-- Footer Modal --}}
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-lg flex-shrink-0">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-preview-dokumen')"
                        class="px-5 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                        Tutup
                    </button>
                </div>
            </div>
        </x-modal>
        @push('scripts')
            <script type="module">
                Livewire.on('konfirmasi-phk', () => {
                    const dari = @this.get('phkDari');

                    let keterangan = '';
                    if (dari === 'SP1') {
                        keterangan = '<b>4x Tingkat II (SP1)</b>';
                    } else if (dari === 'SP2') {
                        keterangan = '<b>1x Tingkat III (SP2)</b>';
                    } else {
                        keterangan = 'SP melebihi batas toleransi';
                    }

                    Swal.fire({
                        title: 'PHK Otomatis?',
                        html: `Karyawan telah menerima ${keterangan}.<br>Menambah SP ini akan <b>berakibat PHK</b>.<br><br><b>Lanjutkan?</b>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan PHK',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('tambahSP', true);
                        }
                    });
                });
            </script>
        @endpush

    </div>
    <div class="w-full mb-6">
        <x-card-tanpa-title>
            <div class="flex items-center space-x-3 mb-4">
                <i class="fa-solid fa-list text-3xl text-gray-700"></i>
                <h1 class="text-2xl font-bold text-success-900">History</h1>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kolom 1: Riwayat Cuti --}}
                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Cuti</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Jenis</th>
                                        <th class="px-2 py-2 sm:px-4">Mulai</th>
                                        <th class="px-2 py-2 sm:px-4">Selesai</th>
                                        <th class="px-2 py-2 sm:px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listCuti as $cuti)
                                        <tr
                                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                            <td class="px-2 py-2 sm:px-4">{{ $cuti->jeniscuti->nama_cuti ?? '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($cuti->tanggal_mulai) ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($cuti->tanggal_selesai) ?? '-' }}</td>
                                            <td
                                                class="px-2 py-2 sm:px-4 font-bold 
                                                {{ $cuti->status_cuti_id == 1 ? 'text-success-900' : ($cuti->status_cuti_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                                                {{ $cuti->statusCuti->nama_status ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-2">Belum ada cuti.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom 2: Riwayat Izin --}}
                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Izin</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Jenis</th>
                                        <th class="px-2 py-2 sm:px-4">Mulai</th>
                                        <th class="px-2 py-2 sm:px-4">Selesai</th>
                                        <th class="px-2 py-2 sm:px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listIzin as $izin)
                                        <tr
                                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                            ., kll; <td class="px-2 py-2 sm:px-4">
                                                {{ $izin->jenisizin->nama_izin ?? '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($izin->tanggal_mulai) ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($izin->tanggal_selesai) ?? '-' }}</td>
                                            <td
                                                class="px-2 py-2 sm:px-4 font-bold 
                                                {{ $izin->status_izin_id == 1 ? 'text-success-900' : ($izin->status_izin_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                                                {{ $izin->statusizin->nama_status ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-2">Belum ada izin.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom 3: Riwayat Penyesuaian --}}
                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Penyesuaian</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Tanggal</th>
                                        <th class="px-2 py-2 sm:px-4">Pendidikan Awal</th>
                                        <th class="px-2 py-2 sm:px-4">Penyesuaian</th>
                                        <th class="px-2 py-2 sm:px-4">Gol Awal</th>
                                        <th class="px-2 py-2 sm:px-4">Gol Akhir</th>
                                        <th class="px-2 py-2 sm:px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listPenyesuaian as $penyesuaian)
                                        <tr
                                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($penyesuaian->tanggal_penyesuaian) ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $penyesuaian->penyesuaian->pendidikanAwal->nama ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $penyesuaian->penyesuaian->pendidikanPenyesuaian->nama ?? '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $penyesuaian->golonganAwal->nama ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $penyesuaian->golonganAkhir->nama ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                <button type="button"
                                                    onclick="confirmAlert('Yakin ingin membatalkan penyesuaian ini?', 'Ya, Batalkan!', () => @this.call('batalPenyesuaian',{{ $penyesuaian->id }}))"
                                                    class="text-red-600 hover:text-red-800 transition">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-2">Belum ada penyesuaian
                                                pendidikan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom 4: Bisa dikembangkan --}}
                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Kenaikan Gapok</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Kenaikan</th>
                                        <th class="px-2 py-2 sm:px-4">Jenis Kenaikan</th>
                                        <th class="px-2 py-2 sm:px-4">Gaji Pokok</th>
                                        <th class="px-2 py-2 sm:px-4">Gol Baru</th>
                                        <th class="px-2 py-2 sm:px-4">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listGapok as $kenaikan)
                                        <tr
                                            class="@if (!$kenaikan->status && $kenaikan->catatan) bg-red-200 @else odd:bg-success-50 even:bg-success-100 hover:bg-success-300 @endif border-b border-success-300">
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($kenaikan->tanggal_kenaikan) ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $kenaikan->jenis_kenaikan ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                Rp {{ number_format($kenaikan->gapok, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                {{ $kenaikan->golonganBaru->nama ?? '-' }}
                                                @if (!$kenaikan->status && $kenaikan->jenis_kenaikan === 'golongan')
                                                    <span class="text-red-600 text-xs font-semibold">(Ditolak)</span>
                                                @endif
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $kenaikan->catatan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-2">Belum ada kenaikan
                                                pendidikan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Surat Peringatan</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Peringatan</th>
                                        <th class="px-2 py-2 sm:px-4">Tingkat</th>
                                        <th class="px-2 py-2 sm:px-4">Jenis Pelanggaran</th>
                                        <th class="px-2 py-2 sm:px-4">Catatan</th>
                                        <th class="px-2 py-2 sm:px-4">Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listSP as $sp)
                                        <tr
                                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                            <td class="px-2 py-2 sm:px-4">{{ formatDate($sp->tanggal_sp) }}</td>
                                            <td class="px-2 py-2 sm:px-4">SP {{ $sp->sanksi }} Tingkat
                                                {{ $sp->tingkat }}</td>
                                            <td class="px-2 py-2 sm:px-4">{{ $sp->jenis_pelanggaran }}</td>
                                            <td class="px-2 py-2 sm:px-4">{{ $sp->keterangan ?? '-' }}</td>
                                            <td class="px-2 py-2 sm:px-4">
                                                @if ($sp->file_sp)
                                                    <a href="{{ Storage::url($sp->file_sp) }}" target="_blank"
                                                        class="inline-flex items-center text-sm text-blue-600 hover:underline">
                                                        <i class="fas fa-file-pdf mr-1"></i> Unduh
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-2">Belum ada surat peringatan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Pergantian Jabatan</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Mulai</th>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Selesai</th>
                                        <th class="px-2 py-2 sm:px-4">Jabatan</th>
                                        <th class="px-2 py-2 sm:px-4">Tunjangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listRiwayat as $riwayat)
                                        <tr
                                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                            <td class="px-2 py-2 sm:px-4">{{ formatDate($riwayat->tanggal_mulai) }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ formatDate($riwayat->tanggal_selesai) ?? '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">{{ $riwayat->kategori->nama }}</td>
                                            <td class="px-2 py-2 sm:px-4">{{ $riwayat->tunjangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-2">Belum ada Riwayat ganti
                                                jabatan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Dalam detail-karyawan.blade.php --}}
                @if ($canSeeRiwayat)
                    <div class="w-full">
                        <div class="text-lg font-semibold mb-2">Riwayat Approval</div>
                        <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                            <div class="max-h-96 overflow-y-auto">
                                <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                    <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-2 py-2 sm:px-4">Nama Karyawan</th>
                                            <th class="px-2 py-2 sm:px-4">Jenis Cuti</th>
                                            <th class="px-2 py-2 sm:px-4">Tanggal Cuti</th>
                                            <th class="px-2 py-2 sm:px-4">Status</th>
                                            <th class="px-2 py-2 sm:px-4">Tanggal Approval</th>
                                            <th class="px-2 py-2 sm:px-4">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($listRiwayatApproval as $riwayat)
                                            <tr
                                                class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                                <td class="px-2 py-2 sm:px-4">{{ $riwayat->cuti->user->name }}</td>
                                                <td class="px-2 py-2 sm:px-4">
                                                    {{ $riwayat->cuti->jeniscuti->nama_cuti }}</td>
                                                <td class="px-2 py-2 sm:px-4">
                                                    {{ formatDate($riwayat->cuti->tanggal_mulai) }} -
                                                    {{ formatDate($riwayat->cuti->tanggal_selesai) }}
                                                </td>
                                                <td class="px-2 py-2 sm:px-4">
                                                    @if ($riwayat->status_approval == 'disetujui_final')
                                                        <span class="text-success-600 font-bold">Disetujui Final</span>
                                                    @elseif($riwayat->status_approval == 'disetujui_intermediate')
                                                        <span class="text-blue-600 font-bold">Disetujui Kepala
                                                            Unit</span>
                                                    @elseif($riwayat->status_approval == 'ditolak')
                                                        <span class="text-red-600 font-bold">Ditolak</span>
                                                    @else
                                                        <span class="text-gray-600 font-bold">Menunggu</span>
                                                    @endif
                                                </td>
                                                <td class="px-2 py-2 sm:px-4">{{ formatDate($riwayat->approve_at) }}
                                                </td>
                                                <td class="px-2 py-2 sm:px-4">{{ $riwayat->catatan ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-2">Belum ada riwayat
                                                    approval.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Kolom Riwayat Dokumen --}}
                <div class="w-full">
                    <div class="text-lg font-semibold mb-2">Riwayat Dokumen</div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Nama Karyawan</th>
                                        <th class="px-2 py-2 sm:px-4">Jenis Dokumen</th>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Mulai</th>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Selesai</th>
                                        <th class="px-2 py-2 sm:px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listRiwayatDokumen as $dokumen)
                                        <tr
                                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                            <td class="px-2 py-2 sm:px-4 font-medium text-gray-900 text-left sm:text-center">
                                                <div>{{ $dokumen->user->name ?? $user->name }}</div>
                                                @if(isset($dokumen->user->unitKerja->nama) || isset($dokumen->user->jabatan->nama) || isset($dokumen->user->kategorijabatan->nama))
                                                    <div class="text-[11px] text-gray-500 font-normal">
                                                        {{ $dokumen->user->unitKerja->nama ?? '' }}
                                                        @if(isset($dokumen->user->unitKerja->nama) && (isset($dokumen->user->jabatan->nama) || isset($dokumen->user->kategorijabatan->nama))) • @endif
                                                        {{ $dokumen->user->jabatan->nama ?? $dokumen->user->kategorijabatan->nama ?? '' }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                <span class="font-semibold">{{ $dokumen->jenisFile->name ?? '-' }}</span>
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $dokumen->mulai ? formatDate($dokumen->mulai) : '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $dokumen->selesai ? formatDate($dokumen->selesai) : '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    {{-- Tombol Preview (Pop-up Modal) --}}
                                                    <button type="button"
                                                        wire:click="setPreviewDokumen({{ $dokumen->id }})"
                                                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs transition inline-flex items-center gap-1 cursor-pointer"
                                                        title="Lihat / Preview Dokumen">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>

                                                    {{-- Tombol Download --}}
                                                    <button type="button"
                                                        wire:click="downloadDokumen({{ $dokumen->id }})"
                                                        class="px-2 py-1 bg-success-600 hover:bg-success-700 text-white rounded text-xs transition inline-flex items-center gap-1"
                                                        title="Download Dokumen">
                                                        <i class="fa-solid fa-download"></i>
                                                    </button>

                                                    {{-- Tombol Delete --}}
                                                    <button type="button"
                                                        onclick="confirmAlert('Apakah Anda yakin ingin menghapus dokumen {{ $dokumen->name }}?', 'Ya, hapus!', () => @this.call('deleteDokumen', {{ $dokumen->id }}))"
                                                        class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs transition inline-flex items-center gap-1"
                                                        title="Hapus Dokumen">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-2">Belum ada dokumen yang diupload.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom Riwayat Penilaian KPI --}}
                <div class="w-full">
                    <div class="flex justify-between items-center mb-2">
                        <div class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            Riwayat Penilaian KPI
                        </div>
                        <a href="{{ route('kpi.create', ['userId' => $user->id]) }}"
                            class="px-3 py-1.5 bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 rounded-lg text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Isi Form KPI</span>
                        </a>
                    </div>
                    <div class="relative overflow-x-auto max-w-full shadow-md sm:rounded-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs sm:text-sm text-center text-gray-700">
                                <thead class="uppercase bg-success-400 text-success-900 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4">Periode</th>
                                        <th class="px-2 py-2 sm:px-4">Tanggal Penilaian</th>
                                        <th class="px-2 py-2 sm:px-4">Pejabat Penilai</th>
                                        <th class="px-2 py-2 sm:px-4">Total Skor</th>
                                        <th class="px-2 py-2 sm:px-4">Tukin Diterima</th>
                                        <th class="px-2 py-2 sm:px-4">Status</th>
                                        <th class="px-2 py-2 sm:px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listRiwayatKpi as $kpi)
                                        <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300 transition">
                                            <td class="px-2 py-2 sm:px-4 font-bold text-gray-900">
                                                {{ $kpi->periode_bulan && $kpi->periode_tahun ? \Carbon\Carbon::createFromDate($kpi->periode_tahun, $kpi->periode_bulan, 1)->translatedFormat('F Y') : '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                {{ $kpi->tanggal_penilaian ? formatDate($kpi->tanggal_penilaian) : '-' }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4 text-left sm:text-center">
                                                <div class="font-semibold text-gray-900">{{ $kpi->penilai_nama ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-500">{{ $kpi->penilai_jabatan ?? '' }}</div>
                                            </td>
                                            <td class="px-2 py-2 sm:px-4 font-extrabold text-success-900 text-sm">
                                                {{ $kpi->total_skor }}
                                            </td>
                                            <td class="px-2 py-2 sm:px-4 text-left sm:text-center">
                                                <div class="font-bold text-gray-900">Rp {{ number_format($kpi->tunjangan_diterima, 0, ',', '.') }}</div>
                                                <div class="text-[11px] text-success-800 font-semibold">({{ $kpi->persentase_tunjangan }}%)</div>
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                @if ($kpi->status === 'selesai')
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-success-200 text-success-900 border border-success-400">
                                                        Selesai
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                        Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-2 py-2 sm:px-4">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    {{-- Tombol Lihat / Edit --}}
                                                    <a href="{{ route('kpi.edit', ['userId' => $user->id, 'kpiId' => $kpi->id]) }}"
                                                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs transition inline-flex items-center gap-1"
                                                        title="Lihat / Edit Form KPI">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>

                                                    {{-- Tombol Hapus --}}
                                                    <button type="button"
                                                        onclick="confirmAlert('Apakah Anda yakin ingin menghapus data KPI periode {{ $kpi->periode_bulan }}-{{ $kpi->periode_tahun }}?', 'Ya, hapus!', () => @this.call('deleteKpi', {{ $kpi->id }}))"
                                                        class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs transition inline-flex items-center gap-1"
                                                        title="Hapus Data KPI">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-gray-500 font-medium">
                                                Belum ada riwayat formulir KPI untuk karyawan ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </x-card-tanpa-title>
    </div>

</div>
