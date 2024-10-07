<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Email Pengajuan Magang Ditolak</title>
</head>
<body>
  
<h1>Maaf!</h1>

<p>Pengajuan magang anda telah ditolak.</p>

<p>Detail Pengajuan:</p>

<ul>
  <li>Nama: {{ $pengajuan->user->name }}</li>
  <li>Nomor Surat: 4SDM/X.{{ $pengajuan->noSuratTolak }}/{{ $bulanRomawi }}/{{ now()->year }}</li>
  <li>Tanggal Ditolak: {{ \Carbon\Carbon::parse($pengajuan->cetakTolak_timestamp)->format('d F Y') }}</li>
  <li>Alasan Penolakan: {{ $pengajuan->alasanTolak }}</li>
</ul>

<p>Terima kasih atas pengajuan anda. Kami berharap anda dapat mencoba lagi di lain waktu.</p>

</body>
</html>
