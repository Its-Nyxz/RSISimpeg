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

        /* Hijau muda */
        .odd-row {
            background-color: #f0fdf4;
        }

        /* Lebih terang */
        .holiday-row {
            background-color: #fecaca;
        }

        /* Merah muda */
        .small-text {
            font-size: 10px;
            color: #4b5563;
        }

        /* Keterangan lebih kecil */
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Absensi {{ $user->name }} - {{ $title }}</h2>

    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Jam Kerja</th>
                <th>Rencana Kerja</th>
                <th>Laporan Kerja</th>
                <th>Jam Lembur</th>
                <th>Deskripsi Lembur</th>
                <th>Real-Time</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr class="{{ $item['is_holiday'] ? 'holiday-row' : ($index % 2 == 0 ? 'even-row' : 'odd-row') }}">
                    <td>{{ $item['hari'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['jam_kerja'] }}</td>
                    <td>{{ $item['rencana_kerja'] }}</td>
                    <td>
                        {{ $item['laporan_kerja'] }}
                        <div class="small-text">
                            <strong>Keterangan:</strong> {{ $item['keterangan'] ?? '-' }}
                        </div>
                    </td>
                    <td>{{ $item['jam_lembur'] }}</td>
                    <td>{{ $item['laporan_lembur'] ?? '-' }}</td>
                    <td>
                        <div class="small-text">
                            <strong>Masuk:</strong> {{ $item['real_masuk'] ?? '-' }}
                            <strong>Selesai:</strong> {{ $item['real_selesai'] ?? '-' }}
                        </div>
                    </td>
                    <td>{{ $item['feedback'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
