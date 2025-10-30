<table style="width:100%; border-collapse: collapse;">
    <thead>
        {{-- Informasi Pengguna --}}
        <tr>
            <th colspan="11" style="text-align:left;">
                <strong>Nama:</strong> {{ $user->name ?? '-' }}
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align:left;">
                <strong>Unit:</strong> {{ $user->unit->nama ?? '-' }}
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align:left;">
                <strong>Jabatan:</strong>
                {{ $user->jabatan?->nama ?? ($user->fungsi?->nama ?? ($user->umum?->nama ?? '-')) }}
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align:left;">
                <strong>Periode:</strong> {{ $title }}
            </th>
        </tr>

        <tr>
            <td colspan="11" style="height:10px;"></td>
        </tr>

        {{-- Header Tabel Utama --}}
        <tr style="background-color:#f2f2f2;">
            <th>Hari</th>
            <th>Tanggal</th>
            <th>Jam Kerja</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Jam Lembur</th>
            <th>Rencana Kerja</th>
            <th>Laporan Kerja</th>
            <th>Laporan Lembur</th>
            <th>Feedback</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['hari'] ?? '-' }}</td>
                <td>{{ $item['tanggal'] ?? '-' }}</td>
                <td>{{ $item['jam_kerja'] ?? '-' }}</td>
                <td>{{ $item['real_masuk'] ?? '-' }}</td>
                <td>{{ $item['real_selesai'] ?? '-' }}</td>
                <td>{{ $item['jam_lembur'] ?? '-' }}</td>
                <td>{{ $item['rencana_kerja'] ?? '-' }}</td>
                <td>{{ $item['laporan_kerja'] ?? '-' }}</td>
                <td>{!! $item['laporan_lembur'] ?? '-' !!}</td>
                <td>{!! $item['feedback'] ?? '-' !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
