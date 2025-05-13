<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $slip->bruto?->user->name ?? 'Karyawan' }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 16px;
        }

        .label {
            width: 40%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td,
        th {
            padding: 6px 8px;
            border: 1px solid #999;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Slip Gaji Karyawan</h2>
        <p>Bulan {{ $slip->bruto?->bulan_penggajian }}/{{ $slip->bruto?->tahun_penggajian }}</p>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="label">Nama</td>
                <td>{{ $slip->bruto?->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td>{{ $slip->bruto?->user->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Karyawan</td>
                <td>{{ ucfirst($slip->bruto?->user->jenis?->nama ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Transfer</td>
                <td>{{ \Carbon\Carbon::parse($slip->tanggal_transfer)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Komponen Gaji</h4>
        <table>
            <tr>
                <td>Gaji Pokok</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_gapok ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Jabatan</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_jabatan ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Fungsi</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_fungsi ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Umum</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_umum ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Makan</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_makan ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Transport</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_transport ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Khusus</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_khusus ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Lainnya</td>
                <td class="right">Rp {{ number_format($slip->bruto?->nom_lainnya ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="bold">
                <td>Total Bruto</td>
                <td class="right">Rp {{ number_format($slip->bruto?->total_bruto ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Potongan</h4>
        <table>
            @forelse ($slip->bruto?->potongan ?? [] as $p)
                <tr>
                    <td>{{ $p->masterPotongan->nama }}</td>
                    <td class="right">Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada potongan.</td>
                </tr>
            @endforelse
            @if ($slip->bruto?->potongan->count())
                <tr class="bold">
                    <td>Total Potongan</td>
                    <td class="right">
                        Rp {{ number_format($slip->bruto->potongan->sum('nominal'), 0, ',', '.') }}
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <table>
            <tr class="bold">
                <td>Total Gaji Diterima (Netto)</td>
                <td class="right">Rp {{ number_format($slip->total_netto ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
