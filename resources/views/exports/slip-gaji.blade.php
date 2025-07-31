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
    <div class="header">
        <h2>Slip Gaji Karyawan</h2>
        <p>Bulan {{ $slip->bruto?->bulan_penggajian }}/{{ $slip->bruto?->tahun_penggajian }}</p>
    </div>

    <div class="section">
        <table>
            <tr>
                <td>Nama</td>
                <td colspan="2">{{ $slip->bruto?->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td colspan="2">{{ $slip->bruto?->user->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Karyawan</td>
                <td colspan="2">{{ ucfirst($slip->bruto?->user->jenis?->nama ?? '-') }}</td>
            </tr>
            <tr>
                <td>Tanggal Transfer</td>
                <td colspan="2">{{ \Carbon\Carbon::parse($slip->tanggal_transfer)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Komponen Gaji</h4>
        <table>
            <tr>
                <td>Gaji Pokok</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_gapok ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Jabatan</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_jabatan ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Fungsi</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_fungsi ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Umum</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_umum ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Makan</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_makan ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Transport</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_transport ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Khusus</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_khusus ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunj. Kinerja</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->nom_lainnya ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="bold">
                <td>Total Bruto</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->bruto?->total_bruto ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Potongan</h4>
        <table>
            @forelse ($slip->bruto?->potongan ?? [] as $p)
                <tr>
                    <td>{{ $p->masterPotongan->nama }}</td>
                    <td class="rp">Rp</td>
                    <td class="nominal">{{ number_format($p->nominal ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Tidak ada potongan.</td>
                </tr>
            @endforelse

            @if ($slip->bruto?->potongan->count())
                <tr class="bold">
                    <td>Total Potongan</td>
                    <td class="rp">Rp</td>
                    <td class="nominal">{{ number_format($slip->bruto->potongan->sum('nominal'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <table>
            <tr class="bold">
                <td>Total Gaji Diterima (Netto)</td>
                <td class="rp">Rp</td>
                <td class="nominal">{{ number_format($slip->total_netto ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
