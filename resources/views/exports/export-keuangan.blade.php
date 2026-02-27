<table>
    <thead>
        {{-- Header Judul --}}
        <tr>
            <th colspan="{{ 18 + $masterPotongans->count() }}"
                style="text-align: center; font-weight: bold; font-size: 14pt;">
                DAFTAR URUTAN GAJI KARYAWAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ 18 + $masterPotongans->count() }}"
                style="text-align: center; font-weight: bold; font-size: 11pt;">
                Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
            </th>
        </tr>
        <tr></tr>

        {{-- Header Utama --}}
        <tr>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="5">No</th>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="35">Nama Lengkap</th>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="25">Unit Kerja</th>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;"
                width="15">Jenis</th>

            <th colspan="2"
                style="background-color: #1f4e78; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">
                TUNJANGAN TETAP</th>

            <th colspan="10"
                style="background-color: #375623; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">
                TUNJANGAN TIDAK TETAP</th>

            <th rowspan="2"
                style="background-color: #215967; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;">
                TOTAL BRUTO</th>

            <th colspan="{{ $masterPotongans->count() + 1 }}"
                style="background-color: #843c0c; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">
                POTONGAN</th>

            <th rowspan="2"
                style="background-color: #000000; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;">
                NETTO</th>
        </tr>

        {{-- Header Detail Kolom --}}
        <tr>
            {{-- Tetap --}}
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Gapok</th>
            <th style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Fungsi</th>

            {{-- Tidak Tetap --}}
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Jabatan</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Fung Tambahan</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Poskes</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Lainnya</th>
            <th style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Lembur</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Level</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">Pendapatan RS</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">% Tukin</th>
            <th style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">KPI</th>
            <th style="background-color: #e2efda; color: #000000; font-weight: bold; border: 1px solid #000000; text-align: center;">Tukin Diterima</th>

            {{-- Potongan --}}
            @foreach ($masterPotongans as $pot)
                <th style="background-color: #c00000; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                    {{ str_replace('Bpjs', 'BPJS', ucwords(str_replace('-', ' ', $pot->nama))) }}
                </th>
            @endforeach
            <th style="background-color: #f2f2f2; color: #000000; font-weight: bold; border: 1px solid #000000; text-align: center;">TOTAL POT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $index => $user)
            @php 
                $p = $user->potongan_rinci ?? []; 
                // Hitung baris untuk formula excel jika diekspor (Header 5 baris, data mulai baris 6)
                $currentRow = $index + 6; 
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; font-weight: bold;">{{ $user->nama_bersih ?? $user->name }}</td>
                <td style="border: 1px solid #000000;">{{ $user->unitKerja->nama ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->jenis->nama ?? '-' }}</td>

                {{-- Tunjangan Tetap --}}
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_gapok }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_fungsi }}</td>

                {{-- Tunjangan Tidak Tetap --}}
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_jabatan }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_umum ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_poskes ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_lainnya }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_lembur ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->level_jabatan ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_pendapatan_rs ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->prosentase_tukin }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $user->KPI }}</td>
                <td style="border: 1px solid #000000; text-align: right; background-color: #e2efda;">{{ $user->nom_tukin_diterima ?? 0 }}</td>

                {{-- Total Bruto --}}
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #fff2cc;">
                    {{ $user->total_bruto }}
                </td>

                {{-- Potongan Dinamis --}}
                @foreach ($masterPotongans as $pot)
                    <td style="border: 1px solid #000000; text-align: right;">
                        {{ $p[$pot->nama] ?? 0 }}
                    </td>
                @endforeach
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #f2f2f2;">
                    {{ $user->total_potongan }}
                </td>

                {{-- Netto --}}
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
                    {{ $user->netto }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Bagian Tanda Tangan --}}
<table style="margin-top: 30px;">
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="3" style="text-align: center;">Mengetahui,</td>
        <td colspan="{{ 6 + $masterPotongans->count() }}"></td>
        <td colspan="4" style="text-align: center;">Banjarnegara, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3" style="text-align: center; font-weight: bold;">Direktur RSI Banjarnegara</td>
        <td colspan="{{ 6 + $masterPotongans->count() }}"></td>
        <td colspan="4" style="text-align: center; font-weight: bold;">Bendahara,</td>
    </tr>
    <tr><td colspan="{{ 18 + $masterPotongans->count() }}"></td></tr>
    <tr><td colspan="{{ 18 + $masterPotongans->count() }}"></td></tr>
    <tr>
        <td></td>
        <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline;">
            ( .................................... )
        </td>
        <td colspan="{{ 6 + $masterPotongans->count() }}"></td>
        <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">
            Nur Chalifah
        </td>
    </tr>
</table>