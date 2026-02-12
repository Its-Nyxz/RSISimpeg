<div>
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-4">
        <div class="w-full lg:w-auto flex flex-col md:flex-row gap-2">
            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->id == 87)
                <!-- Input Pencarian -->
                <select wire:model.live="selectedUnit"
                    class="w-full md:w-auto rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm shadow-sm">
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
                    class="w-full md:w-auto rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm shadow-sm">
                    <option value="">All</option>
                    @foreach ($users as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>

            @endif
        </div>
        <div class="flex justify-end items-center gap-3 w-full lg:w-auto">
            @if ($routeIsDashboard)
                <div class="flex justify-end w-full lg:w-auto">
                    <div class="relative">
                        <a href="{{ route('jadwal.index') }}"
                            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 transition duration-200 shadow-sm flex items-center gap-2"
                            data-tooltip-target="tooltip-pengaturan">
                            <i class="fa-solid fa-gear"></i> <span class="lg:hidden">Pengaturan</span>
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
    <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
        <!-- Filter Kiri -->
        <div class="grid grid-cols-2 gap-2 w-full lg:w-auto">
            <select wire:model.live="bulan"
                class="w-full rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm shadow-sm">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">
                        {{ \Carbon\Carbon::createFromFormat('!m', $m)->locale('id')->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="tahun"
                class="w-full rounded-lg px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 text-sm shadow-sm">
                @foreach (range(now()->year - 3, now()->year + 2) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>

        </div>

        <!-- Tombol-Tombol Kanan -->
        @if (!$routeIsDashboard)
            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto mt-2 lg:mt-0">
                {{-- Tombol Download Template --}}
                @can('template-jadwal')
                    @php
                        $canSelectUnit =
                            auth()->user()->hasRole('Super Admin') || auth()->user()->unitKerja->id == 87;
                        $unitId = $canSelectUnit ? $selectedUnit : auth()->user()->unit_id;
                    @endphp


                    <a href="{{ route('jadwal.template', [
                        'month' => $bulan,
                        'year' => $tahun,
                        'unit_id' => $unitId,
                    ]) }}"
                        class="w-full sm:w-auto flex items-center justify-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 transition shadow-sm">
                        <i class="fas fa-download mr-2"></i> <span>Template</span>
                    </a>
                @endcan

                {{-- Tombol Import Excel --}}
                @can('import-jadwal')
                    <input type="file" wire:model="file" class="hidden" id="uploadFile">
                    <button type="button" onclick="document.getElementById('uploadFile').click();"
                        class="w-full sm:w-auto flex items-center justify-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 transition shadow-sm">
                        <i class="fas fa-file-excel mr-2"></i> Import
                    </button>
                    {{-- Info file dan progress (Import) --}}
                    @if ($file)
                        <div class="mt-2 flex flex-col gap-2 w-full sm:w-auto">
                            <div class="flex items-center space-x-2">
                                <span
                                    class="text-xs sm:text-sm text-success-700 font-medium truncate max-w-[150px]">{{ $file->getClientOriginalName() }}</span>
                                <button type="button" wire:click="$set('file', null)"
                                    class="text-red-500 hover:text-red-700 font-medium text-sm">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>

                            <div wire:loading wire:target="file">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-success-500 h-2 rounded-full"
                                        style="width: 0%;" x-data="{ progress: 0 }" x-init="$watch('progress', value => {
                                            setInterval(() => {
                                                if (progress < 100) progress += 10;
                                            }, 200);
                                        })">
                                    </div>
                                </div>
                            </div>

                            <button type="button" wire:click="import"
                                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 transition shadow-sm">
                                Submit
                            </button>
                        </div>
                    @endif
                @endcan
                {{-- Tombol Tambah Jadwal --}}
                @can('tambah-jadwal')
                    <a href="{{ route('jadwal.create') }}"
                        class="w-full sm:w-auto flex items-center justify-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 transition shadow-sm">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah
                    </a>
                @endcan
            </div>
        @endif
    </div>

    @error('file')
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
             <span class="font-medium">Error!</span> {{ $message }}
        </div>
    @enderror

    <div class="relative overflow-auto max-h-[600px] shadow-md rounded-lg border border-gray-200 bg-white">
        <table class="w-full text-xs sm:text-sm text-left text-gray-700 border-separate border-spacing-0">
            <thead class="text-xs sm:text-sm uppercase bg-success-400 text-success-900 sticky top-0 z-30 shadow-md">
                <tr>
                    <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap bg-success-400 border-b border-success-500">No</th>
                    <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap sticky left-0 z-40 bg-success-400 border-r border-b border-success-500 min-w-[120px] sm:min-w-[150px] shadow-sm">Nama</th>
                    <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap border-b border-success-500 hidden md:table-cell">Pendidikan</th>
                    <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap text-center border-b border-success-500">PJ</th> <!-- Pindahkan ke sini -->
                    <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap border-b border-success-500 hidden lg:table-cell">Tanggal Masuk</th>
                    <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap border-b border-success-500 hidden md:table-cell">Lama Kerja</th>
                    @foreach ($tanggalJadwal as $tanggal)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($tanggal);
                            $hari = $carbonDate->format('l'); // Nama Hari
                            $isHoliday = $this->isHoliday($tanggal);
                        @endphp
                        <th
                            class="px-1 py-2 sm:px-2 sm:py-3 text-center min-w-[30px] sm:min-w-[40px] border-l border-b border-success-500
                            {{ $isHoliday ? 'bg-red-500 text-white' : '' }}">
                            {{ $carbonDate->format('d') }}
                        </th>
                    @endforeach
                    @can('edit-jadwal')
                        <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap bg-success-400 sticky right-0 z-40 border-l border-b border-success-500 shadow-sm">Aksi</th>
                    @endcan
                </tr>
            </thead>

            <tbody>
                @foreach ($jadwals as $user_id => $jadwalUser)
                    <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200 hover:bg-success-50 transition duration-150">
                        <td class="px-2 py-2 sm:px-4 sm:py-3 font-medium text-gray-900 whitespace-nowrap text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 font-medium text-gray-900 whitespace-nowrap sticky left-0 z-20 bg-inherit border-r border-gray-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] min-w-[120px] sm:min-w-[150px]">
                            <div class="truncate max-w-[120px] sm:max-w-none" title="{{ optional(optional($jadwalUser)->first())->user->name ?? '-' }}">
                                {{ optional(optional($jadwalUser)->first())->user->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap hidden md:table-cell">
                            {{ optional(optional($jadwalUser)->first())->user->pendidikanUser->nama ?? '-' }}
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-center whitespace-nowrap">
                            @php
                                // Cek PJ untuk bulan berjalan (bisa disesuaikan jika ingin per tanggal)
                                $user = optional(optional($jadwalUser)->first())->user;
                                $isPJ = \App\Models\PJ::where('user_id', $user_id)
                                    ->whereMonth('assigned_at', $bulan)
                                    ->whereYear('assigned_at', $tahun)
                                    ->where('is_pj', true)
                                    ->exists();
                            @endphp
                            @if($isPJ)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">Iya</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap hidden lg:table-cell">
                            {{ optional(optional($jadwalUser)->first())->user->tmt ? \Carbon\Carbon::parse(optional(optional($jadwalUser)->first())->user->tmt)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap hidden md:table-cell">
                            @php
                                $user = optional(optional($jadwalUser)->first())->user;
                                $masaKerja = $user?->masa_kerja;
                                $jenis = strtolower($user?->jenis?->nama ?? '');
                            @endphp

                            {{ $masaKerja !== null ? ($jenis === 'kontrak' ? floor($masaKerja) . ' bulan' : $masaKerja . ' tahun') : '-' }}
                        </td>

                        @foreach ($tanggalJadwal as $tanggal)
                            @php
                                $carbonDate = \Carbon\Carbon::parse($tanggal);
                                $hari = $carbonDate->format('l');
                                $isHoliday = $this->isHoliday($tanggal); // Hitung ulang di sini
                                $shifts = $filteredShifts[$user_id][$tanggal] ?? [];
                                $isSpecialShift = collect($shifts)
                                    ->pluck('nama_shift')
                                    ->contains(function ($val) {
                                        return in_array($val, ['I', 'C']);
                                    });
                            @endphp

                            <td
                                class="px-1 py-2 sm:px-2 sm:py-3 text-center border-l border-gray-200 text-xs sm:text-sm
                                {{ $isHoliday || $isSpecialShift ? 'bg-red-50 text-red-600 font-semibold' : ($hari === 'Sunday' ? 'bg-red-50 text-red-600' : '') }}">

                                @if (count($shifts))
                                    @foreach ($shifts as $shift)
                                        <button
                                            wire:click="showShiftDetail('{{ $shift['nama_shift'] }}', '{{ $shift['jam_masuk'] }}', '{{ $shift['jam_keluar'] }}', `{{ $shift['keterangan'] }}`, '{{ $user_id }}', '{{ $tanggal }}')"
                                            class="hover:text-success-700 hover:underline transition duration-150 font-semibold focus:outline-none focus:ring-2 focus:ring-success-500 rounded px-1">
                                            {{ $shift['nama_shift'] }}
                                        </button>
                                        @if (!$loop->last)
                                            <span class="text-gray-300">|</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        @endforeach
                        @can('edit-jadwal')
                            <td class="px-2 py-2 sm:px-4 sm:py-3 sticky right-0 z-20 bg-inherit border-l border-gray-200">
                                <a href="{{ route('jadwal.edit', optional(optional($jadwalUser)->first())->user->id) }}"
                                    class="text-success-700 p-1.5 sm:px-3 sm:py-2 rounded-md border border-gray-300 bg-white hover:bg-success-50 hover:border-success-300 block text-center transition shadow-sm"
                                    data-tooltip-target="tooltip-jadwalUser-{{ optional(optional($jadwalUser)->first())->user->id }}">
                                    <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                                </a>
                                <div id="tooltip-jadwalUser-{{ optional(optional($jadwalUser)->first())->user->id }}"
                                    role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Ubah
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
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex justify-center items-center z-50 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all scale-100">
                <div class="p-4 sm:p-6 border-b flex justify-between items-center bg-gray-50 rounded-t-xl">
                     <h2 class="text-lg font-bold text-gray-800">Keterangan Shift per Unit</h2>
                     <button wire:click="closeShiftModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fa-solid fa-times text-xl"></i>
                     </button>
                </div>

                <div class="overflow-y-auto p-4 sm:p-6 flex-1">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs sm:text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100 text-gray-700 uppercase">
                                <tr>
                                    <th class="border-b p-3 text-left font-semibold">Unit</th>
                                    <th class="border-b p-3 text-center font-semibold">Kode</th>
                                    <th class="border-b p-3 text-center font-semibold">Masuk</th>
                                    <th class="border-b p-3 text-center font-semibold">Pulang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($dataShifts as $shift)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 sm:p-3 text-gray-800">{{ $shift->unitKerja->nama ?? '-' }}</td>
                                        <td class="p-2 sm:p-3 text-center font-bold text-success-700 bg-success-50 rounded">{{ $shift->nama_shift }}</td>
                                        <td class="p-2 sm:p-3 text-center text-gray-600">{{ $shift->jam_masuk }}</td>
                                        <td class="p-2 sm:p-3 text-center text-gray-600">{{ $shift->jam_keluar }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-4 sm:p-6 border-t text-right bg-gray-50 rounded-b-xl">
                    <button wire:click="closeShiftModal"
                        class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium transition duration-200 w-full sm:w-auto shadow-md">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if ($showModalDetailShift)
        <div class="fixed inset-0 z-50 bg-gray-900 bg-opacity-80 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm sm:max-w-md relative overflow-hidden transform transition-all scale-100 flex flex-col max-h-[90vh]">

                <!-- Header -->
                <div class="bg-success-600 p-4 sm:p-6 text-white text-center">
                    <h2 class="text-xl font-bold">Detail Shift</h2>
                    <p class="text-success-100 text-sm mt-1">Informasi detail jadwal shift</p>
                </div>

                <!-- Isi Modal -->
                <div class="p-6 overflow-y-auto">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-gray-500 text-sm">Nama Shift</span>
                            <span class="font-bold text-gray-900 text-lg bg-gray-100 px-3 py-1 rounded-full">{{ $shiftNama }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-gray-500 text-sm">Jam Operasional</span>
                            <span class="font-medium text-gray-800 font-mono">{{ $shiftJamMasuk }} - {{ $shiftJamKeluar }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm block mb-2">Keterangan</span>
                            <div class="bg-gray-50 p-3 rounded-lg text-gray-700 italic text-sm border border-gray-200">
                                @if($shiftKeterangan && $shiftKeterangan !== 'null')
                                    {{ $shiftKeterangan }}
                                @else
                                    <span class="text-gray-400">Tidak ada keterangan</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @can('edit-jadwal')
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Ubah / Hapus Shift</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="selectedShiftId" class="block text-xs font-medium text-gray-700 mb-1">Ganti dengan Shift:</label>
                                    <select wire:model="selectedShiftId" id="selectedShiftId"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-success-500 focus:border-success-500 outline-none transition shadow-sm bg-white">
                                        <option value="">-- Pilih Shift Baru --</option>
                                        @foreach ($dataShifts as $shift)
                                            <option value="{{ $shift->id }}">
                                                {{ $shift->nama_shift }} ({{ $shift->jam_masuk }} - {{ $shift->jam_keluar }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedShiftId')
                                        <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex gap-3">
                                    <button wire:click="updateShift"
                                        class="flex-1 bg-success-600 text-white px-4 py-2.5 rounded-lg shadow hover:bg-success-700 transition duration-150 text-sm font-bold flex justify-center items-center gap-2">
                                        <i class="fa-solid fa-save"></i> Simpan
                                    </button>
                                    <button type="button"
                                        class="bg-red-50 text-red-600 px-4 py-2.5 rounded-lg hover:bg-red-100 transition duration-150 border border-red-200 flex justify-center items-center gap-2"
                                        onclick="confirmAlert('Yakin ingin menghapus Jadwal Shift ini?', 'Ya, hapus!', () => @this.call('deleteShiftFromModal'))"
                                        title="Hapus Jadwal">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                <!-- Tombol Tutup -->
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    <button wire:click="$set('showModalDetailShift', false)"
                        class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg transition duration-200 font-medium shadow-sm">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
