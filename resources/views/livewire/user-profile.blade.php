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
                                <th class="px-4 py-3 whitespace-nowrap min-w-[200px]">Kelengkapan Data</th>
                                <th class="px-4 py-3 whitespace-nowrap text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    $missingData = $user->getIncompleteData($allJenisFiles ?? null);
                                    $totalMissing = count($missingData);
                                    $remainingCount = $totalMissing - 3;
                                    $titleLines = array_map(function($item, $idx) {
                                        return ($idx + 1) . '. ' . $item['label'];
                                    }, $missingData, array_keys($missingData));
                                    $titleTooltip = "Daftar Data Belum Lengkap (" . $totalMissing . "):\n" . implode("\n", $titleLines);
                                @endphp
                                <tr class="odd:bg-white even:bg-success-50 border-b hover:bg-success-100 transition">
                                    <td class="px-4 py-3 font-medium">{{ $user->username ?? '-' }}</td>
                                    <td class="px-4 py-3 break-words min-w-[150px]">{{ $user->name }}</td>
                                    <td class="px-4 py-3">
                                        @if ($totalMissing === 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-circle-check text-emerald-600"></i> Lengkap
                                            </span>
                                        @else
                                            <div class="space-y-1">
                                                <ul class="space-y-0.5 text-xs text-gray-700 cursor-pointer group"
                                                    wire:click="openMissingModal({{ $user->id }})"
                                                    title="Klik untuk melihat detail modal">
                                                    @foreach (array_slice($missingData, 0, 3) as $item)
                                                        <li class="flex items-center gap-1.5 leading-snug group-hover:text-success-900 transition">
                                                            <span class="w-1.5 h-1.5 rounded-full {{ $item['type'] === 'dokumen' ? 'bg-amber-500' : 'bg-rose-500' }} flex-shrink-0"></span>
                                                            <span class="truncate max-w-[180px]">{{ $item['label'] }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                @if ($totalMissing > 3)
                                                    <button type="button"
                                                        wire:click="openMissingModal({{ $user->id }})"
                                                        class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-0.5 rounded-md text-[11px] font-semibold text-success-800 bg-success-50 hover:bg-success-100 border border-success-200 transition cursor-pointer shadow-xs">
                                                        <span>... (+{{ $remainingCount }} lainnya)</span>
                                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}" class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-600 hover:text-white transition">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <button onclick="confirmAlert('Reset password jadi 123?', 'Ya!', () => @this.call('resetPassword', {{ $user->id }}))" class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-500 hover:text-white transition">
                                                <i class="fa-solid fa-rotate-right"></i>
                                            </button>
                                            <button onclick="confirmAlert('Hapus user?', 'Ya!', () => @this.call('deleteUser', {{ $user->id }}))" class="p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-500 hover:text-white transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-gray-500">Data tidak ditemukan</td>
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

    {{-- Modal Pop-up Rincian Data Belum Lengkap --}}
    @if ($showModalMissing && $selectedUserForModal)
        <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 font-sans"
             wire:click.self="closeMissingModal"
             wire:keydown.escape="closeMissingModal">
            
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-200 flex flex-col max-h-[90vh]">
                
                {{-- Header Modal --}}
                <div class="px-5 py-3.5 bg-success-400 text-success-950 flex justify-between items-center flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-lg text-success-950"></i>
                        <h3 class="font-extrabold text-sm sm:text-base uppercase tracking-tight text-success-950">Kelengkapan Data User</h3>
                    </div>
                    <button type="button" wire:click="closeMissingModal" class="text-success-950 hover:text-red-700 text-xl font-bold p-1 leading-none transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Box Info Nama Karyawan (Jelas & Kontras) --}}
                <div class="bg-success-50 p-4 border-b border-success-200">
                    <span class="block text-[10px] font-bold text-success-800 uppercase tracking-wider">Nama Karyawan</span>
                    <h4 class="text-base font-extrabold text-gray-900 leading-tight mt-0.5">{{ $selectedUserForModal->name }}</h4>
                    @if ($selectedUserForModal->unitKerja || $selectedUserForModal->kategorijabatan)
                        <p class="text-xs text-gray-600 mt-1 font-medium">
                            {{ $selectedUserForModal->unitKerja->nama ?? '' }}
                            @if ($selectedUserForModal->unitKerja && $selectedUserForModal->kategorijabatan) • @endif
                            {{ $selectedUserForModal->kategorijabatan->nama ?? '' }}
                        </p>
                    @endif
                </div>

                {{-- Konten Modal --}}
                <div class="p-5 overflow-y-auto space-y-3">
                    <div class="flex items-center justify-between text-xs text-gray-600 pb-2 border-b border-gray-200">
                        <span>Total Belum Lengkap: <strong class="text-red-600 font-extrabold text-sm">{{ count($missingDataForModal) }}</strong> item</span>
                        <div class="flex items-center gap-2 text-[11px] font-semibold">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Biodata
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Berkas
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1">
                        @forelse ($missingDataForModal as $item)
                            <div class="flex items-center justify-between gap-3 p-2.5 rounded-lg bg-gray-50 hover:bg-success-50/40 transition border border-gray-200 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $item['type'] === 'dokumen' ? 'bg-amber-500' : 'bg-rose-500' }}"></span>
                                    <span class="font-semibold text-gray-800 break-words">{{ $item['label'] }}</span>
                                </div>
                                <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider flex-shrink-0 {{ $item['type'] === 'dokumen' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                    {{ $item['type'] === 'dokumen' ? 'Upload Berkas' : 'Isi Biodata' }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-6 text-emerald-600 font-bold text-sm">
                                <i class="fa-solid fa-circle-check text-2xl mb-1 block"></i>
                                Semua data sudah lengkap!
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-200 flex justify-end flex-shrink-0">
                    <button type="button" wire:click="closeMissingModal" 
                        class="px-5 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>