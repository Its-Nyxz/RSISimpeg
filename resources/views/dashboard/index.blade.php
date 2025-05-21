<x-body>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-success-900">
            DASHBOARD {{ auth()->user()->unitKerja->nama ?? ' ' }}
        </h1>

        {{-- Notifikasi Masa Berlaku SIP/STR --}}
        @if (count($masaBerlakuSipStr) > 0)
            <div
                class="flex items-center bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 px-4 py-2 rounded-lg text-sm max-w-2xl overflow-y-auto max-h-28">
                <div>
                    <p class="font-bold mb-1">PERHATIAN: Masa Berlaku SIP/STR</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($masaBerlakuSipStr as $file)
                            @php
                                $selesai = \Carbon\Carbon::parse($file->selesai);
                                $sisaHari = intval(now()->diffInDays($selesai));

                            @endphp
                            <li>
                                @if (auth()->user()->unitKerja?->nama === 'KEPEGAWAIAN' || auth()->user()->hasRole('Super Admin'))
                                    <strong>{{ $file->user->name ?? '-' }}</strong> -
                                @endif

                                {{ $file->jenisFile->name ?? '-' }}
                                berakhir <strong>{{ $selesai->format('d M Y') }}</strong>

                                {{-- Kalau sisa hari kurang dari 7 --}}
                                @if ($sisaHari <= 7)
                                    <span class="ml-2 text-red-600 font-bold">(Sisa {{ $sisaHari }} Hari!)</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
    {{-- <livewire:data-absen type="absen" /> --}}
    {{-- <x-card-tanpa-title class="max-w-md">
        <div class="flex flex-col" style="margin-left: 30px;">
            <div class="mb-4">
                <h2 class="text-2xl font-semibold" style="color: #3C986A;">Approval Cuti</h2>
            </div>
            <div class="flex justify-start items-center gap-8">
                <div class="flex flex-col items-start">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 rounded-full" style="background-color:#3C986A;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="text-green-600 font-semibold">Cuti Disetujui</div>
                    </div>
                    <div class="flex items-baseline space-x-1 mb-4">
                        <span class="text-3xl font-semibold text-green-600">21</span>
                        <span class="text-sm text-green-600 ml-2">orang</span>
                    </div>
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 text-white rounded-lg hover:bg-green-700"
                        style="background-color: #3C986A; border-radius: 100px;">
                        Lihat
                        <i class="fa-solid fa-circle-chevron-right ml-2" style="color: #ffffff;"></i>
                    </a>
                </div>
                <div class="flex flex-col items-start">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 rounded-full" style="background-color: #D56262;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="text-red-600 font-semibold">Cuti Ditolak</div>
                    </div>
                    <div class="flex items-baseline space-x-1 mb-4">
                        <span class="text-3xl font-semibold text-red-600">6</span>
                        <span class="text-sm text-red-600 ml-2">orang</span>
                    </div>
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 text-white rounded-lg hover:bg-green-700"
                        style="background-color: #D56262; border-radius: 100px;">
                        Lihat
                        <i class="fa-solid fa-circle-chevron-right ml-2" style="color: #ffffff;"></i>
                    </a>
                </div>
            </div>
        </div>
        </x-card> --}}

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Kolom Kiri (Lebih Besar) -->
        <div class="md:col-span-2">
            <x-card title="Timer" class="mb-4">
                <livewire:timer :jadwal_id="$jadwal_id" />
            </x-card>
        </div>
        <!-- Kolom Kanan -->
        <div class="md:col-span-1">
            {{-- @if (auth()->user()->unitKerja?->nama === 'KEPEGAWAIAN' || auth()->user()->hasRole('Super Admin'))
                <x-card title="Total Karyawan" class="mb-4">
                    <div class="flex flex-col items-left" style="margin-left: 30px;">
                        <div class="flex items-left gap-6 mb-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full"
                                style="background-color: #3C986A;">
                                <i class="fa-solid fa-people-group" style="color: white; font-size: 24px;"></i>
                            </div>
                            <h2 class="text-2xl font-semibold" style="color: #3C986A; margin-top:7px;">Total Karyawan
                            </h2>
                        </div>
                        <div class="flex items-center gap-6 mt-2 mb-2">
                            @foreach ($jumlahKaryawan as $karyawan)
                                <div class="text-center">
                                    <div class="text-3xl font-semibold" style="color: #3C986A;">
                                        {{ $karyawan->total }}
                                    </div>
                                    <span class="badge rounded-lg"
                                        style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">
                                        {{ $karyawan->nama }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-6 mt-2 mb-2">
                            <div class="text-center">
                                <div class="text-3xl font-semibold" style="color: #3C986A;">
                                    {{ $jumlahKaryawanShift }}
                                </div>
                                <span class="badge rounded-lg"
                                    style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">
                                    Shift
                                </span>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-semibold" style="color: #3C986A;">
                                    {{ $jumlahKaryawanNonShift }}
                                </div>
                                <span class="badge rounded-lg"
                                    style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">
                                    Non Shift
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 text-left">
                            <a href="/datakaryawan"
                                class="inline-flex items-center px-4 py-2 text-white rounded-lg hover:bg-green-700"
                                style="background-color: #3C986A; border-radius: 100px; ">
                                Lihat
                                <i class="fa-solid fa-circle-chevron-right ml-2" style="color: #ffffff;"></i>
                            </a>
                        </div>
                    </div>
                </x-card>
            @endif --}}
            <x-card title="Detail" class="mb-4">
                <div class="flex flex-col gap-4 p-4">
                    <div class="flex justify-between">
                        <div class="font-semibold">Sisa Cuti Tahunan</div>
                        <div>{{ $sisaCutiTahunan }} kali</div>
                    </div>
                    <div class="flex justify-between">
                        <div class="font-semibold">Keterlambatan Per Bulan</div>
                        <div>{{ $jumlahKeterlambatan }} kali </div>
                    </div>
                    <div class="font-semibold">
                        Izin :
                    </div>
                    <div class="ml-4 flex flex-col gap-2">
                        <div class="flex justify-between">
                            <div>Sakit</div>
                            <div>{{ $jumlahIzin['sakit'] }} kali</div>
                        </div>
                        <div class="flex justify-between">
                            <div>Tugas</div>
                            <div>{{ $jumlahIzin['tugas'] }} kali</div>
                        </div>
                        <div class="flex justify-between">
                            <div>Keluarga</div>
                            <div>{{ $jumlahIzin['keluarga'] }} kali</div>
                        </div>
                        <div class="flex justify-between">
                            <div>Tanpa Keterangan</div>
                            <div>{{ $jumlahTanpaKeterangan }} kali</div>
                        </div>
                    </div>
                </div>
            </x-card>


            <x-card title="Pengajuan" class="mb-4">
                <div class="flex flex-wrap gap-3 justify-start items-center">
                    <!-- ✅ Tombol Cuti -->
                    <a href="{{ route('pengajuan.create', ['tipe' => 'cuti']) }}"
                        class="inline-flex items-center px-5 py-3 text-white font-medium rounded-full shadow-md
                   bg-green-500 hover:bg-green-400 transition-all duration-300 transform hover:-translate-y-1">
                        Cuti
                        <i class="fa-solid fa-circle-chevron-right ml-2 text-white"></i>
                    </a>

                    <!-- ✅ Tombol Ijin -->
                    <a href="{{ route('pengajuan.create', ['tipe' => 'ijin']) }}"
                        class="inline-flex items-center px-5 py-3 text-white font-medium rounded-full shadow-md
                   bg-blue-500 hover:bg-blue-400 transition-all duration-300 transform hover:-translate-y-1">
                        Ijin
                        <i class="fa-solid fa-circle-chevron-right ml-2 text-white"></i>
                    </a>

                    <!-- ✅ Tombol Tukar Jadwal -->
                    <a href="{{ route('pengajuan.create', ['tipe' => 'tukar_jadwal']) }}"
                        class="inline-flex items-center px-5 py-3 text-white font-medium rounded-full shadow-md
                   bg-yellow-500 hover:bg-yellow-400 transition-all duration-300 transform hover:-translate-y-1">
                        Tukar Jadwal
                        <i class="fa-solid fa-circle-chevron-right ml-2 text-white"></i>
                    </a>
                </div>
            </x-card>


        </div>

    </div>
    <div>
        <x-card title="Jadwal Absen">
            <livewire:data-jadwal />
        </x-card>
    </div>


</x-body>
