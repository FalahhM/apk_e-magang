<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Email Pengajuan Magang Diterima</title>
</head>
<body>
  
<h1>Selamat!</h1>

<p>Pengajuan magang anda telah diterima.</p>

<p>Detail Pengajuan:</p>

<ul>
  <li>Nama: {{ $pengajuan->user->name }}</li>
  <li>Nomor Surat: 4SDM/X.{{ $pengajuan->noSuratTerima }}/{{ $bulanRomawi }}/{{ now()->year }}</li>
  <li>Tanggal Diterima: {{ \Carbon\Carbon::parse($pengajuan->cetakTerima_timestamp)->format('d F Y') }}</li>
</ul>

<p>Terima kasih</p>

</body>
</html>