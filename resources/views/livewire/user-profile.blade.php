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

    @php
        $roles = ['Super Admin', 'Kepala Seksi Kepegawaian', 'Staf Seksi Kepegawaian', 'Administrator'];
    @endphp

    @if (Auth::user()->hasAnyRole($roles))
        <div>
            <x-card :title="'Data Users'">
                <div class="flex justify-end mb-3">
                    {{-- <h1 class="text-2xl font-bold text-success-900">Data Users</h1> --}}
                    <div class="flex justify-between items-center gap-4 mb-3">
                        <div class="flex-1">
                            <input type="text" wire:keyup="updateSearch($event.target.value)"
                                placeholder="Cari User..."
                                class="w-full rounded-lg px-10 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                        </div>
                        {{-- <a href="{{ route('users.create') }}"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        + Tambah User
                    </a> --}}
                    </div>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-center text-gray-700">
                        <thead class="text-sm uppercase bg-success-400 text-success-900">
                            <tr>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                {{-- <th scope="col" class="px-6 py-3">Password</th> --}}
                                {{-- <th scope="col" class="px-6 py-3">Jabatan</th> --}}
                                {{-- <th scope="col" class="px-6 py-3">Unit</th> --}}
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr
                                    class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                    <td class="px-6 py-3">{{ $user->username ?? '-' }}</td>
                                    <td scope="row" class=" font-medium text-success-900 whitespace-nowrap">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-3">{{ $user->email ?? '-' }}</td>
                                    {{-- <td class="px-6 py-3">{{ $user->password ?? '-' }}</td> --}}
                                    {{-- <td class="px-6 py-3">{{ $user->kategorijabatan->nama ?? '-' }}</td> --}}
                                    {{-- <td class="px-6 py-3">{{ $user->unitKerja->nama ?? '-' }}</td> --}}
                                    <td class="px-6 py-3 flex justify-center items-center gap-2">
                                        <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                            class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form id="resetPassword-form-{{ $user->id }}"
                                            action="{{ route('users.resetPassword', $user->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <a href="javascript:void(0);"
                                            onclick="confirmAlert('Ingin mereset password menjadi 123?', 'Ya, Yakin!',function() { 
                                                document.getElementById('resetPassword-form-{{ $user->id }}').submit();
                                            })"
                                            class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                                            <i class="fa-solid fa-rotate-right"></i>
                                        </a>
                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="javascript:void(0);"
                                            onclick="confirmAlert('Ingin menghapus user ini?', 'Ya, Hapus!',function() { 
                                                document.getElementById('delete-form-{{ $user->id }}').submit();
                                            })"
                                            class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center px-6 py-4">Tidak ada data User.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex gap-2 justify-center items-center">

                    @if (!$users->onFirstPage())
                        <button wire:click="previousPage" wire:loading.attr="disabled"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            &laquo; Sebelumnya
                        </button>
                    @endif

                    @php
                        $totalPages = $users->lastPage();
                        $currentPage = $users->currentPage();
                        $range = 3; // Range around current page
                    @endphp

                    @if ($currentPage > $range + 1)
                        <button wire:click="gotoPage(1)"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            1
                        </button>
                        @if ($currentPage > $range + 2)
                            <span class="px-2 py-1 text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($page = max($currentPage - $range, 1); $page <= min($currentPage + $range, $totalPages); $page++)
                        @if ($page == $currentPage)
                            <span
                                class="px-2 py-1 bg-success-600 text-white rounded-md text-sm">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})"
                                class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                                {{ $page }}
                            </button>
                        @endif
                    @endfor


                    @if ($currentPage < $totalPages - $range)
                        @if ($currentPage < $totalPages - $range - 1)
                            <span class="px-2 py-1 text-gray-500">...</span>
                        @endif
                        <button wire:click="gotoPage({{ $totalPages }})"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            {{ $totalPages }}
                        </button>
                    @endif


                    @if ($users->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled"
                            class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                            Selanjutnya &raquo;
                        </button>
                    @endif
                </div>
            </x-card>
        </div>
    @endif
</div>
