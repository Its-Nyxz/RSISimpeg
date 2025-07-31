<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Export Data Keuangan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        thead {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
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
                <th>NIP</th>
                <th>Unit</th>
                <th>Jenis</th>
                <th>Gapok</th>
                <th>Jabatan</th>
                <th>Fungsi</th>
                <th>Umum</th>
                <th>Khusus</th>
                <th>Makan</th>
                <th>Transport</th>
                <th>Kinerja</th>
                <th>Total Bruto</th>
                @foreach ($masterPotongans as $pot)
                    <th>{{ $pot->nama }}</th>
                @endforeach
                <th>Total Potongan</th>
                <th>Netto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @php $p = $user->potongan_rinci ?? []; @endphp
                <tr>
                    <td>{{ $user->nama_bersih ?? $user->name }}</td>
                    <td>{{ $user->nip }}</td>
                    <td>{{ $user->unitKerja->nama ?? '-' }}</td>
                    <td>{{ $user->jenis->nama ?? '-' }}</td>
                    <td class="text-right">{{ (int) $user->nom_gapok }}</td>
                    <td class="text-right">{{ (int) $user->nom_jabatan }}</td>
                    <td class="text-right">{{ (int) $user->nom_fungsi }}</td>
                    <td class="text-right">{{ (int) $user->nom_umum }}</td>
                    <td class="text-right">{{ (int) $user->nom_khusus }}</td>
                    <td class="text-right">{{ (int) $user->nom_makan }}</td>
                    <td class="text-right">{{ (int) $user->nom_transport }}</td>
                    <td class="text-right">{{ (int) $user->nom_lainnya }}</td>
                    <td class="text-right">{{ (int) $user->total_bruto }}</td>

                    @foreach ($masterPotongans as $pot)
                        <td class="text-right">{{ (int) $p[$pot->nama] ?? 0 }}</td>
                    @endforeach

                    <td class="text-right">{{ (int) $user->total_potongan }}</td>
                    <td class="text-right">{{ (int) $user->netto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
