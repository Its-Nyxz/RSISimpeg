<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Absensi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .section {
            margin-bottom: 15px;
        }

        .title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <h2>Data Umum</h2>
    <div class="section">
        <p><strong>NIP:</strong> {{ $absen->user->nip ?? '-' }}</p>
        <p><strong>Nama:</strong> {{ $absen->user->name ?? '-' }}</p>
        <p><strong>Jabatan:</strong> {{ $absen->user->kategorijabatan->nama ?? '-' }}</p>
        <p><strong>Tanggal Lahir:</strong> {{ formatDate($absen->user->tanggal_lahir) }}</p>
        <p><strong>Unit:</strong> {{ $absen->user->unitKerja->nama ?? '-' }}</p>
        <p><strong>Pendidikan:</strong> {{ $absen->user->pendidikanUser->deskripsi ?? '-' }}</p>
    </div>

    <h2>Data Absensi</h2>
    <div class="section">
        <p><strong>Tanggal:</strong> {{ $tanggalFormatted }}</p>
        <p><strong>Jam Kerja:</strong> {{ $jamKerjaFormatted }}</p>
        <p><strong>Jam Masuk:</strong> {{ $realMasukFormatted }}</p>
        <p><strong>Jam Keluar:</strong> {{ $realKeluarFormatted }}</p>
        <p><strong>Rencana Kerja:</strong> {{ $absen->deskripsi_in ?? '-' }}</p>
        <p><strong>Laporan Kerja:</strong> {{ $absen->deskripsi_out ?? '-' }}</p>
        <p><strong>Status Lembur:</strong> {{ $isLembur ? 'Ya' : 'Tidak' }}</p>
        @if ($isLembur)
            <p><strong>Durasi Lembur:</strong> {{ $lemburFormatted }}</p>
        @endif
        <p><strong>Feedback:</strong> {{ $absen->feedback ?? '-' }}</p>
    </div>
</body>

</html>
