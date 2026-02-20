<table>
    <thead>
        <tr>
            <th colspan="{{ 14 + $masterPotongans->count() }}" style="text-align: center; font-weight: bold; font-size: 14pt;">
                DAFTAR URUTAN GAJI KARYAWAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ 14 + $masterPotongans->count() }}" style="text-align: center; font-weight: bold; font-size: 11pt;">
                Periode: {{ $bulan }} / {{ $tahun }}
            </th>
        </tr>
        <tr></tr> <tr>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="5">No</th>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="15">Slug</th>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="35">Nama Lengkap</th>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="25">Unit Kerja</th>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="15">Status</th>
            
            <th colspan="9" style="background-color: #1f4e78; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">PENERIMAAN (BRUTO)</th>
            
            <th colspan="{{ $masterPotongans->count() }}" style="background-color: #843c0c; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">POTONGAN</th>
        </tr>
        <tr>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Gapok</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Jabatan</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Fungsi</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Umum</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Makan</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Transp</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Khusus</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Lainnya</th>
            <th style="background-color: #215967; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">TOTAL</th>

            @foreach ($masterPotongans as $potongan)
                <th style="background-color: #c00000; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                    {{ str_replace('Bpjs', 'BPJS', ucwords(str_replace('-', ' ', $potongan->nama))) }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $index => $user)
            <tr>
                <td style="border: 1px solid #000000; text-align: center; vertical-align: center;">{{ $user->urutanKeuangan->urutan ?? ($index + 1) }}</td>
                <td style="border: 1px solid #000000; vertical-align: center; color: #666666;">{{ $user->slug }}</td>
                <td style="border: 1px solid #000000; vertical-align: center; font-weight: bold;">{{ $user->nama_bersih }}</td>
                <td style="border: 1px solid #000000; vertical-align: center;">{{ $user->unitKerja->nama ?? '-' }}</td>
                <td style="border: 1px solid #000000; vertical-align: center; text-align: center;">{{ ucfirst($user->jenis->nama ?? '-') }}</td>
                
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_gapok, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_jabatan, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_fungsi, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_umum, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_makan, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_transport, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_khusus, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">{{ number_format($user->nom_lainnya, 0, ',', ',') }}</td>
                <td style="border: 1px solid #000000; text-align: right; vertical-align: center; background-color: #fff2cc; font-weight: bold;">
                    {{ number_format($user->total_bruto, 0, ',', ',') }}
                </td>

                @foreach ($masterPotongans as $potongan)
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: center;">
                        {{ number_format(($user->potonganOtomasis[$potongan->nama] ?? 0), 0, ',', ',') }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<table>
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center;">Mengetahui,</td>
        <td colspan="{{ 5 + $masterPotongans->count() }}"></td>
        <td colspan="3" style="text-align: center;">Banjarnegara, {{ date('d F Y') }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center; font-weight: bold;">Direktur RSI Banjarnegara</td>
        <td colspan="{{ 5 + $masterPotongans->count() }}"></td>
        <td colspan="3" style="text-align: center; font-weight: bold;">Bendahara,</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center; font-weight: bold; text-decoration: underline;">( .................................... )</td>
        <td colspan="{{ 5 + $masterPotongans->count() }}"></td>
        <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline;">( .................................... )</td>
    </tr>
</table>