<x-body>
    @if ($jadwals->count() > 1)
        <div class="mb-4">
            <label for="jadwalSelect" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Jadwal:
            </label>
            <select id="jadwalSelect" onchange="window.location = '?jadwal_id=' + this.value"
                class="block w-full max-w-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                @foreach ($jadwals as $jadwal)
                    <option value="{{ $jadwal->id }}" {{ $jadwal->id == $jadwal_id ? 'selected' : '' }}>
                        {{ $jadwal->shift->nama_shift ?? 'Tanpa Shift' }} -
                        {{ \Carbon\Carbon::parse($jadwal->shift?->jam_masuk)->format('H:i') }}
                        s/d
                        {{ \Carbon\Carbon::parse($jadwal->shift?->jam_keluar)->format('H:i') }}
                    </option>
                @endforeach
            </select>
        </div>
    @elseif ($jadwals->count() === 1)
        <div class="mb-4 text-sm text-gray-700">
            <p><strong>Shift:</strong>
                {{ $jadwals[0]->shift->nama_shift ?? 'Tanpa Shift' }} -
                {{ \Carbon\Carbon::parse($jadwals[0]->shift?->jam_masuk)->format('H:i') }}
                s/d
                {{ \Carbon\Carbon::parse($jadwals[0]->shift?->jam_keluar)->format('H:i') }}
            </p>
        </div>
    @endif

    @if ($jadwal_id)
        {{-- Timer hanya untuk jadwal terpilih --}}
        <livewire:timer :jadwal_id="$jadwal_id" />
    @else
        <p class="text-gray-500">Tidak ada jadwal ditemukan.</p>
    @endif
</x-body>
