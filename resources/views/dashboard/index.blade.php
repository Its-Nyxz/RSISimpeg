<x-body>
    {{-- Header Section: Mengubah layout dari row ke column pada layar kecil --}}
    <div class="flex flex-col lg:flex-row items-start justify-between mb-6 gap-4">
        <div class="w-full lg:w-2/3">
            <h1 class="text-xl md:text-2xl font-bold text-success-900 mb-4">
                DASHBOARD {{ auth()->user()->unitKerja->nama ?? ' ' }}
            </h1>

            {{-- Notifikasi Masa Berlaku SIP/STR --}}
            @if (count($masaBerlakuSipStr) > 0 || count($masaBerlakuPelatihan) > 0)
                <div class="flex items-start bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded-lg text-sm shadow-sm max-h-48 overflow-y-auto">
                    <div class="w-full">
                        <p class="font-bold mb-2 flex items-center">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i> PERHATIAN: Masa Berlaku SIP/STR/Pelatihan
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            @foreach ($masaBerlakuSipStr as $file)
                                @php
                                    $selesai = \Carbon\Carbon::parse($file->selesai);
                                    $sisaHari = intval(now()->diffInDays($selesai));
                                @endphp
                                @if ($sisaHari <= 7)
                                    <li class="border-b border-yellow-200 pb-1 last:border-0">
                                        @if (auth()->user()->unitKerja?->id === 87 || auth()->user()->hasRole('Super Admin'))
                                            <strong class="block md:inline">{{ $file->user->name ?? '-' }}</strong>
                                        @endif
                                        <span>{{ $file->jenisFile->name ?? '-' }} berakhir <strong>{{ $selesai->format('d M Y') }}</strong></span>
                                        <span class="ml-1 text-red-600 font-bold block md:inline">(Sisa {{ $sisaHari }} Hari!)</span>
                                    </li>
                                @endif
                            @endforeach

                            @foreach ($masaBerlakuPelatihan as $file)
                                @php
                                    $selesai = \Carbon\Carbon::parse($file->selesai);
                                    $sisaHari = intval(now()->diffInDays($selesai));
                                @endphp
                                @if ($sisaHari <= 7)
                                    <li class="border-b border-yellow-200 pb-1 last:border-0">
                                        @if (auth()->user()->unitKerja?->id === 87 || auth()->user()->hasRole('Super Admin'))
                                            <strong class="block md:inline">{{ $file->user->name ?? '-' }}</strong>
                                        @endif
                                        <span>{{ $file->jenisFile->name ?? '-' }} berakhir <strong>{{ $selesai->format('d M Y') }}</strong></span>
                                        <span class="ml-1 text-red-600 font-bold block md:inline">(Sisa {{ $sisaHari }} Hari!)</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Card untuk Total Jam Sertifikat Pelatihan --}}
        <div class="w-full lg:w-1/3">
            @if (count($masaBerlakuPelatihan) > 0 || ((auth()->user()->unitKerja?->id === 87 || auth()->user()->hasRole('Super Admin')) && $masaBerlakuPelatihan->sum('jumlah_jam') > 0))
                <div class="flex items-start bg-blue-100 border-l-4 border-blue-500 text-blue-800 px-4 py-3 rounded-lg text-sm shadow-sm max-h-48 overflow-y-auto">
                    <div class="w-full">
                        <p class="font-bold mb-2 flex items-center">
                            <i class="fa-solid fa-clock mr-2"></i> Total Jam Pelatihan
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            @if (auth()->user()->unitKerja?->id === 87 || auth()->user()->hasRole('Super Admin'))
                                @php $users = $masaBerlakuPelatihan->pluck('user_id')->unique(); @endphp
                                @foreach ($users as $userId)
                                    @php
                                        $user = App\Models\User::find($userId);
                                        $totalJamPelatihan = $user->sourceFiles()->whereHas('jenisFile', fn($q) => $q->where('name', 'like', '%pelatihan%'))->whereDate('selesai', '>=', now())->sum('jumlah_jam');
                                    @endphp
                                    <li class="border-b border-blue-200 pb-1 last:border-0">
                                        <span class="font-semibold">{{ $user->name ?? '-' }}</span>: {{ $totalJamPelatihan }} Jam
                                    </li>
                                @endforeach
                            @else
                                @php
                                    $totalJamPelatihan = auth()->user()->sourceFiles()->whereHas('jenisFile', fn($q) => $q->where('name', 'like', '%pelatihan%'))->whereDate('selesai', '>=', now())->sum('jumlah_jam');
                                @endphp
                                @if ($totalJamPelatihan > 0)
                                    <li class="font-bold text-lg text-center list-none">{{ $totalJamPelatihan }} Jam</li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Main Grid Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Timer Absensi">
                @if ($jadwals->count() > 1)
                    <div class="mb-4">
                        <label for="jadwalSelect" class="block text-sm font-medium text-gray-700 mb-1">Pilih Jadwal:</label>
                        <select id="jadwalSelect" onchange="window.location = '?jadwal_id=' + this.value"
                            class="block w-full border-gray-300 focus:border-success-500 focus:ring-success-500 rounded-md shadow-sm text-sm">
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" {{ $jadwal->id == $jadwal_id ? 'selected' : '' }}>
                                    {{ $jadwal->shift->nama_shift ?? 'Tanpa Shift' }} ({{ \Carbon\Carbon::parse($jadwal->shift?->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->shift?->jam_keluar)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @elseif ($jadwals->count() === 1)
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-700 border border-gray-200">
                        <i class="fa-solid fa-calendar-day mr-1"></i> <strong>Shift Aktif:</strong>
                        {{ $jadwals[0]->shift->nama_shift ?? 'Tanpa Shift' }}
                        @if($jadwals[0]->shift && $jadwals[0]->shift->nama_shift !== 'L')
                            <span class="bg-success-100 text-success-800 px-2 py-0.5 rounded ml-2">
                                {{ \Carbon\Carbon::parse($jadwals[0]->shift?->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwals[0]->shift?->jam_keluar)->format('H:i') }}
                            </span>
                        @endif
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <livewire:timer :jadwal_id="$jadwal_id" />
                </div>
            </x-card>

            {{-- Jadwal Absen ditaruh di bawah timer untuk layar besar --}}
            <div class="hidden lg:block">
                <x-card title="Jadwal Absen">
                    <livewire:data-jadwal />
                </x-card>
            </div>
        </div>

        <div class="space-y-6">
            <x-card title="Ringkasan Kehadiran">
                <div class="flex flex-col gap-3 p-2">
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div class="font-medium text-gray-600">Sisa Cuti Tahunan</div>
                        <div class="font-bold text-success-700 text-lg">{{ $sisaCutiTahunan }} <span class="text-xs font-normal">Hari</span></div>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div class="font-medium text-gray-600">Terlambat (Bulan Ini)</div>
                        <div class="font-bold text-red-600 text-lg">{{ $jumlahKeterlambatan }} <span class="text-xs font-normal">Kali</span></div>
                    </div>
                    
                    <div class="mt-2">
                        <div class="font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fa-solid fa-notes-medical mr-2 text-blue-500"></i> Rekap Izin:
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="p-2 border rounded flex justify-between">
                                <span class="text-gray-500">Sakit</span>
                                <span class="font-bold">{{ $jumlahIzin['sakit'] }}</span>
                            </div>
                            <div class="p-2 border rounded flex justify-between">
                                <span class="text-gray-500">Tugas</span>
                                <span class="font-bold">{{ $jumlahIzin['tugas'] }}</span>
                            </div>
                            <div class="p-2 border rounded flex justify-between">
                                <span class="text-gray-500">Keluarga</span>
                                <span class="font-bold">{{ $jumlahIzin['keluarga'] }}</span>
                            </div>
                            <div class="p-2 border rounded flex justify-between bg-red-50">
                                <span class="text-red-500">Alpa</span>
                                <span class="font-bold text-red-700">{{ $jumlahTanpaKeterangan }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card title="Menu Pengajuan">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">
                    <a href="{{ route('pengajuan.create', ['tipe' => 'cuti']) }}"
                        class="flex items-center justify-between px-5 py-3 text-white font-medium rounded-xl shadow-sm bg-success-600 hover:bg-success-700 transition-all">
                        <span><i class="fa-solid fa-calendar-minus mr-2"></i> Cuti</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>

                    <a href="{{ route('pengajuan.create', ['tipe' => 'ijin']) }}"
                        class="flex items-center justify-between px-5 py-3 text-white font-medium rounded-xl shadow-sm bg-blue-600 hover:bg-blue-700 transition-all">
                        <span><i class="fa-solid fa-envelope-open-text mr-2"></i> Izin</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>

                    <a href="{{ route('pengajuan.create', ['tipe' => 'tukar_jadwal']) }}"
                        class="flex items-center justify-between px-5 py-3 text-white font-medium rounded-xl shadow-sm bg-yellow-500 hover:bg-yellow-600 transition-all sm:col-span-2 lg:col-span-1">
                        <span><i class="fa-solid fa-repeat mr-2"></i> Tukar Jadwal</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </div>
            </x-card>

            {{-- Jadwal Absen muncul di bawah pada mobile --}}
            <div class="block lg:hidden">
                <x-card title="Jadwal Absen">
                    <livewire:data-jadwal />
                </x-card>
            </div>
        </div>
    </div>
</x-body>