<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Slip Gaji</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td,
        th {
            padding: 6px;
            border: 1px solid #ccc;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .rp {
            width: 30px;
            text-align: left;
            white-space: nowrap;
        }

        .nominal {
            text-align: right;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Slip Gaji Detail</h3>

    <p><strong>Nama:</strong> {{ $user->name }}</p>
    <p><strong>Bulan:</strong> {{ $bulan }}/{{ $tahun }}</p>

    <h4>Gaji Bruto</h4>
    <table>
        <tr>
            <td>Gaji Pokok</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_gapok ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Jabatan</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_jabatan ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Fungsional</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_fungsi ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Umum</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_umum ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Transport</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_transport ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Makan</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_makan ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Khusus</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_khusus ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunj. Kinerja</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->nom_lainnya ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr class="bold">
            <td>Total Bruto</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($gajiBruto->total_bruto ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h4>Potongan</h4>
    <table>
        @forelse ($potonganList as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($item->nominal ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Tidak ada potongan.</td>
            </tr>
        @endforelse
        <tr class="bold">
            <td>Total Potongan</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($totalPotongan ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h4>Total Gaji Diterima</h4>
    <table>
        <tr class="bold">
            <td>Total Diterima</td>
            <td class="rp">Rp</td>
            <td class="nominal">{{ number_format($netto ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
