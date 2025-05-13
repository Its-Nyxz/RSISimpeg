<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Slug</th>
            <th>Nama</th>
            <th>Unit Kerja</th>
            <th>Jenis Karyawan</th>
            <th>Gaji Pokok</th>
            <th>Tunj. Jabatan</th>
            <th>Tunj. Fungsi</th>
            <th>Tunj. Umum</th>
            <th>Tunj. Khusus</th>
            <th>Tunj. Makan</th>
            <th>Tunj. Transport</th>
            <th>Total Bruto</th>
            @foreach ($masterPotongans as $potongan)
                <th>{{ $potongan->nama }}</th>
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
                <td>{{ (int) $user->nom_khusus }}</td>
                <td>{{ (int) $user->nom_makan }}</td>
                <td>{{ (int) $user->nom_transport }}</td>
                <td>{{ (int) $user->total_bruto }}</td>
                @foreach ($masterPotongans as $potongan)
                    @php
                        $nom = $user->potonganOtomasis[$potongan->nama] ?? '';
                    @endphp
                    <td style="mso-number-format:'\@';">
                        {{ is_numeric($nom) ? (int) $nom : '' }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
