<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Export Data Keuangan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        thead {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Data Keuangan Karyawan</h2>
    <p>Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Unit Kerja</th>
                <th>Jenis</th>
                <th>Total Bruto</th>
                <th>Total Potongan</th>
                <th>Netto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @php
                    $brutoModel = $user
                        ->gajiBruto()
                        ->where('bulan_penggajian', $bulan)
                        ->where('tahun_penggajian', $tahun)
                        ->first();

                    $bruto = $brutoModel?->total_bruto ?? 0;
                    $potongan = $brutoModel?->potongan->sum('nominal') ?? 0;
                    $netto = $bruto - $potongan;
                @endphp
                <tr>
                    <td>{{ $user->nama_bersih }}</td>
                    <td>{{ $user->nik }}</td>
                    <td>{{ $user->unitKerja->nama ?? '-' }}</td>
                    <td>{{ $user->jenis->nama ?? '-' }}</td>
                    <td>Rp {{ number_format($bruto, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($potongan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($netto, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
