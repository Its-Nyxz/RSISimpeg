<table>
    <thead>
        <tr>
            <th colspan="{{ count($tanggalJadwal) + 2 }}" style="font-size: 14pt; font-weight: bold; text-align: center;">
                LAPORAN JADWAL ABSENSI
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($tanggalJadwal) + 2 }}" style="text-align: center; font-weight: bold;">
                UNIT: {{ strtoupper($namaUnit) }} | 
                PERIODE: {{ strtoupper($monthName) }} {{ $tahun }}
            </th>
        </tr>
        <tr></tr>

        <tr>
            <th style="background-color: #4ade80; border: 1px solid #000000; font-weight: bold; text-align: center;">NO</th>
            <th style="background-color: #4ade80; border: 1px solid #000000; font-weight: bold; text-align: center;">NAMA</th>
            @foreach ($tanggalJadwal as $tanggal)
                @php
                    $carbonDate = \Carbon\Carbon::parse($tanggal);
                    $isSunday = $carbonDate->format('l') === 'Sunday';
                @endphp
                <th style="text-align: center; border: 1px solid #000000; font-weight: bold; {{ $isSunday ? 'background-color: #ff0000; color: #ffffff;' : 'background-color: #4ade80;' }}">
                    {{ $carbonDate->format('d') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($jadwals as $user_id => $jadwalUser)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000000; white-space: nowrap;">
                    {{ optional(optional($jadwalUser)->first())->user->name ?? '-' }}
                </td>
                @foreach ($tanggalJadwal as $tanggal)
                    @php
                        $shifts = $filteredShifts[$user_id][$tanggal] ?? [];
                        $shiftString = collect($shifts)->pluck('nama_shift')->implode('|');
                        $isSunday = \Carbon\Carbon::parse($tanggal)->format('l') === 'Sunday';
                    @endphp
                    <td style="text-align: center; border: 1px solid #000000; {{ $isSunday ? 'background-color: #fef2f2; color: #dc2626;' : '' }}">
                        {{ $shiftString ?: '-' }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>