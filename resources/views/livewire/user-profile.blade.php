<div class="space-y-6 mb-5">
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
            <div class="flex items-center space-x-6">
                <!-- Foto Profile -->
                <div class="w-32 h-32 flex-shrink-0 overflow-hidden rounded-full border-2 border-gray-300">
                    {!! $userprofile->photo
                        ? '<img src="' .
                            asset('storage/photos/' . $userprofile->photo) .
                            '" 
                                                     alt="User Profile" 
                                                     class="w-full h-full object-cover">'
                        : '<div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                                                    <i class="fa-solid fa-user text-5xl"></i>
                                               </div>' !!}
                </div>

                <!-- Data Profile -->
                <div class="text-sm text-gray-700 space-y-3 flex-1">
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">Nama</div>
                        <div>: {{ $userprofile->name }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">Tempat, Tanggal Lahir</div>
                        <div>: {{ $userprofile->tempat ?? '-' }},
                            {{ $userprofile->tanggal_lahir ? formatDate($userprofile->tanggal_lahir) : '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">No. KTP</div>
                        <div>: {{ $userprofile->no_ktp }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">No. HP</div>
                        <div>: {{ $userprofile->no_hp }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">No. Rekening</div>
                        <div>: {{ $userprofile->no_rek }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">Pendidikan </div>
                        <div>: {{ $userprofile->pendidikanUser->deskripsi ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">Institusi</div>
                        <div>: {{ $userprofile->no_ktp }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">Jenis Kelamin</div>
                        <div>: {{ $userprofile->jk == 1 ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="font-semibold">Alamat</div>
                        <div>: {{ $userprofile->alamat }}</div>
                    </div>
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
                <div class="flex items-center justify-between">
                    <p><strong>NIP:</strong>
                        @if ($showNip)
                            {{ $userprofile->nip ?? '-' }}
                        @else
                            ••••••••
                        @endif
                    </p>
                    <button wire:click="toggleNip"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        <i class="{{ $showNip ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash' }}"></i>
                    </button>
                </div>
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
