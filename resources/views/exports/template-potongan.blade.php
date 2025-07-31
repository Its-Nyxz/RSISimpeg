<table border="1" cellspacing="0" cellpadding="5">
    <thead style="background-color: #f0f0f0; font-weight: bold;">
        <tr>
            <th>Slug</th>
            <th>Nama Lengkap</th>
            <th>Unit Kerja</th>
            <th>Jenis Karyawan</th>
            <th>Gaji Pokok</th>
            <th>Tunjangan Jabatan</th>
            <th>Tunjangan Fungsional</th>
            <th>Tunjangan Umum</th>
            <th>Tunjangan Makan</th>
            <th>Tunjangan Transport</th>
            <th>Tunjangan Khusus</th>
            <th>Tunjangan Kinerja</th>
            <th>Total Gaji Bruto</th>
            @foreach ($masterPotongans as $potongan)
                <th>{{ ucwords(str_replace('-', ' ', $potongan->nama)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->slug }}</td> <!-- Export slug -->
                <td>{{ $user->nama_bersih }}</td>
                <td>{{ $user->unitKerja->nama ?? '-' }}</td>
                <td>{{ ucfirst($user->jenis->nama ?? '-') }}</td>
                <td>{{ (int) $user->nom_gapok }}</td>
                <td>{{ (int) $user->nom_jabatan }}</td>
                <td>{{ (int) $user->nom_fungsi }}</td>
                <td>{{ (int) $user->nom_umum }}</td>
                <td>{{ (int) $user->nom_makan }}</td>
                <td>{{ (int) $user->nom_transport }}</td>
                <td>{{ (int) $user->nom_khusus }}</td>
                <td>{{ (int) $user->nom_lainnya }}</td>
                <td>{{ (int) $user->total_bruto }}</td>
                @foreach ($masterPotongans as $potongan)
                    @php
                        $nom = $user->potonganOtomasis[$potongan->nama] ?? '';
                    @endphp
                    <td style="mso-number-format:'\@';">
                        {{ (int) $nom }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
