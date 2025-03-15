<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Master Jadwal Absensi</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Jadwal..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>
            <a href="{{ route('jadwal.create') }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Jadwal
            </a>
        </div>
    </div>
<!-- Filter Bulan & Tahun -->
<div class="flex gap-4 mb-4">
    <select wire:model.live="bulan" class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
        @foreach(range(1,12) as $m)
            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
        @endforeach
    </select>

    <select wire:model.live="tahun" class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
        @foreach(range(now()->year - 5, now()->year) as $y)
            <option value="{{ $y }}">{{ $y }}</option>
        @endforeach
    </select>
</div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Pendidikan</th>
                    <th class="px-4 py-3">Tanggal Masuk</th>
                    <th class="px-4 py-3">Lama Kerja</th>
                    @foreach ($tanggalJadwal as $tanggal)
                        <th class="px-2 py-3 text-center">{{ \Carbon\Carbon::parse($tanggal)->format('d') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $user_id => $jadwalUser)
                    <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                        <td class="px-4 py-3 font-medium text-success-900 whitespace-nowrap">
                            {{ optional(optional($jadwalUser)->first())->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ optional(optional($jadwalUser)->first())->user->pendidikan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ optional(optional($jadwalUser)->first())->user->tanggal_tetap ?? '-' }}</td>
                        <td class="px-4 py-3">{{ optional(optional($jadwalUser)->first())->user->masa_kerja ?? '-' }} tahun</td>
                        @foreach ($tanggalJadwal as $tanggal)
                            <td class="px-2 py-3 text-center">
                                {{ $filteredShifts[$user_id][$tanggal] ?? '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>