<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        /* 1. Pengaturan Halaman */
        @page {
            size: A4 landscape;
            margin: 1.2cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5px;
            line-height: 1.4;
            color: #374151;
            margin: 0;
        }

        /* 2. Header Laporan sesuai Dashboard */
        .header-box {
            border-bottom: 2px solid #86efac;
            /* green-300 */
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #15803d;
            /* green-700 */
            margin: 0;
        }

        /* 3. Desain Tabel Modern Green Theme */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #e5e7eb;
        }

        th {
            background-color: #86efac;
            /* green-300 sesuai permintaan */
            color: #064e3b;
            /* green-900 */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5px;
            padding: 10px 4px;
            border: 1px solid #bbf7d0;
            text-align: center;
        }

        td {
            padding: 7px 4px;
            border-bottom: 1px solid #d1fae5;
            word-wrap: break-word;
            vertical-align: top;
        }

        /* 4. Logika Pewarnaan Baris (Sesuai Dashboard Anda) */
        .bg-red-100 {
            background-color: #fee2e2;
        }

        /* Holiday */
        .bg-yellow-100 {
            background-color: #fef9c3;
        }

        /* Lembur */
        .bg-blue-100 {
            background-color: #dbeafe;
        }

        /* Dinas */
        .bg-red-300 {
            background-color: #fca5a5;
        }

        /* Late */
        .bg-green-50 {
            background-color: #f0fdf4;
        }

        /* Even Row */
        .bg-white {
            background-color: #ffffff;
        }

        /* 5. Utility */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
            padding-left: 6px;
        }

        .font-semibold {
            font-weight: bold;
            color: #064e3b;
        }

        .text-red-600 {
            color: #dc2626;
            font-weight: 500;
            font-style: italic;
        }

        .text-gray-500 {
            color: #6b7280;
            font-size: 8px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="header-box">
        <h1 class="title">List History Absensi</h1>
        <div style="font-size: 10px; color: #6b7280; margin-top: 4px;">
            Nama Pegawai: <strong>{{ $user->name }}</strong> &nbsp; | &nbsp; Periode:
            <strong>{{ $title }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35px;">Hari</th>
                <th style="width: 55px;">Tanggal</th>
                <th style="width: 40px;">Masuk</th>
                <th style="width: 40px;">Keluar</th>
                <th style="width: 40px;">Kerja</th>
                <th style="width: 15%;">Rencana Kerja</th>
                <th style="width: 15%;">Laporan Kerja</th>
                <th style="width: 40px;">Lembur</th>
                <th style="width: 14%;">Ket. Lembur</th>
                <th style="width: 15%;">Feedback</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                @php
                    $rowClass = 'bg-white';
                    if ($item['is_holiday']) {
                        $rowClass = 'bg-red-100';
                    } elseif ($item['is_lembur']) {
                        $rowClass = 'bg-yellow-100';
                    } elseif ($item['is_dinas']) {
                        $rowClass = 'bg-blue-100';
                    } elseif ($item['late']) {
                        $rowClass = 'bg-red-300';
                    } elseif ($loop->even) {
                        $rowClass = 'bg-green-50';
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-center font-semibold">{{ $item['hari'] }}</td>
                    <td class="text-center">{{ $item['tanggal'] }}</td>
                    <td class="text-center">{!! $item['real_masuk'] ?? '-' !!}</td>
                    <td class="text-center">{!! $item['real_selesai'] ?? '-' !!}</td>
                    <td class="text-center font-semibold">{!! $item['jam_kerja'] !!}</td>
                    <td class="text-left">{!! $item['rencana_kerja'] !!}</td>
                    <td class="text-left">
                        @if ($item['nama_shift'] == 'L')
                            <span class="text-red-600">Libur</span>
                        @else
                            {!! $item['laporan_kerja'] !!}
                        @endif
                    </td>
                    <td class="text-center">{!! $item['jam_lembur'] !!}</td>
                    <td class="text-left">{!! $item['laporan_lembur'] !!}</td>
                    <td class="text-left text-gray-500">{!! $item['feedback'] !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 30px;">Tidak ada data ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
