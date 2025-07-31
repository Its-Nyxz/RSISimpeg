<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Absensi - Bulan {{ \Carbon\Carbon::createFromFormat('m', $month)->locale('id')->translatedFormat('F') }} Tahun {{ $year }}</h2>
    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Jam Kerja</th>
                <th>Rencana Kerja</th>
                <th>Laporan Kerja</th>
                <th>Jam Lembur</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['hari'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['jam_kerja'] }}</td>
                    <td>{{ $item['rencana_kerja'] }}</td>
                    <td>{{ $item['laporan_kerja'] }}</td>
                    <td>{{ $item['jam_lembur'] }}</td>
                    <td>{{ $item['feedback'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
