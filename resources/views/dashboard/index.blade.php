<x-body>
    <h1 class="text-2xl font-bold text-success-900 mb-4">Dashboard</h1>
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
            <x-card title="Total Karyawan" class="mb-4">
                <div class="flex flex-col items-left" style="margin-left: 30px;">
                    <div class="flex items-left gap-6 mb-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full"
                            style="background-color: #3C986A;">
                            <i class="fa-solid fa-people-group" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h2 class="text-2xl font-semibold" style="color: #3C986A; margin-top:7px;">Total Karyawan</h2>
                    </div>
                    <div class="flex items-center gap-6" style="margin-top: 10px; margin-bottom: 10px;">
                        {{-- <div class="text-center">
                            <div class="text-3xl font-semibold" style="color: #3C986A;">{{ $totalKaryawanAktif }}</div>
                            <span class="badge rounded-lg"
                                style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">
                                Aktif</span>
                        </div> --}}
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
        </div>
    </div>
    <div>
        <x-card title="Jadwal Absen">
            <livewire:data-jadwal />
        </x-card>
    </div>


</x-body>
