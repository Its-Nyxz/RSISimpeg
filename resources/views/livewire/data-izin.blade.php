<div x-data="{ open: false }" class="relative mb-6">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">
            {{ $isRiwayatIzin ? 'Riwayat Izin Karyawan' : 'Approval Izin' }}
        </h1>
    </div>

    @if (!$isRiwayatIzin)
        <!-- Tabel Data izin Karyawan -->
        <div class="flex flex-wrap gap-3 justify-center md:justify-start w-full mb-3">
            <select wire:model.live="selectedUserAktif" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="1">Aktif</option>
                <option value="0">Non Aktif</option>
            </select>
            
            <livewire:searchable-pillbox wire:model.live="selectedUnit" :options="$units" label-key="nama" placeholder="Cari Unit" />

            <select wire:model.live="selectedJenisKaryawan" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                <option value="">Semua Jenis Karyawan</option>
                @foreach ($jenisKaryawans as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
            
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari..."
                class="w-full md:w-64 rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-center text-gray-700">
                <thead class="text-sm uppercase bg-success-400 text-success-900">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Jabatan</th>
                        <th scope="col" class="px-6 py-3">Tanggal Mulai</th>
                        <th scope="col" class="px-6 py-3">Tanggal Selesai</th>
                        <th scope="col" class="px-6 py-3">Jumlah Hari</th>
                        <th scope="col" class="px-6 py-3">Jenis Izin</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userIzin as $izin)
                        <tr
                            class="{{ $izin->status_izin_id == 2 ? 'bg-red-200' : 'odd:bg-success-50 even:bg-success-100' }} border-b border-success-300 hover:bg-success-300">
                            <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                {{ $izin->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $izin->user->kategorijabatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ formatDate($izin->tanggal_mulai) ?? '-' }}</td>
                            <td class="px-6 py-4">{{ formatDate($izin->tanggal_selesai) ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $izin->jumlah_hari ?? '-' }} Hari</td>
                            <td class="px-6 py-4">{{ $izin->jenisIzin->nama_izin ?? '-' }}</td>
                            <td
                                class="px-6 py-4 font-extrabold whitespace-nowrap {{ $izin->status_izin_id == 1 ? 'text-success-900' : ($izin->status_izin_id == 2 ? 'text-red-900' : 'text-gray-900') }}">
                                {{ $izin->statusIzin->nama_status ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    @if ($izin->status_izin_id == 3 || ($isKepegawaian && $izin->status_izin_id == 4))
                                        <button
                                            onclick="confirmAlert('Ingin menyetujui izin ini?', 'Ya, Setujui!', () => @this.call('approveIzin', {{ $izin->id }}, {{ $izin->user->id }}))"
                                            class="bg-success-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                            <i class="fa-solid fa-check"></i> Disetujui
                                        </button>

                                        <button
                                            onclick="confirmRejectWithReason('Ingin menolak izin ini?', 'Ya, Tolak!', (reason) => @this.call('rejectIzin', {{ $izin->id }}, {{ $izin->user->id }}, reason))"
                                            class="bg-red-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                            <i class="fa-solid fa-xmark"></i> Ditolak
                                        </button>
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center px-6 py-4">Tidak ada data Ijin Karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigasi Pagination -->
        <div class="mt-4 flex gap-2 justify-center items-center">
            @if ($userIzin->onFirstPage() == false)
                <button wire:click="previousPage" wire:loading.attr="disabled"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    &laquo; Sebelumnya
                </button>
            @endif

            @php
                $totalPages = $userIzin->lastPage();
                $currentPage = $userIzin->currentPage();
                $range = 3;
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
                    <span class="px-2 py-1 bg-success-600 text-white rounded-md text-sm">{{ $page }}</span>
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

            @if ($userIzin->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                    class="px-2 py-1 bg-success-100 hover:bg-success-600 text-success-900 rounded-md text-sm">
                    Selanjutnya &raquo;
                </button>
            @endif
        </div>
    @else
        {{-- Riwayat Izin mode: filters, list users and selected user's izin history --}}
        <div class="mb-4">
            <h2 class="text-xl font-bold text-success-900">Karyawan</h2>
            <div class="flex flex-wrap gap-3 justify-center md:justify-start w-full">
                @php
                    $minDate = \Carbon\Carbon::createFromDate($tahun ?: now()->year, $bulan ?: now()->month, 1)->startOfMonth()->format('Y-m-d');
                    $maxDate = \Carbon\Carbon::createFromDate($tahun ?: now()->year, $bulan ?: now()->month, 1)->endOfMonth()->format('Y-m-d');
                @endphp
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative flex items-center" onclick="this.querySelector('input').showPicker()">
                        <input type="date" wire:model.live="tanggalMulaiFilter" min="{{ $minDate }}" max="{{ $tanggalSelesaiFilter ?: $maxDate }}" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto cursor-pointer" title="Dari Tanggal">
                        @if($tanggalMulaiFilter)
                            <button wire:click.stop="$set('tanggalMulaiFilter', '')" class="ml-1 text-gray-400 hover:text-red-500" title="Clear"><i class="fa-solid fa-circle-xmark"></i></button>
                        @endif
                    </div>
                    <span class="text-gray-500 font-medium">S/D</span>
                    <div class="relative flex items-center" onclick="this.querySelector('input').showPicker()">
                        <input type="date" wire:model.live="tanggalSelesaiFilter" min="{{ $tanggalMulaiFilter ?: $minDate }}" max="{{ $maxDate }}" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto cursor-pointer" title="Sampai Dengan Tanggal">
                        @if($tanggalSelesaiFilter)
                            <button wire:click.stop="$set('tanggalSelesaiFilter', '')" class="ml-1 text-gray-400 hover:text-red-500" title="Clear"><i class="fa-solid fa-circle-xmark"></i></button>
                        @endif
                    </div>
                </div>
                <select wire:model.live="bulan" x-on:change="
                    let y = $wire.tahun || new Date().getFullYear();
                    let m = $event.target.value;
                    let max = new Date(y, m, 0).getDate();
                    let d1 = $wire.tanggalMulaiFilter ? Math.min(parseInt($wire.tanggalMulaiFilter.split('-')[2]), max) : 1;
                    let d2 = $wire.tanggalSelesaiFilter ? Math.min(parseInt($wire.tanggalSelesaiFilter.split('-')[2]), max) : max;
                    $wire.set('tanggalMulaiFilter', `${y}-${String(m).padStart(2, '0')}-${String(d1).padStart(2, '0')}`);
                    $wire.set('tanggalSelesaiFilter', `${y}-${String(m).padStart(2, '0')}-${String(d2).padStart(2, '0')}`);"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select wire:model.live="tahun" x-on:change="
                    let y = $event.target.value;
                    let m = $wire.bulan || new Date().getMonth() + 1;
                    let max = new Date(y, m, 0).getDate();
                    let d1 = $wire.tanggalMulaiFilter ? Math.min(parseInt($wire.tanggalMulaiFilter.split('-')[2]), max) : 1;
                    let d2 = $wire.tanggalSelesaiFilter ? Math.min(parseInt($wire.tanggalSelesaiFilter.split('-')[2]), max) : max;
                    $wire.set('tanggalMulaiFilter', `${y}-${String(m).padStart(2, '0')}-${String(d1).padStart(2, '0')}`);
                    $wire.set('tanggalSelesaiFilter', `${y}-${String(m).padStart(2, '0')}-${String(d2).padStart(2, '0')}`);"
                    class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    @foreach (range(now()->year - 5, now()->year) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
                <select wire:model.live="selectedUserAktif" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 w-full sm:w-auto">
                    <option value="1">Aktif</option>
                    <option value="0">Non Aktif</option>
                </select>
                <livewire:searchable-pillbox wire:model.live="selectedUnit" :options="$units" label-key="nama" placeholder="Cari Unit" />
                <select wire:model.live="selectedJenisKaryawan" class="rounded-lg px-2 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                    <option value="">Semua Jenis Karyawan</option>
                    @foreach ($jenisKaryawans as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari..."
                    class="w-full md:w-64 rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />

                <button wire:click="export({
                        bulan: {{ $bulan ?: now()->month }},
                        tahun: {{ $tahun ?: now()->year }},
                        @php
                            if ($selectedUnit) {
                                $unit = implode(', ', array_column($selectedUnit, 'nama'));
                                $unitId = $selectedUnit[0]['id'];
                            }
                        @endphp
                        unitId: '{{ $unitId ?? '' }}',
                        unit: '{{ $unit ?? '' }}',
                        jenis: '{{ $selectedJenisKaryawan }}',
                        keyword: '{{ $search }}',
                        mode: 'all',
                        selected: 'none'
                    })"
                    wire:loading.attr="disabled"
                    style="margin-left: auto;"
                    class="bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition font-bold py-2 px-4 rounded-lg flex items-center gap-2">
                    <i wire:loading.remove wire:target="export" class="fas fa-file-excel"></i>
                    <i wire:loading wire:target="export" class="fas fa-spinner fa-spin"></i>
                    @if ($selectedUnit)
                        Export {{ $selectedUnit[0]['nama'] }}
                    @else
                        Export {{ \Carbon\Carbon::createFromDate($tahun ?: now()->year, $bulan ?: now()->month, 1)->locale('id')->translatedFormat('F Y') }}
                    @endif
                </button>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-success-200 mt-2">
                <table class="w-full text-sm text-center text-gray-700">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">NIP</th>
                            <th class="px-6 py-3">Jabatan</th>
                            <th class="px-6 py-3">Unit</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                            <tr class="border-b border-success-300 hover:bg-success-300 transition duration-150 cursor-pointer {{ $u->id == $selectedRiwayatUserId ? 'bg-success-300 font-bold' : 'odd:bg-success-50 even:bg-success-100' }}"
                                wire:click="selectRiwayatUser({{ $u->id }}, '{{ $u->name }}')">
                                <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                <td class="px-6 py-3">{{ $u->name }}</td>
                                <td class="px-6 py-3">{{ $u->nip ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $u->kategorijabatan->nama ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $u->unitKerja->nama ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    <button class="bg-success-600 hover:bg-success-700 text-white px-3 py-1.5 rounded-lg text-md transition">
                                        <i class="fa-solid fa-eye text-md"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center">Tidak ada data izin karyawan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex gap-2 justify-center items-center">
                @if ($users && $users->hasPages())
                    <button wire:click="previousPage('usersPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm" {{ $users->onFirstPage() ? 'disabled' : '' }}>&laquo; Prev</button>
                    @php
                        $totalPagesTop = $users->lastPage();
                        $currentPageTop = $users->currentPage();
                        $rangeTop = 2;
                    @endphp
                    @for ($page = max(1, $currentPageTop - $rangeTop); $page <= min($totalPagesTop, $currentPageTop + $rangeTop); $page++)
                        <button wire:click="gotoPage({{ $page }}, 'usersPage')" class="px-2 py-1 {{ $page == $currentPageTop ? 'bg-success-600 text-white' : 'bg-success-100 text-success-900' }} rounded-md text-sm">{{ $page }}</button>
                    @endfor
                    <button wire:click="nextPage('usersPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm" {{ !$users->hasMorePages() ? 'disabled' : '' }}>Next &raquo;</button>
                @endif
            </div>
        </div>

        @if ($selectedRiwayatUserId)
            <div class="pt-4">
                <div class="flex justify-between items-center mb-2 gap-2">
                    <h3 class="text-lg font-bold text-success-900">Riwayat Izin: {{ $selectedRiwayatUserName }}</h3>
                    @if ($selectedRiwayatUserId)
                        <button wire:click="export({
                                bulan: {{ $bulan ?: now()->month }},
                                tahun: {{ $tahun ?: now()->year }},
                                @php
                                    if ($selectedUnit) {
                                        $unit = implode(', ', array_column($selectedUnit, 'nama'));
                                        $unitId = $selectedUnit[0]['id'];
                                    }
                                @endphp
                                unitId: '{{ $unitId ?? '' }}',
                                unit: '{{ $unit ?? '' }}',
                                jenis: '{{ $selectedJenisKaryawan }}',
                                keyword: '{{ $search }}',
                                mode: 'user',
                                selected: '{{ $selectedRiwayatUserName }}'
                            })"
                            wire:loading.attr="disabled"
                            style="margin-left: auto;"
                            class="bg-success-100 text-success-900 hover:bg-success-600 hover:text-white transition font-bold py-2 px-4 rounded-lg flex items-center gap-2">
                            <i wire:loading.remove wire:target="export" class="fas fa-file-excel"></i>
                            <i wire:loading wire:target="export" class="fas fa-spinner fa-spin"></i>
                            Export {{ $selectedRiwayatUserName }}
                        </button>
                    @endif
                    <button wire:click="closeRiwayatUser" class="flex flex-row items-center bg-gray-50 xt-sm text-red-600 hover:text-gray-50 font-semibold px-3 py-1 border border-red-200 hover:bg-red-600 rounded-lg gap-4">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-center text-gray-700">
                        <thead class="text-sm uppercase bg-success-400 text-success-900">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Tanggal Mulai</th>
                                <th class="px-6 py-3">Tanggal Selesai</th>
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-2 py-4 text-left">Lain2</th>
                                <th class="px-6 py-3">Bukti Izin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatIzinDetail as $iz)
                                <tr class="{{ $iz->status_izin_id == 2 ? 'bg-red-50' : 'bg-white' }} border-b hover:bg-gray-50 text-md">
                                    <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-3">{{ formatDate($iz->tanggal_mulai) }}</td>
                                    <td class="px-6 py-3">{{ formatDate($iz->tanggal_selesai) }}</td>
                                    <td class="px-6 py-3">{{ $iz->jumlah_hari }} Hari</td>
                                    <td class="px-6 py-3">{{ $iz->jenisIzin->nama_izin ?? '-' }}</td>
                                    <td class="px-2 py-4 font-bold {{ $iz->status_izin_id == 1 ? 'text-success-600' : ($iz->status_izin_id == 2 ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ $iz->statusIzin->nama_status ?? '-' }}
                                    </td>
                                    @php
                                        $approvals = \App\Models\RiwayatApproval::with('approver')->where('id', $iz->id)->orderBy('approve_at', 'asc')->get();
                                    @endphp
                                    <td class="px-2 py-4 text-left">
                                        <div class="mb-1 last:mb-0 pb-1 last:border-0 ">
                                            <span class="font-semibold">Tgl Pengajuan:</span><br>
                                            <span>{{ \Carbon\Carbon::parse($iz->created_at)->locale('id')->translatedFormat('l, d F') ?? '-' }}</span><br>
                                        </div>
                                        <div>
                                            <span class="font-semibold">Keterangan:</span><br>
                                            <span>{{ $iz->keterangan ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col items-center gap-2">
                                            @if ($iz->bukti_izin)
                                                <img src="{{ asset('storage/photos/bukti-izin/' . $iz->bukti_izin) }}" alt="Bukti Izin" class="max-w-[100px] h-auto rounded-lg border border-gray-300">
                                                <a href="{{ asset('storage/photos/bukti-izin/' . $iz->bukti_izin) }}" target="_blank" class="text-success-600 hover:text-success-900 font-semibold">Lihat</a>
                                            @else
                                                tidak ditemukan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="p-4 text-center">Belum ada riwayat izin.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 flex gap-2 justify-center items-center">
                    @if ($riwayatIzinDetail && $riwayatIzinDetail->hasPages())
                        <button wire:click="previousPage('detailsPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm" {{ $riwayatIzinDetail->onFirstPage() ? 'disabled' : '' }}>&laquo; Prev</button>
                        @php
                            $total = $riwayatIzinDetail->lastPage();
                            $current = $riwayatIzinDetail->currentPage();
                        @endphp
                        @for ($p = max(1, $current - 2); $p <= min($total, $current + 2); $p++)
                            <button wire:click="gotoPage({{ $p }}, 'detailsPage')" class="px-2 py-1 {{ $p == $current ? 'bg-success-600 text-white' : 'bg-success-100 text-success-900' }} rounded-md text-sm">{{ $p }}</button>
                        @endfor
                        <button wire:click="nextPage('detailsPage')" class="px-2 py-1 bg-success-100 text-success-900 rounded-md text-sm" {{ !$riwayatIzinDetail->hasMorePages() ? 'disabled' : '' }}>Next &raquo;</button>
                    @endif
                </div>
            </div>
        @else
            <div class="mt-6 p-8 border-2 border-dashed border-gray-400 rounded-xl text-center bg-gray-50">
                <h3 class="text-lg font-medium text-gray-500">Tekan "Detail" di atas untuk melihat detail riwayat izin.</h3>
            </div>
        @endif
    @endif
    @if (session()->has('message'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('message') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
</div>
