<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $slip->bruto?->user->name ?? 'Karyawan' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 20px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .subheader {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            text-decoration: underline;
        }

        .label {
            width: 250px;
            padding-left: 10px;
        }

        .indent {
            padding-left: 25px;
        }

        .rp {
            width: 30px;
            text-align: left;
        }

        .val {
            width: 120px;
            text-align: right;
            border-bottom: 1px dotted #000;
        }

        .center-val {
            text-align: center;
        }

        .total-label {
            font-weight: bold;
            text-align: right;
            padding-right: 15px;
        }

        .total-val {
            font-weight: bold;
            text-align: right;
            border-bottom: 2px solid #000;
        }

        .netto-row td {
            font-weight: bold;
            font-size: 13px;
            padding-top: 20px;
        }

        .netto-border {
            border-bottom: 3px double #000;
        }

        .signature-area {
            float: right;
            width: 200px;
            margin-top: 20px;
            text-align: center;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="header">SLIP PENERIMAAN GAJI KARYAWAN {{ strtoupper($slip->bruto?->user->jenis?->nama ?? 'TETAP') }}
    </div>
    <div class="subheader">BULAN
        {{ strtoupper(\Carbon\Carbon::createFromFormat('m', $slip->bruto?->bulan_penggajian ?? now()->month)->translatedFormat('F')) }}
        {{ $slip->bruto?->tahun_penggajian }}</div>

    <table class="info-table">
        <tr>
            <td style="width: 70px;">NO URUT</td>
            <td>: {{ $slip->bruto?->user->urutanKeuangan->urutan ?? '-' }}</td>
        </tr>
        <tr>
            <td>N A M A</td>
            <td>: {{ $slip->bruto?->user->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">GAJI BRUTO</div>
    <table class="main-table">
        <tr>
            <td colspan="3" style="font-weight: bold;">TUNJANGAN TETAP</td>
        </tr>
        <tr>
            <td class="label indent">1 Gaji Pokok</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($slip->bruto?->nom_gapok ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label indent">2 Tunjangan Fungsional</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($slip->bruto?->nom_fungsi ?? 0, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td colspan="3" style="font-weight: bold; padding-top: 5px;">TUNJANGAN TIDAK TETAP</td>
        </tr>
        <tr>
            <td class="label indent">1 Tunjangan Jabatan</td>
            <td class="rp">Rp</td>
            <td class="val">
                {{ $slip->bruto?->nom_jabatan ? number_format($slip->bruto->nom_jabatan, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">2 Tunjangan Fungsional Tambahan</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $slip->bruto?->nom_umum ? number_format($slip->bruto->nom_umum, 0, ',', '.') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label indent">3 Tunjangan Poskes</td>
            <td class="rp">Rp</td>
            <td class="val">
                {{ $slip->bruto?->nom_poskes ? number_format($slip->bruto->nom_poskes, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">4 Tunjangan Lainnya</td>
            <td class="rp">Rp</td>
            <td class="val">
                {{ $slip->bruto?->nom_lainnya ? number_format($slip->bruto->nom_lainnya, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">5 Lembur</td>
            <td class="rp">Rp</td>
            <td class="val">
                {{ $slip->bruto?->nom_lembur ? number_format($slip->bruto->nom_lembur, 0, ',', '.') : '-' }}</td>
        </tr>

        <tr>
            <td class="label indent">6 Level Jabatan</td>
            <td class="rp"></td>
            <td class="val center-val">{{ $slip->bruto?->level_jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">7 Pendapatan RS</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($slip->bruto?->nom_pendapatan_rs ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label indent">8 Prosentase Tukin</td>
            <td class="rp"></td>
            <td class="val center-val">{{ number_format($slip->bruto?->prosentase_tukin ?? 0, 4, ',', '.') }}%</td>
        </tr>
        <tr>
            <td class="label indent">9 KPI</td>
            <td class="rp"></td>
            <td class="val center-val">{{ number_format($slip->bruto?->KPI ?? 0, 1, ',', '.') }}%</td>
        </tr>
        <tr>
            <td class="label indent">10 Tukin Diterima</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($slip->bruto?->nom_tukin_diterima ?? 0, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td class="total-label" style="padding-top: 10px;">TOTAL GAJI BRUTO</td>
            <td class="rp" style="border-top: 1px solid #000; padding-top: 10px;">Rp</td>
            <td class="total-val" style="padding-top: 10px;">
                {{ number_format($slip->bruto?->total_bruto ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">POTONGAN-POTONGAN :</div>
    <table class="main-table">
        @php $no = 1; @endphp
        @php
            $urutanManual = [
                'simpanan-wajib',
                'simpanan-pokok',
                'ibi',
                'idi',
                'ppni',
                'pinjaman-koperasi',
                'obat',
                'angsuran-bank',
                'angsuran-perum',
                'dansos-karyawan',
                'dplk',
                'bpjs-tenaga-kerja',
                'bpjs-kesehatan',
                'rekonsiliasi-bpjs-kesehatan',
                'bpjs-kesehatan-ortutambahan',
                'pph21',
                'kurangan-pph-21-tahun-2024',
                'amaliah-romadhon',
                'rawat-inap',
                'potongan-selisih',
                'iuran-pekarsi',
                'lain-lain',
            ];

            // Mengurutkan koleksi potongan berdasarkan slug yang ada di master_potongan
            $slip->bruto->potongan = $slip->bruto->potongan
                ->sortBy(function ($p) use ($urutanManual) {
                    // Ambil slug dari relasi masterPotongan
                    $slug = $p->masterPotongan->slug ?? '';
                    $posisi = array_search($slug, $urutanManual);

                    return $posisi === false ? 999 : $posisi;
                })
                ->values();
        @endphp
        @forelse ($slip->bruto?->potongan ?? [] as $p)
            <tr>
                <td class="label">{{ $no++ }} {{ $p->masterPotongan->nama }}</td>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($p->nominal ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="indent">Tidak ada potongan.</td>
            </tr>
        @endforelse

        <tr>
            <td class="total-label">Jumlah Potongan</td>
            <td class="rp" style="border-top: 1px solid #000;">Rp</td>
            <td class="total-val">{{ number_format($slip->bruto?->potongan->sum('nominal') ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr class="netto-row">
            <td style="text-align: right; width: 70%; padding-right: 15px;">GAJI NETTO</td>
            <td style="width: 30px;">Rp</td>
            <td class="netto-border" style="text-align: right; width: 120px;">
                {{ number_format($slip->total_netto ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="signature-area">
        <p>Banjarnegara, {{ \Carbon\Carbon::parse($slip->tanggal_transfer)->translatedFormat('d F Y') }}</p>
        <p>Bendahara,</p>
        <br><br><br>
        <p><strong>Nur Chalifah</strong></p>
    </div>
    <div class="clear"></div>
</body>

</html>
