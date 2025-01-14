<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <x-card-tanpa-title class="mb-2 text-center">

            <div class="flex flex-col items-left" style="margin-left: 30px;">
                <div class="flex items-left gap-6 mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full" style="background-color: #3C986A;">
                        <i class="fa-solid fa-people-group" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h2 class="text-2xl font-semibold" style="color: #3C986A; margin-top:7px;">Total Karyawan</h2>
                </div>
                <div class="flex items-center gap-6" style="margin-top: 10px; margin-bottom: 10px;">
                    <div class="text-center">
                        <div class="text-3xl font-semibold" style="color: #3C986A;">{{ $totalKaryawan }}</div>
                        <span class="badge rounded-lg" style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">Total</span>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-semibold" style="color: #3C986A;">480</div>
                        <span class="badge rounded-lg" style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">Tetap</span>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-semibold" style="color: #3C986A;">30</div>
                        <span class="badge rounded-lg" style="color: white; background-color:#3C986A; padding-left: 12.5px; padding-right: 12.5px;">Magang</span>
                    </div>
                </div>
                <div class="mt-4 text-left">
                    <a href="#" class="inline-flex items-center px-4 py-2 text-white rounded-lg hover:bg-green-700" style="background-color: #3C986A; border-radius: 100px; ">
                        Lihat   
                        <i class="fa-solid fa-circle-chevron-right ml-2" style="color: #ffffff;"></i>
                    </a>
                </div>
            </div>
        </x-card-tanpa-title>


        <x-card-tanpa-title class="mb-2">

            <div class="flex flex-col items-left" style="margin-left: 30px;">
                <div class="flex items-center gap-6 mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full" style="background-color: #3C986A;">
                        <i class="fa-regular fa-user" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h2 class="text-2xl font-semibold" style="color: #3C986A;">Karyawan Hadir</h2>
                </div>
                <div class="flex items-baseline space-x-1">
                    <!-- Memanggil variabel $totalHadir yang didefinisikan di komponen DataAbsen -->
                    <span class="text-6xl font-semibold" style="color: #3C986A; margin-top: 20px; margin-bottom: 20px;">{{ $totalHadir }}</span>
                    <span class="text-sm" style="color: #3C986A;">orang</span>
                </div>
                <div class="mt-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 text-white rounded-lg hover:bg-green-700" style="background-color: #3C986A; border-radius: 100px; margin-right: 110px;">
                        Lihat   
                        <i class="fa-solid fa-circle-chevron-right ml-2" style="color: #ffffff;"></i>
                    </a>
                </div>
            </div>
        </x-card-tanpa-title>


        <x-card-tanpa-title class="mb-2">

            <div class="flex flex-col items-left" style="margin-left: 30px;">
                <div class="flex items-center gap-6 mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full" style="background-color: #3C986A;">
                        <i class="fa-regular fa-user" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h2 class="text-2xl font-semibold" style="color: #3C986A;">Karyawan Pulang</h2>
                </div>
                <div class="flex items-baseline space-x-1">
                    <span class="text-6xl font-semibold" style="color: #3C986A; margin-top: 20px; margin-bottom: 20px;">{{ $totalPulang }}</span>
                    <span class="text-sm" style="color: #3C986A;">orang</span>
                </div>
                <div class="mt-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 text-white rounded-lg hover:bg-green-700" style="background-color: #3C986A; border-radius: 100px; margin-right: 110px;">
                        Lihat   
                        <i class="fa-solid fa-circle-chevron-right ml-2" style="color: #ffffff;"></i>
                    </a>
                </div>
            </div>
        </x-card-tanpa-title>
    </div>
</div>