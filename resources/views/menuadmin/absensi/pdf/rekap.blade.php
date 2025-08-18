<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi Per Hari</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: center; }
    </style>
</head>
<body>

<div class="title">Rekap Absensi Mahasiswa Per Hari</div>

<p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
<p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
<p><strong>Jurusan:</strong> {{ $mahasiswa->jurusan }}</p>
<p><strong>Asal Kampus:</strong> {{ $mahasiswa->pengajuan->user->name ?? '-' }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($mahasiswa->absensis->sortBy('tanggal') as $absen)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d/m/Y') }}</td>
                <td>{{ ucfirst($absen->status) }}</td>
                <td>{{ $absen->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
