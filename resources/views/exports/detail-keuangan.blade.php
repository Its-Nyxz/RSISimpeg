<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Slip Gaji - {{ $user->name }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; line-height: 1.3; color: #000; margin: 20px; }
        .header { text-align: center; font-weight: bold; font-size: 12px; margin-bottom: 2px; }
        .subheader { text-align: center; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; }
        
        .info-table { width: 100%; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .info-table td { border: none; padding: 2px; }

        .main-table { width: 100%; border-collapse: collapse; }
        .main-table td { border: none; padding: 3px; }
        
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-decoration: underline; }
        .label { width: 250px; }
        .indent { padding-left: 20px !important; }
        .rp { width: 30px; text-align: left; }
        .val { width: 120px; text-align: right; border-bottom: 1px dotted #000; }
        .center-val { text-align: center; }
        
        .total-label { font-weight: bold; text-align: right; padding-right: 15px; }
        .total-val { font-weight: bold; text-align: right; border-bottom: 2px solid #000; }
        
        .netto-row td { font-weight: bold; font-size: 13px; padding-top: 20px; }
        .netto-border { border-bottom: 3px double #000; }
        
        .signature-area { float: right; width: 200px; margin-top: 30px; text-align: center; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">DETAIL SLIP GAJI KARYAWAN {{ strtoupper($user->jenis?->nama ?? '') }}</div>
    <div class="subheader">BULAN {{ strtoupper(\Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F')) }} {{ $tahun }}</div>

    <table class="info-table">
        <tr><td style="width: 70px;">NIP</td><td>: {{ $user->nip ?? '-' }}</td></tr>
        <tr><td>N A M A</td><td>: {{ $user->name }}</td></tr>
    </table>

    <div class="section-title">GAJI BRUTO</div>
    <table class="main-table">
        <tr><td colspan="3" style="font-weight: bold;">TUNJANGAN TETAP</td></tr>
        <tr>
            <td class="label indent">1 Gaji Pokok</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($gajiBruto->nom_gapok ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label indent">2 Tunjangan Fungsional</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($gajiBruto->nom_fungsi ?? 0, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="3" style="font-weight: bold; padding-top: 10px;">TUNJANGAN TIDAK TETAP</td></tr>
        <tr>
            <td class="label indent">1 Tunjangan Jabatan</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $gajiBruto->nom_jabatan ? number_format($gajiBruto->nom_jabatan, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">2 Tunjangan Umum</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $gajiBruto->nom_umum ? number_format($gajiBruto->nom_umum, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">3 Tunjangan Transport</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $gajiBruto->nom_transport ? number_format($gajiBruto->nom_transport, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">4 Tunjangan Makan</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $gajiBruto->nom_makan ? number_format($gajiBruto->nom_makan, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">5 Tunjangan Khusus</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $gajiBruto->nom_khusus ? number_format($gajiBruto->nom_khusus, 0, ',', '.') : '-' }}</td>
        </tr>
        <tr>
            <td class="label indent">6 Tunjangan Kinerja/Lainnya</td>
            <td class="rp">Rp</td>
            <td class="val">{{ $gajiBruto->nom_lainnya ? number_format($gajiBruto->nom_lainnya, 0, ',', '.') : '-' }}</td>
        </tr>
        
        @if(isset($gajiBruto->nom_lembur))
        <tr>
            <td class="label indent">7 Lembur</td>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($gajiBruto->nom_lembur, 0, ',', '.') }}</td>
        </tr>
        @endif

        <tr>
            <td class="total-label" style="padding-top: 10px;">TOTAL GAJI BRUTO</td>
            <td class="rp" style="border-top: 1px solid #000; padding-top: 10px;">Rp</td>
            <td class="total-val" style="padding-top: 10px;">{{ number_format($gajiBruto->total_bruto ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">POTONGAN-POTONGAN :</div>
    <table class="main-table">
        @php $no = 1; @endphp
        @forelse ($potonganList as $item)
            <tr>
                <td class="label indent">{{ $no++ }} {{ $item->nama }}</td>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($item->nominal ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="indent">Tidak ada potongan.</td></tr>
        @endforelse
        
        <tr>
            <td class="total-label" style="padding-top: 5px;">Jumlah Potongan</td>
            <td class="rp" style="border-top: 1px solid #000;">Rp</td>
            <td class="total-val">{{ number_format($totalPotongan ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr class="netto-row">
            <td style="text-align: right; width: 70%; padding-right: 15px;">GAJI NETTO</td>
            <td style="width: 30px;">Rp</td>
            <td class="netto-border" style="text-align: right; width: 120px;">{{ number_format($netto ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="signature-area">
        <p>Banjarnegara, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Bendahara,</p>
        <br><br><br>
        <p><strong>Nur Chalifah</strong></p>
    </div>
    <div class="clear"></div>
</body>
</html>