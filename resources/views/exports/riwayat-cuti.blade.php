<table>
    <thead>
        <!--Header Judul-->
        <tr>
            <th colspan="14" style="text-align: center; font-weight: bold; font-size: 14pt;">
                @if ($selected && $selected !== 'none')
                    RIWAYAT CUTI {{ strtoupper($selected) }}
                @else
                    RIWAYAT CUTI KARYAWAN
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
            <th style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;"></th>
            <th colspan="2" style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;">Informasi Karyawan</th>
            <th colspan="6" style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;">Detail Pengajuan</th>
            <th colspan="5" style="background-color: #4c99ff; border: 1px solid #000000; text-align: center; font-weight: bold;">Riwayat Approval</th>
        </tr>
        <!--Header Tabel-->
        <tr>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">No</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Nama Karyawan</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Unit Kerja</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Jenis Cuti</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal Pengajuan</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal Mulai Cuti</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tanggal Selesai Cuti</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Lama Cuti</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Keterangan</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Status</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Approval (1)</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tgl (1)</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Approval (2)</th>
            <th rowspan="2" style="border: 1px solid #000000; text-align: center; font-weight: bold;">Tgl (2)</th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        @forelse ($cutiKaryawans as $index => $cuti)
            <tr>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->user->name ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->user->unitKerja->nama ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->jenisCuti->nama_cuti ?? '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->created_at ? \Carbon\Carbon::parse($cuti->created_at)->locale('id')->translatedFormat('l, d F') : '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->tanggal_mulai ? \Carbon\Carbon::parse($cuti->tanggal_mulai)->locale('id')->translatedFormat('l, d F') : '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->tanggal_selesai ? \Carbon\Carbon::parse($cuti->tanggal_selesai)->locale('id')->translatedFormat('l, d F') : '-' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->jumlah_hari . ' hari' }}</td>
                <td rowspan="2" style="border: 1px solid #000000; text-align: center;">{{ $cuti->keterangan ?? '-' }}</td>
                @php
                    $statusStyle = '';
                    if (Str::slug($cuti->statusCuti->nama_status) === 'disetujui') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #4CAF50; color: #ffffff; font-weight: bold;';
                    } elseif (Str::slug($cuti->statusCuti->nama_status) === 'ditolak') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #ff3838; color: #ffffff; font-weight: bold;';
                    } elseif (Str::slug($cuti->statusCuti->nama_status) === 'menunggu') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #ffe600; color: #ffffff; font-weight: bold;';
                    } elseif (Str::slug($cuti->statusCuti->nama_status) === 'menunggu-kepegawaian') {
                        $statusStyle = 'border: 1px solid #000000; text-align: center; background-color: #4c99ff; color: #ffffff; font-weight: bold;';
                    } else {
                        $statusStyle = 'border: 1px solid #000000; text-align: center;';
                    };
                @endphp

                <td rowspan="2" style="{{ $statusStyle }}">{{ $cuti->statusCuti->nama_status ?? '-' }}</td>

                @php
                    $approvals = \App\Models\RiwayatApproval::with('approver')->where('cuti_id', $cuti->id)->orderBy('created_at', 'asc')->get();
                @endphp
                @if($approvals->isNotEmpty())
                    @if ($approvals->count() > 1)
                        <td style="color: #1e40af; font-weight: bold; border: 1px solid #000000; text-align: left; vertical-align: center;">
                            <span>{{ $approvals[0]->approver->name ?? 'Unknown' }}</span> ({{ $approvals[0]->approver->roles->pluck('name')->first() ?? '-' }})
                        </td>
                        <td rowspan="2" style="border: 1px solid #000000; text-align: center;">
                            {{ \Carbon\Carbon::parse($approvals[0]->approve_at)->locale('id')->translatedFormat('l, d F') }}
                        </td>
                        <td style="color: #1e40af; font-weight: bold; border: 1px solid #000000; text-align: left; vertical-align: center;">
                            <span>{{ $approvals[1]->approver->name ?? 'Unknown' }}</span> ({{ $approvals[1]->approver->roles->pluck('name')->first() ?? '-' }})
                        </td>
                        <td rowspan="2" style="border: 1px solid #000000; text-align: center;">
                            {{ \Carbon\Carbon::parse($approvals[1]->approve_at)->locale('id')->translatedFormat('l, d F') }}
                        </td>
                    @elseif ($approvals->count() === 1)
                        <td style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: center;">
                            -
                        </td>
                        <td rowspan="2" style="border: 1px solid #000000; text-align: center;">
                            -
                        </td>
                        <td style="color: #1e40af; font-weight: bold; border: 1px solid #000000; text-align: left; vertical-align: center;">
                            <span>{{ $approvals[0]->approver->name ?? 'Unknown' }}</span> ({{ $approvals[0]->approver->roles->pluck('name')->first() ?? '-' }})
                        </td>
                        <td rowspan="2" style="border: 1px solid #000000; text-align: center;">
                            {{ \Carbon\Carbon::parse($approvals[0]->approve_at)->locale('id')->translatedFormat('l, d F') }}
                        </td>
                    @endif
                @else
                    <td rowspan="2" colspan="4" style="border: 1px solid #000000; text-align: center; font-style: italic;">
                        <i>Belum ada riwayat approval.</i>
                    </td>
                @endif
            </tr>
            <tr>
                @if($approvals->count() > 1)
                    <td style="color: {{ str_contains($approvals[0]->status_approval, 'ditolak') ? '#dc2626' : '#16a34a' }}; border: 1px solid #000000; text-align: left; vertical-align: center;">
                        <span>{{ strtoupper(str_replace('_', ' ', $approvals[0]->status_approval)) }}</span>
                        @if($approvals[0]->catatan)<br><span style="color: #000000; font-weight: normal;">{{ $approvals[0]->catatan }}</span>@endif
                    </td>
                    <td style="color: {{ str_contains($approvals[1]->status_approval, 'ditolak') ? '#dc2626' : '#16a34a' }}; border: 1px solid #000000; text-align: left; vertical-align: center;">
                        <span>{{ strtoupper(str_replace('_', ' ', $approvals[1]->status_approval)) }}</span>
                        @if($approvals[1]->catatan)<br><span style="color: #000000; font-weight: normal;">Catatan: {{ $approvals[1]->catatan }}</span>@endif
                    </td>
                @elseif($approvals->count() === 1)
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: center;">
                        -
                    </td>
                    <td style="color: {{ str_contains($approvals[0]->status_approval, 'ditolak') ? '#dc2626' : '#16a34a' }}; border: 1px solid #000000; text-align: left; vertical-align: center;">
                        <span>{{ strtoupper(str_replace('_', ' ', $approvals[0]->status_approval)) }}</span>
                        @if($approvals[0]->catatan)<br><span style="color: #000000; font-weight: normal;">Catatan: {{ $approvals[0]->catatan }}</span>@endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td rowspan="2" colspan="14" style="border: 1px solid #000000; text-align: center; padding: 20px;">
                    Tidak ada data riwayat cuti
                </td>
            </tr>
        @endforelse
    </tbody>
</table>