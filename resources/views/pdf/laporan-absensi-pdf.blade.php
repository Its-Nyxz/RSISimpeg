<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #86efac;
            color: #065f46;
        }

        .even-row {
            background-color: #dcfce7;
        }

        .odd-row {
            background-color: #f0fdf4;
        }

        .holiday-row {
            background-color: #fecaca;
        }

        .small-text {
            font-size: 10px;
            color: #4b5563;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Absensi {{ $user->name }} - {{ $title }}</h2>

    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th> {{-- ✅ Baru --}}
                <th>Jam Keluar</th> {{-- ✅ Baru --}}
                <th>Jam Kerja</th>
                <th>Jam Lembur</th>
                <th>Rencana Kerja</th>
                <th>Laporan Kerja</th>
                <th>Laporan Lembur</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr class="{{ $item['is_holiday'] ? 'holiday-row' : ($index % 2 == 0 ? 'even-row' : 'odd-row') }}">
                    <td>{{ $item['hari'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['real_masuk'] ?? '-' }}</td> {{-- ✅ dari time_in --}}
                    <td>{{ $item['real_selesai'] ?? '-' }}</td> {{-- ✅ dari time_out --}}
                    <td>{{ $item['jam_kerja'] }}</td>
                    <td>{{ $item['jam_lembur'] }}</td>
                    <td>{{ $item['rencana_kerja'] }}</td>
                    <td>{{ $item['laporan_kerja'] }}</td>
                    <td>{{ $item['laporan_lembur'] ?? '-' }}</td>
                    <td>{{ $item['feedback'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
