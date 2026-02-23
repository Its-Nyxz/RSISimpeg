<table>
    <thead>
        {{-- Header Judul --}}
        <tr>
            <th colspan="{{ 14 + $masterPotongans->count() }}"
                style="text-align: center; font-weight: bold; font-size: 14pt;">
                DAFTAR URUTAN GAJI KARYAWAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ 14 + $masterPotongans->count() }}"
                style="text-align: center; font-weight: bold; font-size: 11pt;">
                Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
            </th>
        </tr>
        <tr></tr>

        {{-- Header Utama --}}
        <tr>
            <th rowspan="2"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="5">No</th>
            <th rowspan="2"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="35">Nama Lengkap</th>
            <th rowspan="2"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="25">Unit Kerja</th>
            <th rowspan="2"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="15">Jenis</th>

            <th colspan="9"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center;">
                PENERIMAAN (BRUTO)</th>

            <th colspan="{{ $masterPotongans->count() + 1 }}"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center;">
                POTONGAN</th>

            <th rowspan="2"
                style="background-color: #ffffff; color: #000000; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;">
                NETTO</th>
        </tr>

        {{-- Header Detail Kolom --}}
        <tr>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Gapok</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Jabatan</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Fungsi</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Umum</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Makan</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Transp</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Khusus</th>
            <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Lainnya</th>
            <th style="background-color: #f2f2f2; font-weight: bold; border: 1px solid #000000; text-align: center;">TOTAL BRUTO</th>

            @foreach ($masterPotongans as $pot)
                <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                    {{ str_replace('Bpjs', 'BPJS', ucwords(str_replace('-', ' ', $pot->nama))) }}
                </th>
            @endforeach
            <th style="background-color: #f2f2f2; font-weight: bold; border: 1px solid #000000; text-align: center;">TOTAL POT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $index => $user)
            @php $p = $user->potongan_rinci ?? []; @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; font-weight: bold;">{{ $user->nama_bersih ?? $user->name }}</td>
                <td style="border: 1px solid #000000;">{{ $user->unitKerja->nama ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->jenis->nama ?? '-' }}</td>

                {{-- Penerimaan Bruto - Format Ribuan Koma --}}
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_gapok, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_jabatan, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_fungsi, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_umum, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_makan, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_transport, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_khusus, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format((int)$user->nom_lainnya, 0, '.', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #f2f2f2;">
                    {{ number_format((int)$user->total_bruto, 0, '.', ',') }}
                </td>

                {{-- Potongan Dinamis - Format Ribuan Koma --}}
                @foreach ($masterPotongans as $pot)
                    <td style="border: 1px solid #000000; text-align: right;">
                        {{ number_format((int)($p[$pot->nama] ?? 0), 0, '.', ',') }}
                    </td>
                @endforeach
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #f2f2f2;">
                    {{ number_format((int)$user->total_potongan, 0, '.', ',') }}
                </td>

                {{-- Netto - Format Ribuan Koma --}}
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
                    {{ number_format((int)$user->netto, 0, '.', ',') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Tanda Tangan --}}
<table style="margin-top: 30px;">
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="3" style="text-align: center;">Mengetahui,</td>
        <td colspan="{{ 6 + $masterPotongans->count() }}"></td>
        <td colspan="3" style="text-align: center;">Banjarnegara, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3" style="text-align: center; font-weight: bold;">Direktur RSI Banjarnegara</td>
        <td colspan="{{ 6 + $masterPotongans->count() }}"></td>
        <td colspan="3" style="text-align: center; font-weight: bold;">Bendahara,</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline;">
            ( .................................... )
        </td>
        <td colspan="{{ 6 + $masterPotongans->count() }}"></td>
        <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline;">
            ( .................................... )
        </td>
    </tr>
</table>