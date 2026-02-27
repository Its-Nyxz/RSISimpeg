<table>
    <thead>
        <tr>
            <th colspan="{{ 19 + $masterPotongans->count() }}"
                style="text-align: center; font-weight: bold; font-size: 14pt;">
                DAFTAR URUTAN GAJI KARYAWAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ 19 + $masterPotongans->count() }}"
                style="text-align: center; font-weight: bold; font-size: 11pt;">
                Periode: {{ $bulan }} / {{ $tahun }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;"
                width="5">No</th>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;"
                width="15">Slug</th>

            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;"
                width="35">Nama Lengkap</th>
            <th rowspan="2"
                style="background-color: #333333; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;"
                width="25">Unit Kerja</th>

            <th colspan="2"
                style="background-color: #1f4e78; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">
                TUNJANGAN TETAP</th>

            <th colspan="10"
                style="background-color: #375623; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">
                TUNJANGAN TIDAK TETAP</th>

            <th rowspan="2"
                style="background-color: #215967; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">
                TOTAL BRUTO</th>

            <th colspan="{{ $masterPotongans->count() }}"
                style="background-color: #843c0c; color: #ffffff; font-weight: bold; border: 2px solid #000000; text-align: center;">
                POTONGAN</th>
        </tr>
        <tr>
            <th
                style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Gaji Pokok</th>
            <th
                style="background-color: #2e75b6; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Tj. Fungsional</th>

            <th
                style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Tj. Jabatan</th>
            <th
                style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Tj. Fung Tambahan</th>
            <th
                style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Tj. Poskes</th>
            <th
                style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Tj. Lainnya</th>
            <th
                style="background-color: #548235; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Lembur</th>
            <th
                style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Level</th>
            <th
                style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Pendapatan RS</th>
            <th
                style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                % Tukin</th>
            <th
                style="background-color: #76933c; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                KPI</th>
            <th
                style="background-color: #e2efda; color: #000000; font-weight: bold; border: 1px solid #000000; text-align: center;">
                Tukin Diterima</th>

            @foreach ($masterPotongans as $potongan)
                <th
                    style="background-color: #c00000; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">
                    {{ str_replace('Bpjs', 'BPJS', ucwords(str_replace('-', ' ', $potongan->nama))) }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $index => $user)
            @php
                /**
                 * HITUNG POSISI BARIS EXCEL:
                 * 1. Judul (Baris 1)
                 * 2. Periode (Baris 2)
                 * 3. Baris Kosong (Baris 3)
                 * 4. Header Baris 1 (Baris 4)
                 * 5. Header Baris 2 (Baris 5)
                 * Maka data pertama ($index = 0) ada di Baris 6.
                 */
                $currentRow = $index + 6;
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $user->slug }}</td>

                <td style="border: 1px solid #000000; font-weight: bold;">{{ $user->nama_bersih }}</td>
                <td style="border: 1px solid #000000;">{{ $user->unitKerja->nama ?? '-' }}</td>

                {{-- Nominal - Kirim angka polos --}}
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_gapok }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_fungsi }}</td>

                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_jabatan }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_umum ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_poskes ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_lainnya }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_lembur ?? 0 }}</td>

                <td style="border: 1px solid #000000; text-align: center;">{{ $user->level_jabatan ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $user->nom_pendapatan_rs ?? 0 }}</td>

                {{-- Persentase - Kirim angka dengan desimal titik agar valid secara sistem --}}
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ $user->prosentase_tukin }}
                </td>

                <td style="border: 1px solid #000000; text-align: center;">
                    {{ $user->KPI }}
                </td>

                <td style="border: 1px solid #000000; text-align: right; background-color: #e2efda; font-weight: bold;">
                    {{ $user->nom_tukin_diterima ?? 0 }}
                </td>

                <td style="border: 1px solid #000000; text-align: right; background-color: #fff2cc; font-weight: bold;">
                   =SUM(E{{ $currentRow }}:K{{ $currentRow }},P{{ $currentRow }})
                </td>

                @foreach ($masterPotongans as $potongan)
                    <td style="border: 1px solid #000000; text-align: right;">
                        {{ $user->potonganOtomasis[$potongan->nama] ?? 0 }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
