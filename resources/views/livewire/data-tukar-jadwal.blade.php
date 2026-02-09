<div x-data="{ open: false }" class="relative">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Approval Tukar Jadwal</h1>
    </div>

    <!-- Tabel Data Tukar Jadwal Karyawan -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">No.</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Tanggal Pergantian</th>
                    <th scope="col" class="px-6 py-3">Shift</th>
                    <th scope="col" class="px-6 py-3">Alasan</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $tukar)
                            <tr class="
                            {{ is_null($tukar->is_approved)
                    ? 'bg-gray-100'
                    : ($tukar->is_approved == 0
                        ? 'bg-red-200'
                        : 'odd:bg-success-50 even:bg-success-100') }}
                            border-b border-success-300 hover:bg-success-300">
                                <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4"> {{ $tukar->user->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $tukar->user->kategorijabatan->nama ?? '-' }}</td>
                                <td class="px-6 py-4">{{ formatDate($tukar->tanggal) ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($tukar->shift)
                                        {{ $tukar->shift->nama_shift }} ({{ $tukar->shift->jam_masuk }} -
                                        {{ $tukar->shift->jam_keluar }})
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $tukar->keterangan ?? '-' }}</td>
                                <td
                                    class="px-6 py-4 font-extrabold whitespace-nowrap
                                         {{ is_null($tukar->is_approved) ? 'text-gray-900' : ($tukar->is_approved ? 'text-success-900' : 'text-red-900') }}">
                                    {{ is_null($tukar->is_approved) ? 'Menunggu' : ($tukar->is_approved ? 'Disetujui' : 'Ditolak') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        @if (is_null($tukar->is_approved))
                                            <button
                                                onclick="confirmAlert('Ingin menyetujui Tukar Jadwal ini?', 'Ya, Setujui!', () => @this.call('approveTukar', {{ $tukar->id }}, {{ $tukar->user->id }}))"
                                                class="bg-success-600 text-white px-3 py-1 rounded-lg flex items-center gap-2">
                                                <i class="fa-solid fa-check"></i> Disetujui
                                            </button>
                                            <button
                                                onclick="confirmRejectWithReason('Ingin menolak Tukar Jadwal ini?', 'Ya, Tolak!', () => @this.call('rejectTukar', {{ $tukar->id }}, {{ $tukar->user->id }}))"
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
                        <td colspan="8" class="text-center px-6 py-4">Tidak ada data Tukar Jadwal Karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-responsive-pagination :data="$users" />
</div>