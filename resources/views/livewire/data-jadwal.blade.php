<div>
    <div class="flex justify-between items-center gap-4 mb-2">
        <div>
            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->nama == 'KEPEGAWAIAN')
                <!-- Input Pencarian -->
                <select wire:model.live="selectedUnit"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">>
                    <option value="">Pilih Unit</option>
                    @foreach ($units as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            @endif
            @if (collect(auth()->user()->roles()->pluck('name'))->filter(function ($name) {
                        return str_starts_with($name, 'Kepala');
                    })->count())
                <select wire:model.live="selectedUser"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                    <option value="">All</option>
                    @foreach ($users as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>

            @endif
        </div>
        <div class="flex justify-end items-center gap-3 mb-2">
            @if ($routeIsDashboard)
                <div class="flex justify-end">
                    <div>
                        <a href="{{ route('jadwal.index') }}"
                            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200"
                            data-tooltip-target="tooltip-pengaturan">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                        <div id="tooltip-pengaturan" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                            Pengaturan Jadwal Absensi
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- Filter Bulan & Tahun -->
    <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Filter Kiri -->
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <select wire:model.live="bulan"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">
                        {{ \Carbon\Carbon::createFromFormat('!m', $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="tahun"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                @foreach (range(now()->year - 5, now()->year) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tombol-Tombol Kanan -->
        @if (!$routeIsDashboard)
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                {{-- Tombol Download Template --}}
                @can('template-jadwal')
                    <a href="{{ route('jadwal.template', ['month' => $bulan, 'year' => $tahun]) }}"
                        class="w-full sm:w-auto flex items-center justify-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                        <i class="fas fa-download mr-2"></i> Download Template
                    </a>
                @endcan

                {{-- Tombol Import Excel --}}
                @can('import-jadwal')
                    <input type="file" wire:model="file" class="hidden" id="uploadFile">
                    <button type="button" onclick="document.getElementById('uploadFile').click();"
                        class="w-full sm:w-auto flex items-center justify-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                        <i class="fas fa-file-excel mr-2"></i> Import Excel
                    </button>
                    {{-- Info file dan progress (Import) --}}
                    @if ($file)
                        <div class="mt-2 flex flex-col gap-2 w-full sm:w-auto">
                            <div class="flex items-center space-x-2">
                                <span
                                    class="text-sm text-green-700 font-medium">{{ $file->getClientOriginalName() }}</span>
                                <button type="button" wire:click="$set('file', null)"
                                    class="text-red-500 hover:text-red-700 font-medium text-sm">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>

                            <div wire:loading wire:target="file">
                                <div class="w-full bg-gray-200 rounded-full">
                                    <div class="bg-green-500 text-xs leading-none py-1 text-center text-white"
                                        style="width: 0%;" x-data="{ progress: 0 }" x-init="$watch('progress', value => {
                                            setInterval(() => {
                                                if (progress < 100) progress += 10;
                                            }, 200);
                                        })">
                                        Loading...
                                    </div>
                                </div>
                            </div>

                            <button type="button" wire:click="import"
                                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                                Submit Import
                            </button>
                        </div>
                    @endif
                @endcan
                {{-- Tombol Tambah Jadwal --}}
                @can('tambah-jadwal')
                    <a href="{{ route('jadwal.create') }}"
                        class="w-full sm:w-auto flex items-center justify-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Jadwal
                    </a>
                @endcan
            </div>
        @endif
    </div>

    @error('file')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror

    <div class="relative overflow-auto max-h-full shadow-md rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Pendidikan</th>
                    <th class="px-4 py-3">Tanggal Masuk</th>
                    <th class="px-4 py-3">Lama Kerja</th>
                    @foreach ($tanggalJadwal as $tanggal)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($tanggal);
                            $hari = $carbonDate->format('l'); // Nama Hari
                            $isHoliday = $this->isHoliday($tanggal);
                        @endphp
                        <th
                            class="px-2 py-3 text-center
                            {{ $isHoliday ? 'bg-red-500 text-white' : '' }}">
                            {{ $carbonDate->format('d') }}
                        </th>
                    @endforeach
                    @can('edit-jadwal')
                        <th class="px-4 py-3">Aksi</th>
                    @endcan
                </tr>
            </thead>

            <tbody>
                @foreach ($jadwals as $user_id => $jadwalUser)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td class="px-4 py-3 font-medium text-success-900 whitespace-nowrap">
                            {{ optional(optional($jadwalUser)->first())->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional(optional($jadwalUser)->first())->user->pendidikanUser->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional(optional($jadwalUser)->first())->user->tmt ? \Carbon\Carbon::parse(optional(optional($jadwalUser)->first())->user->tmt)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $user = optional(optional($jadwalUser)->first())->user;
                                $masaKerja = $user?->masa_kerja;
                                $jenis = strtolower($user?->jenis?->nama ?? '');
                            @endphp

                            {{ $masaKerja !== null ? ($jenis === 'kontrak' ? floor($masaKerja) . ' bulan' : $masaKerja . ' tahun') : '-' }}
                        </td>

                        @foreach ($tanggalJadwal as $tanggal)
                            @php
                                $shiftData = $filteredShifts[$user_id][$tanggal] ?? null;
                                $isSpecialShift = in_array($shiftData['nama_shift'] ?? '', ['I', 'C']);
                            @endphp

                            <td
                                class="px-2 py-3 text-center
                        {{ $isHoliday || $isSpecialShift ? 'bg-red-200 text-red-600' : ($hari === 'Sunday' ? 'bg-red-200 text-red-600' : '') }}">

                                @if ($shiftData)
                                    <button
                                        wire:click="showShiftDetail('{{ $shiftData['nama_shift'] }}', '{{ $shiftData['jam_masuk'] }}', '{{ $shiftData['jam_keluar'] }}', `{{ $shiftData['keterangan'] ?? '-' }}`)"
                                        class="hover:text-blue-700 hover:underline transition duration-150">
                                        {{ $shiftData['nama_shift'] ?? '-' }}
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        @can('edit-jadwal')
                            <td class="px-4 py-3">
                                <a href="{{ route('jadwal.edit', optional(optional($jadwalUser)->first())->user->id) }}"
                                    class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                                    data-tooltip-target="tooltip-jadwalUser-{{ optional(optional($jadwalUser)->first())->user->id }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-jadwalUser-{{ optional(optional($jadwalUser)->first())->user->id }}"
                                    role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah Data Jadwal Absensi
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($showModalShift)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-3/4 max-h-[80vh] overflow-auto p-6">
                <h2 class="text-lg font-semibold mb-4">Keterangan Shift per Unit</h2>

                <table class="w-full text-sm border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Unit</th>
                            <th class="border p-2">Kode Shift</th>
                            <th class="border p-2">Jam Masuk</th>
                            <th class="border p-2">Jam Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataShifts as $shift)
                            <tr>
                                <td class="border p-2">{{ $shift->unitKerja->nama ?? '-' }}</td>
                                <td class="border p-2">{{ $shift->nama_shift }}</td>
                                <td class="border p-2">{{ $shift->jam_masuk }}</td>
                                <td class="border p-2">{{ $shift->jam_keluar }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <button wire:click="closeShiftModal"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Tutup</button>
                </div>
            </div>
        </div>
    @endif
    @if ($showModalDetailShift)
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md sm:max-w-lg p-6 relative overflow-hidden">

                <!-- Judul -->
                <h2 class="text-lg sm:text-xl font-semibold mb-4 text-center">Detail Shift</h2>

                <!-- Isi Modal -->
                <div class="text-sm sm:text-base text-gray-700 space-y-2">
                    <div><strong>Nama Shift:</strong> {{ $shiftNama }}</div>
                    <div><strong>Jam:</strong> {{ $shiftJamMasuk }} - {{ $shiftJamKeluar }}</div>
                    <div><strong>Keterangan:</strong> {{ $shiftKeterangan ?? '-' }}</div>
                </div>

                <!-- Tombol Tutup -->
                <div class="mt-6 text-center">
                    <button wire:click="$set('showModalDetailShift', false)"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
