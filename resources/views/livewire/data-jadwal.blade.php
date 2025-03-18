<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Jadwal Absensi</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Jadwal..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <!-- Tombol Tambah Jadwal -->
            <a href="{{ route('jadwal.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Jadwal
            </a>


        </div>
    </div>

    <!-- Filter Bulan & Tahun -->
    <div class="flex gap-4 mb-4">
        <select wire:model.live="bulan"
            class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
            @foreach (range(1, 12) as $m)
                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
            @endforeach
        </select>

        <select wire:model.live="tahun"
            class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
            @foreach (range(now()->year - 5, now()->year) as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>

        <!-- Tombol Download Template -->
        <a href="{{ route('jadwal.template', ['month' => now()->month, 'year' => now()->year]) }}"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            <i class="fas fa-download"></i> Download Template
        </a>
        <!-- Input untuk Import -->
        <input type="file" wire:model="file" class="hidden" id="uploadFile">
        <button type="button" onclick="document.getElementById('uploadFile').click();"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            <i class="fas fa-file-excel"></i> Import Excel
        </button>

        <!-- Menampilkan Nama File -->
        @if ($file)
            <div class="mt-2 flex items-center space-x-2">
                <span class="text-sm text-green-700 font-medium">{{ $file->getClientOriginalName() }}</span>

                <!-- Tombol Hapus File -->
                <button type="button" wire:click="$set('file', null)"
                    class="text-red-500 hover:text-red-700 font-medium text-sm">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        @endif

        <!-- Menampilkan Progress Upload -->
        <div wire:loading wire:target="file" class="mt-2">
            <div class="w-full bg-gray-200 rounded-full">
                <div class="bg-green-500 text-xs leading-none py-1 text-center text-white" style="width: 0%;"
                    x-data="{ progress: 0 }" x-init="$watch('progress', value => {
                        setInterval(() => {
                            if (progress < 100) progress += 10;
                        }, 200);
                    })">
                    Loading...
                </div>
            </div>
        </div>

        <!-- Tombol Submit Import -->
        @if ($file)
            <button type="button" wire:click="import"
                class="mt-2 text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Submit Import
            </button>
        @endif

    </div>
    @error('file')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Pendidikan</th>
                    <th class="px-4 py-3">Tanggal Masuk</th>
                    <th class="px-4 py-3">Lama Kerja</th>
                    @foreach ($tanggalJadwal as $tanggal)
                        @php
                            $hari = \Carbon\Carbon::parse($tanggal)->format('l'); // Nama Hari
                        @endphp
                        <th
                            class="px-2 py-3 text-center
                            {{ $hari === 'Sunday' ? 'bg-red-500 text-white' : '' }}">
                            {{ \Carbon\Carbon::parse($tanggal)->format('d') }}
                        </th>
                    @endforeach
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $user_id => $jadwalUser)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td class="px-4 py-3 font-medium text-success-900 whitespace-nowrap">
                            {{ optional(optional($jadwalUser)->first())->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ optional(optional($jadwalUser)->first())->user->pendidikan ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional(optional($jadwalUser)->first())->user->tanggal_tetap ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ optional(optional($jadwalUser)->first())->user->masa_kerja ?? '-' }}
                            tahun</td>

                        @foreach ($tanggalJadwal as $tanggal)
                            @php
                                $hari = \Carbon\Carbon::parse($tanggal)->format('l');
                            @endphp
                            <td
                                class="px-2 py-3 text-center 
                            {{ $hari === 'Sunday' ? 'bg-red-200 text-red-600' : '' }}">
                                {{ $filteredShifts[$user_id][$tanggal] ?? '-' }}
                            </td>
                        @endforeach
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
