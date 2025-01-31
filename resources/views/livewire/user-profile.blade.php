<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <i class="fa-solid fa-gear text-3xl text-gray-700"></i>
            <h1 class="text-2xl font-bold text-success-900">Settings</h1>
        </div>

        <!-- Kotak No. KTP -->
        <div class="bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-lg">
            {{ $userprofile->nip ?? '-' }}
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Profile Card -->
        <x-card :title="'Profile'">
            <div class="text-sm text-gray-700 space-y-3">
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Nama</div>
                    <div>: {{ $userprofile->name }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Jabatan</div>
                    <div>: {{ $userprofile->jabatan->nama ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Tempat Tanggal Lahir</div>
                    <div>: {{ $userprofile->tempat }}, {{ $userprofile->tanggal_lahir }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Tanggal Tetap</div>
                    <div>: {{ $userprofile->tanggal_tetap ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Pendidikan Awal</div>
                    <div>: {{ $userprofile->pendidikan_awal ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Pendidikan Penyesuaian</div>
                    <div>: {{ $userprofile->pendidikan_penyesuaian ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Tanggal Penyesuaian</div>
                    <div>: {{ $userprofile->tgl_penyesuaian ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="font-semibold">Informasi Pensiun</div>
                    <div>: {{ $userprofile->pensiun ?? '-' }}</div>
                </div>
            </div>

            <!-- Tombol Edit Profile -->
            <div class="mt-4">
                <a href="{{ route('userprofile.editprofile') }}"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    Edit Profile
                </a>
            </div>
        </x-card>

        <!-- Login & Security Card -->
        <x-card :title="'Login dan Keamanan'">
            <div class="text-sm text-gray-700 space-y-3">
                <p><strong>NIP:</strong> {{ $userprofile->nip ?? '-' }}</p>

                <div class="flex items-center justify-between">
                    <p><strong>No. WhatsApp:</strong> {{ $userprofile->no_hp ?? '-' }}</p>
                    <a href="{{ route('userprofile.editnomor') }}"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>

                <div class="flex items-center justify-between">
                    <p><strong>Email:</strong> {{ $userprofile->email ?? '-' }}</p>
                    <a href="{{ route('userprofile.editemail') }}"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>

                <div class="flex items-center justify-between">
                    <p><strong>Password:</strong> ************</p>
                    <a href="{{ route('userprofile.editpassword') }}"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>
            </div>
        </x-card>
    </div>
</div>
