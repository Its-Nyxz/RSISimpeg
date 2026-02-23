<div class="space-y-6 mb-5 px-2 sm:px-0">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <i class="fa-solid fa-gear text-2xl sm:text-3xl text-gray-700"></i>
            <h1 class="text-xl sm:text-2xl font-bold text-success-900">Settings</h1>
        </div>

        <div class="w-full sm:w-auto bg-success-600 text-white text-sm font-bold px-4 py-2 rounded-lg text-center">
            <span class="sm:hidden">NIP: </span>{{ $userprofile->nip ?? '-' }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card :title="'Profile'">
            <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-4 lg:space-y-0 lg:space-x-6">
                <div class="w-28 h-28 sm:w-32 sm:h-32 flex-shrink-0 overflow-hidden rounded-full border-2 border-gray-300">
                    {!! $userprofile->photo
                        ? '<img src="' . asset('storage/photos/' . $userprofile->photo) . '" class="w-full h-full object-cover">'
                        : '<div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500"><i class="fa-solid fa-user text-4xl sm:text-5xl"></i></div>' 
                    !!}
                </div>

                <div class="text-sm text-gray-700 space-y-3 w-full">
                    @php
                        $profileData = [
                            'Nama' => $userprofile->name,
                            'TTL' => ($userprofile->tempat ?? '-') . ', ' . ($userprofile->tanggal_lahir ? formatDate($userprofile->tanggal_lahir) : '-'),
                            'No. KTP' => $userprofile->no_ktp,
                            'No. HP' => $userprofile->no_hp,
                            'No. Rekening' => $userprofile->no_rek,
                            'Pendidikan' => $userprofile->pendidikanUser->deskripsi ?? '-',
                            'Instansi' => $userprofile->institusi,
                            'Struktural' => $userprofile->kategorijabatan->nama ?? '-',
                            'Fungsional' => $userprofile->kategorifungsional->nama ?? '-',
                            'Gender' => $userprofile->jk === null ? '-' : ($userprofile->jk == 1 ? 'Laki-Laki' : 'Perempuan'),
                            'Alamat' => $userprofile->alamat,
                        ];
                    @endphp

                    @foreach ($profileData as $label => $value)
                    <div class="grid grid-cols-12 border-b border-gray-50 pb-1">
                        <div class="col-span-5 font-semibold">{{ $label }}</div>
                        <div class="col-span-7 text-right sm:text-left">: {{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('userprofile.editprofile') }}"
                    class="block text-center sm:inline-block text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                    Edit Profile
                </a>
            </div>
        </x-card>

        <x-card :title="'Login dan Keamanan'">
            <div class="text-sm text-gray-700 space-y-4">
                @php
                    $securityItems = [
                        ['label' => 'NIP', 'value' => $showNip ? ($userprofile->nip ?? '-') : '••••••••', 'route' => null, 'type' => 'toggle'],
                        ['label' => 'WhatsApp', 'value' => $userprofile->no_hp ?? '-', 'route' => 'userprofile.editnomor'],
                        ['label' => 'Email', 'value' => $userprofile->email ?? '-', 'route' => 'userprofile.editemail'],
                        ['label' => 'Username', 'value' => $userprofile->username ?? '-', 'route' => 'userprofile.editusername'],
                    ];
                @endphp

                @foreach ($securityItems as $item)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                    <div class="break-all">
                        <p class="text-xs text-gray-500 uppercase font-bold">{{ $item['label'] }}</p>
                        <p class="text-sm font-medium">{{ $item['value'] }}</p>
                    </div>
                    @if($item['type'] ?? '' === 'toggle')
                        <button wire:click="toggleNip" class="w-full sm:w-auto flex justify-center text-success-900 bg-success-50 p-2 rounded-lg hover:bg-success-600 hover:text-white transition">
                            <i class="{{ $showNip ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash' }}"></i>
                        </button>
                    @elseif($item['route'])
                        <a href="{{ route($item['route']) }}" class="w-full sm:w-auto flex justify-center text-success-900 bg-success-50 p-2 rounded-lg hover:bg-success-600 hover:text-white transition">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    @endif
                </div>
                @endforeach

                @if (!$userprofile->hasRole('Super Admin'))
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Password</p>
                        <p class="text-sm font-medium">************</p>
                    </div>
                    <a href="{{ route('userprofile.editpassword') }}" class="w-full sm:w-auto flex justify-center text-success-900 bg-success-50 p-2 rounded-lg hover:bg-success-600 hover:text-white transition">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-4">
                    <a href="{{ route('userprofile.upload') }}"
                        class="block text-center w-full bg-success-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-success-700 transition">
                        Upload Dokumen pendukung
                    </a>
                </div>
            </div>
        </x-card>
    </div>

    @if (Auth::user()->hasAnyRole([1, 2, 14, 12]))
        <div class="mt-6">
            <x-card :title="'Data Users'">
                <div class="mb-4">
                    <div class="relative w-full sm:w-64 ml-auto">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                        </div>
                        <input type="text" wire:keyup="updateSearch($event.target.value)"
                            placeholder="Cari User..."
                            class="w-full rounded-lg pl-10 pr-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600" />
                    </div>
                </div>

                <div class="relative overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs uppercase bg-success-400 text-success-900">
                            <tr>
                                <th class="px-4 py-3 whitespace-nowrap">Username</th>
                                <th class="px-4 py-3 whitespace-nowrap">Nama</th>
                                <th class="px-4 py-3 whitespace-nowrap text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="odd:bg-white even:bg-success-50 border-b hover:bg-success-100 transition">
                                    <td class="px-4 py-3 font-medium">{{ $user->username ?? '-' }}</td>
                                    <td class="px-4 py-3 break-words min-w-[150px]">{{ $user->name }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}" class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-600 hover:text-white transition">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <button onclick="confirmAlert('Reset password jadi 123?', 'Ya!', () => $wire.resetPassword({{ $user->id }}))" class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-500 hover:text-white transition">
                                                <i class="fa-solid fa-rotate-right"></i>
                                            </button>
                                            <button onclick="confirmAlert('Hapus user?', 'Ya!', () => $wire.deleteUser({{ $user->id }}))" class="p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-500 hover:text-white transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-gray-500">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex flex-wrap justify-center gap-2">
                    {{-- Navigasi pagination disingkat untuk mobile --}}
                    @if (!$users->onFirstPage())
                        <button wire:click="previousPage" class="px-3 py-1 bg-success-100 rounded-md text-xs sm:text-sm">&laquo;</button>
                    @endif
                    
                    <span class="px-4 py-1 bg-success-600 text-white rounded-md text-xs sm:text-sm">
                        Hal {{ $users->currentPage() }} dari {{ $users->lastPage() }}
                    </span>

                    @if ($users->hasMorePages())
                        <button wire:click="nextPage" class="px-3 py-1 bg-success-100 rounded-md text-xs sm:text-sm">&raquo;</button>
                    @endif
                </div>
            </x-card>
        </div>
    @endif
</div>