<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
    </style>
</head>
<body>

<div class="title">Rekap Absensi Mahasiswa</div>

<p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
<p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
<p><strong>Jurusan:</strong> {{ $mahasiswa->jurusan }}</p>
<p><strong>Asal Kampus:</strong> {{ $mahasiswa->kampus->name ?? '-' }}</p>

<table>
    <thead>
        <tr>
            <th>Status</th>
            <th>Jumlah Hari</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>Hadir</td><td>{{ $hadir }}</td></tr>
        <tr><td>Izin</td><td>{{ $izin }}</td></tr>
        <tr><td>Sakit</td><td>{{ $sakit }}</td></tr>
        <tr><td>Alfa</td><td>{{ $alfa }}</td></tr>
    </tbody>
</table>

</body>
</html>
