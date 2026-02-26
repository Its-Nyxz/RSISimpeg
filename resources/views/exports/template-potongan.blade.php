<table>
    <thead>
        <tr>
            <th colspan="{{ 19 + $masterPotongans->count() }}" style="text-align: center; font-weight: bold; font-size: 14pt;">
                DAFTAR URUTAN GAJI KARYAWAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ 19 + $masterPotongans->count() }}" style="text-align: center; font-weight: bold; font-size: 11pt;">
                Periode: {{ $bulan }} / {{ $tahun }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="5">No</th>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="15">Slug</th>
            
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="35">Nama Lengkap</th>
            <th rowspan="2" style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;" width="25">Unit Kerja</th>
            
            <th colspan="2" style="background-color: #1f4e78; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">TUNJANGAN TETAP</th>
            
            <th colspan="10" style="background-color: #375623; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">TUNJANGAN TIDAK TETAP</th>
            
            <th rowspan="2" style="background-color: #215967; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">TOTAL BRUTO</th>

            <th colspan="{{ $masterPotongans->count() }}" style="background-color: #843c0c; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">POTONGAN</th>
        </tr>
        <tr>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Gaji Pokok</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Tj. Fungsional</th>
            
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Tj. Jabatan</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Tj. Fung Tambahan</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Tj. Poskes</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Tj. Lainnya</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Lembur</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Level</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Pendapatan RS</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">% Tukin</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">KPI</th>
            <th style="background-color: #e2efda; color: #000000; font-weight: bold; border: 1px solid #000000; text-align: center;">Tukin Diterima</th>

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
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $user->slug }}</td>

                <td style="border: 1px solid #000000; font-weight: bold;">{{ $user->nama_bersih }}</td>
                <td style="border: 1px solid #000000;">{{ $user->unitKerja->nama ?? '-' }}</td>
                
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_gapok, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_fungsi, 0, ',', '.') }}</td>
                
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_jabatan, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_umum ?? 0, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_poskes ?? 0, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_lainnya, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_lembur ?? 0, 0, ',', '.') }}</td>
                
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->level_jabatan ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($user->nom_pendapatan_rs ?? 0, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->prosentase_tukin ?? 0 }}%</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->KPI ?? 0 }}%</td>
                <td style="border: 1px solid #000000; text-align: right; background-color: #e2efda; font-weight: bold;">{{ number_format($user->nom_tukin_diterima ?? 0, 0, ',', '.') }}</td>

                <td style="border: 1px solid #000000; text-align: right; background-color: #fff2cc; font-weight: bold;">
                    {{ number_format($user->total_bruto, 0, ',', '.') }}
                </td>

                @foreach ($masterPotongans as $potongan)
                    <td style="border: 1px solid #000000; text-align: right;">
                        {{ number_format(($user->potonganOtomasis[$potongan->nama] ?? 0), 0, ',', '.') }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>