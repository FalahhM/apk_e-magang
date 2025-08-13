<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Kegiatan Magang</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
        }

        .kop-surat td {
            vertical-align: middle;
            text-align: center;
        }

        .kop-surat td {
            border: none !important;
            padding-bottom: 20px;
        }

        .kop-logo {
            width: 80px;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 15px;
            font-weight: normal;
        }

        .kop-text p {
            margin: 2px 0;
            font-size: 12px;
        }

        /* Judul */
        h2.judul {
            text-align: center;
            font-size: 16px;
            text-transform: uppercase;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        /* Identitas */
        .identitas {
            margin-bottom: 20px;
            font-size: 13px;
        }

        .identitas p {
            margin: 5px 0;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        /* Foto */
        .foto {
            text-align: center;
        }

        img.foto-kegiatan {
            max-width: 100%;
            max-height: 100px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        /* Tanda Tangan */
        .ttd {
            width: 100%;
            margin-top: 60px;
            text-align: right;
            font-size: 13px;
        }

        .ttd p {
            margin: 5px 0;
        }

        .nama-pembimbing {
            margin-top: 50px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('gambar/logoptpn.png') }}" alt="Logo PTPN" style="width:70px;">
            </td>
            <td class="kop-text">
                <h1>PT PERKEBUNAN NUSANTARA IV REGIONAL IV</h1>
                <h2>UNIT USAHA XYZ</h2>
                <p>Jl. Contoh Alamat No. 123, Kota, Provinsi</p>
                <p>Telp: (021) 123456 | Email: info@ptpn.co.id</p>
            </td>
        </tr>
    </table>

    <!-- Judul -->
    <h2 class="judul">Laporan Kegiatan Magang</h2>

    <!-- Identitas Mahasiswa -->
    <div class="identitas">
        <p><strong>Nama Mahasiswa:</strong> {{ $mahasiswa->nama_mahasiswa ?? '-' }}</p>
        <p><strong>NIM:</strong> {{ $mahasiswa->nim ?? '-' }}</p>
    </div>

    <!-- Tabel Kegiatan -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal Kegiatan</th>
                <th style="width: 45%;">Keterangan</th>
                <th style="width: 35%;">Foto Dokumentasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($laporan->tanggal_kegiatan)->format('d/m/Y') }}
                    </td>
                    <td>{{ $laporan->keterangan }}</td>
                    <td class="foto">
                        @if($laporan->foto_dokumentasi && file_exists(public_path('storage/'.$laporan->foto_dokumentasi)))
                            <img class="foto-kegiatan" src="{{ public_path('storage/'.$laporan->foto_dokumentasi) }}" alt="Foto Dokumentasi">
                        @else
                            <span>Tidak ada foto</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="ttd">
        <p>Jambi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Pembimbing Lapangan,</p><br><br><br><br>
        <p style="margin-right: 30px" class="nama-pembimbing">{{ $pembimbing->nama ?? '________________' }}</p>
    </div>

</body>
</html>
