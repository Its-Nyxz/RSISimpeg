<table>
    <thead>
        <!--Header Judul-->
        <tr>
            <th colspan="14" style="text-align: center; font-weight: bold; font-size: 14pt;">
                @if ($selected && $selected !== 'none')
                    RIWAYAT IZIN {{ strtoupper($selected) }}
                @else
                    RIWAYAT IZIN KARYAWAN
                @endif
            </th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; font-weight: bold; font-size: 11pt;">
                @if($bulan && $tahun)
                    Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
                @else
                    Semua Periode
                @endif
            </th>
        </tr>
        <!--Super Header-->
        <tr>
            <th colspan="3" style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;">Informasi Karyawan</th>
            <th colspan="8" style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;">Detail Pengajuan</th>
            {{-- <th colspan="5" style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;">Riwayat Approval</th> --}}
        </tr>
        <!--Header Tabel-->
        <tr>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">No</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Nama Karyawan</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Unit Kerja</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Jenis Izin</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal Pengajuan</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal Mulai Izin</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal Selesai Izin</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Lama Izin</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Keterangan</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Status</th>
            {{-- <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Approval (1)</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tgl (1)</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Approval (2)</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tgl (2)</th> --}}
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Foto Surat Bukti</th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        @forelse ($izinKaryawans as $index => $izin)
            <tr>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->user->name ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->user->unitKerja->nama ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->jenisIzin->nama_izin ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->created_at ? \Carbon\Carbon::parse($izin->created_at)->locale('id')->translatedFormat('l, d F') : '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->tanggal_mulai ? \Carbon\Carbon::parse($izin->tanggal_mulai)->locale('id')->translatedFormat('l, d F') : '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->tanggal_selesai ? \Carbon\Carbon::parse($izin->tanggal_selesai)->locale('id')->translatedFormat('l, d F') : '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->jumlah_hari . ' hari' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $izin->keterangan ?? '-' }}</td>
                @php
                    $statusStyle = '';
                    if (Str::slug($izin->statusIzin->nama_status) === 'disetujui') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #4CAF50; color: #ffffff; font-weight: bold;';
                    } elseif (Str::slug($izin->statusIzin->nama_status) === 'ditolak') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #ff3838; color: #ffffff; font-weight: bold;';
                    } elseif (Str::slug($izin->statusIzin->nama_status) === 'menunggu') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #ffe600; color: #ffffff; font-weight: bold;';
                    } elseif (Str::slug($izin->statusIzin->nama_status) === 'menunggu-kepegawaian') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #4c99ff; color: #ffffff; font-weight: bold;';
                    } else {
                        $statusStyle = 'border: 1px solid #000000; text-align: center;';
                    };
                @endphp

                <td rowspan="2" style="{{ $statusStyle }}">{{ $izin->statusIzin->nama_status ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ asset('storage/photos/bukti-izin/' . $izin->bukti_izin) }}</td>
            </tr>
            <tr>
                {{--  --}}
            </tr>
        @empty
            <tr>
                <td rowspan="2" colspan="14" style="border: 1px solid #000000; text-align: center; padding: 20px;">
                    Tidak ada data riwayat izin
                </td>
            </tr>
        @endforelse
    </tbody>
</table>