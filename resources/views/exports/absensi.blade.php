<table>
    <thead>
        {{-- Informasi Pengguna --}}
        <tr>
            <th colspan="10" style="text-align:left; font-size: 16px; color: #15803d; font-weight: bold;">
                List History Absensi
            </th>
        </tr>
        <tr>
            <th colspan="10" style="text-align:left;">
                Nama Pegawai: <strong>{{ $user->name ?? '-' }}</strong> | Periode: <strong>{{ $title }}</strong>
            </th>
        </tr>
        <tr>
            <th colspan="10" style="text-align:left;">
                Unit: {{ $user->unitKerja->nama ?? '-' }} | Jabatan: {{ $user->kategorijabatan->nama ?? '-' }}
            </th>
        </tr>

        <tr><td colspan="10"></td></tr> {{-- Spacer --}}

        {{-- Header Tabel Utama --}}
        <tr style="background-color:#86efac;">
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Hari</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Tanggal</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Masuk</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Keluar</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Kerja</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Lembur</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Rencana Kerja</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Laporan Kerja</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Ket. Lembur</th>
            <th style="border: 1px solid #bbf7d0; color: #064e3b; font-weight: bold;">Feedback</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($items as $item)
            @php
                // Logika warna baris menggunakan Hex Code untuk kompatibilitas Excel
                $rowStyle = 'background-color: #ffffff;';
                if ($item['is_holiday']) {
                    $rowStyle = 'background-color: #fee2e2;'; // Merah muda
                } elseif ($item['is_lembur']) {
                    $rowStyle = 'background-color: #fef9c3;'; // Kuning
                } elseif ($item['is_dinas']) {
                    $rowStyle = 'background-color: #dbeafe;'; // Biru
                } elseif ($item['late']) {
                    $rowStyle = 'background-color: #fca5a5;'; // Merah terang
                } elseif ($loop->even) {
                    $rowStyle = 'background-color: #f0fdf4;'; // Hijau muda (zebra)
                }

                // Fungsi pembersih br menjadi pemisah pipa |
                $clean = function($text) {
                    $replaced = str_replace(['<br>', '<br/>', '<br />'], ' | ', $text ?? '-');
                    return strip_tags($replaced);
                };
            @endphp

            <tr style="{{ $rowStyle }}">
                <td style="text-align: center; border: 1px solid #d1fae5;">{{ $item['hari'] }}</td>
                <td style="text-align: center; border: 1px solid #d1fae5;">{{ $item['tanggal'] }}</td>
                <td style="text-align: center; border: 1px solid #d1fae5;">{{ $clean($item['real_masuk']) }}</td>
                <td style="text-align: center; border: 1px solid #d1fae5;">{{ $clean($item['real_selesai']) }}</td>
                <td style="text-align: center; border: 1px solid #d1fae5; font-weight: bold;">{{ $clean($item['jam_kerja']) }}</td>
                <td style="text-align: center; border: 1px solid #d1fae5;">{{ $clean($item['jam_lembur']) }}</td>
                <td style="text-align: left; border: 1px solid #d1fae5;">{{ $clean($item['rencana_kerja']) }}</td>
                <td style="text-align: left; border: 1px solid #d1fae5;">
                    @if ($item['nama_shift'] == 'L')
                        <span style="color: #dc2626; font-style: italic;">Libur</span>
                    @else
                        {{ $clean($item['laporan_kerja']) }}
                    @endif
                </td>
                <td style="text-align: left; border: 1px solid #d1fae5;">{{ $clean($item['laporan_lembur']) }}</td>
                <td style="text-align: left; border: 1px solid #d1fae5; color: #6b7280;">{{ $clean($item['feedback']) }}</td>
            </tr>
        @endforeach
            <tr><td colspan="10"></td></tr> {{-- Spacer --}}
            <tr>
                <th colspan="10" style="text-align:left; font-size: 16px; color: #15803d; font-weight: bold;">
                    Rekap Absensi
                </th>
            </tr>
            <tr>
                @php
                    $total = collect($items)->filter(function ($item) {
                        return !$item['is_holiday'] && !$item['is_dinas'] && $item['id'];
                    })->count();
                    $shift = collect($items)->filter(function ($item) {
                        return !$item['is_holiday'] && !$item['is_dinas'] && !$item['is_lembur'] && $item['id'];
                    })->count();
                    $lembur = collect($items)->filter(function ($item) {
                        return !$item['is_holiday'] && !$item['is_dinas'] && $item['is_lembur'] && $item['id'];
                    })->count();
                    $dinas = collect($items)->filter(function ($item) {
                        return $item['is_dinas'] && $item['id'];
                    })->count();
                    $onTime = collect($items)->filter(function ($item) {
                        return !$item['is_holiday'] && !$item['is_dinas'] && !$item['late'] && $item['id'];
                    })->count();
                    $terlambat = collect($items)->filter(function ($item) {
                        return !$item['is_holiday'] && !$item['is_dinas'] && $item['late'] && $item['id'];
                    })->count();
                @endphp
                <th colspan="2" style="text-align:left;">Total</th>
                <th colspan="1" style="text-align:left;">{{ $total }} ({{ (new NumberFormatter('ID', NumberFormatter::SPELLOUT))->format($total) }})</th>
                <th colspan="2" style="text-align:left;">On-Time</th>
                <th colspan="1" style="text-align:left;">{{ $onTime }} ({{ (new NumberFormatter('ID', NumberFormatter::SPELLOUT))->format($onTime) }})</th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:left;">Shift</th>
                <th colspan="1" style="text-align:left;">{{ $shift }} ({{ (new NumberFormatter('ID', NumberFormatter::SPELLOUT))->format($shift) }})</th>
                <th colspan="2" style="text-align:left;">Terlambat</th>
                <th colspan="1" style="text-align:left;">{{ $terlambat }} ({{ (new NumberFormatter('ID', NumberFormatter::SPELLOUT))->format($terlambat) }})</th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:left;">Lembur</th>
                <th colspan="1" style="text-align:left;">{{ $lembur }} ({{ (new NumberFormatter('ID', NumberFormatter::SPELLOUT))->format($lembur) }})</th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:left;">Dinas</th>
                <th colspan="1" style="text-align:left;">{{ $dinas }} ({{ (new NumberFormatter('ID', NumberFormatter::SPELLOUT))->format($dinas) }})</th>
            </tr>
    </tbody>
</table>