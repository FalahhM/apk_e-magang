<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 20px;
            background: white;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .wrapper {
            border: 3px double #000;
            padding: 30px 40px;
        }

        .header, .footer, .ttd, .center {
            text-align: center;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 18px;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        .section {
            margin: 20px 0;
        }

        .section p {
            margin: 6px 0;
        }

        .nilai-tabel {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .nilai-tabel th, .nilai-tabel td {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        .nilai-tabel th {
            background: #f0f0f0;
            text-align: center;
        }

        .identitas {
            margin-top: 25px;
        }

        .identitas p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 40px;
        }

        .ttd {
            margin-top: 60px;
            font-weight: bold;
        }

        .ttd2 {
            margin-top: 60px;
            font-weight: bold;
            text-align: right;
        }

        .kriteria {
            margin-top: 40px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>PT PERKEBUNAN NUSANTARA IV REGIONAL IV</h1><br>
            <h1>PIAGAM PENGHARGAAN</h1>
            <h4>DIBERIKAN KEPADA :</h4>
            <h1 style="margin-top: 20px; text-transform: uppercase;">{{ $mahasiswa->nama_mahasiswa ?? '-' }}</h3>
        </div>

        <div class="section center">
            <p>Telah melaksanakan :</p>
            <p>Program Magang Kerja pada PT Perkebunan Nusantara IV Regional IV</p>
            @php
                $mulai  = \Carbon\Carbon::parse($pengajuan->mulai_tanggal);
                $sampai = \Carbon\Carbon::parse($pengajuan->sampai_tanggal);
                $lama   = $mulai->diffInDays($sampai);
            @endphp

            <p>
                Selama {{ $lama }} hari,
                terhitung mulai tanggal {{ tanggalIndo($pengajuan->mulai_tanggal) }} - {{ tanggalIndo($pengajuan->sampai_tanggal) }}
            </p>
            <p>Dengan hasil : <strong>{{ $mahasiswa->penilaianMagang->predikat ?? '-' }}</strong></p>
        </div>

        <div class="footer">
            <p>{{ $pengajuan->lokasi_magang ?? 'Jambi' }}, {{ tanggalIndo(now()) }}</p>
        </div>

        <div class="ttd">
            <p>{{ $pengajuan->nama_kabag ?? 'Hery Kurniawan' }}</p>
            <p>Kepala Bagian SDM & Sistem Manajemen</p>
        </div>
    </div><br><br>

    <div class="wrapper" style="margin-top: 30px;">
        <div class="identitas">
            <p><strong>Nama Mahasiswa :</strong> {{ $mahasiswa->nama_mahasiswa ?? '-' }}</p>
            <p><strong>Perguruan Tinggi :</strong> {{ $pengajuan->user->name ?? '-' }}</p>
            <p><strong>Pelaksanaan Magang :</strong> {{ tanggalIndo($pengajuan->mulai_tanggal) }} s.d {{ tanggalIndo($pengajuan->sampai_tanggal) }}</p>
        </div><br>
        <table class="nilai-tabel" style="font-size: 16px">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Komponen</th>
                    <th colspan="2">Daftar Nilai</th>
                </tr>
                <tr>
                    <th>Angka</th>
                    <th>Dengan Huruf</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Integritas (Etika, Moral dan Kesungguhan)</td><td>{{ $mahasiswa->penilaianMagang->integritas }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->integritas)) }}</td></tr>
                <tr><td>2</td><td>Ketepatan waktu dalam bekerja</td><td>{{ $mahasiswa->penilaianMagang->ketepatan_waktu }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->ketepatan_waktu)) }}</td></tr>
                <tr><td>3</td><td>Keahlian berdasarkan bidang ilmu</td><td>{{ $mahasiswa->penilaianMagang->keahlian }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->keahlian)) }}</td></tr>
                <tr><td>4</td><td>Kerjasama dalam tim</td><td>{{ $mahasiswa->penilaianMagang->teamwork }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->teamwork)) }}</td></tr>
                <tr><td>5</td><td>Komunikasi</td><td>{{ $mahasiswa->penilaianMagang->komunikasi }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->komunikasi)) }}</td></tr>
                <tr><td>6</td><td>Penggunaan teknologi informasi</td><td>{{ $mahasiswa->penilaianMagang->teknologi }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->teknologi)) }}</td></tr>
                <tr><td>7</td><td>Pengembangan diri</td><td>{{ $mahasiswa->penilaianMagang->pengembangan_diri }}</td><td>{{ ucwords(terbilang($mahasiswa->penilaianMagang->pengembangan_diri)) }}</td></tr>
                <tr>
                    <td colspan="2"><strong>Total nilai pembimbing Perusahaan</strong></td>
                    <td><strong>{{ number_format($mahasiswa->penilaianMagang->total_nilai, 2) }}</strong></td>
                    <td><strong>{{ ucwords(terbilang(round($mahasiswa->penilaianMagang->total_nilai))) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <!-- Kolom Kriteria -->
                <td style="width: 33%; padding: 0; vertical-align: top;">
                    <div style="margin: 6px 8px; font-size: 16px; line-height: 1.4;">
                        <strong>Kriteria Total Nilai Pembimbing Perusahaan :</strong>
                        <div>86 - 100 : Sangat Memuaskan</div>
                        <div>71 - 85 &nbsp;&nbsp;&nbsp;: Memuaskan</div>
                        <div>70 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Cukup Memuaskan</div>
                    </div>
                </td>

                <!-- Kolom Kosong -->
                <td style="width: 34%; padding: 0;"></td>

                <!-- Kolom TTD -->
                <td style="width: 33%; padding: 0; vertical-align: top; padding-top:10;">
                    <div style="margin: 6px 8px; font-size: 16px; text-align: center; line-height: 1.3;">
                        <div>{{ $mahasiswa->lokasi_magang ?? 'Jambi' }}, {{ tanggalIndo(now()) }}</div>
                        <div>Kepala Bagian SDM & Sistem Manajemen</div>
                        <br><br><br>
                        <div><strong>{{ $mahasiswa->nama_kabag ?? 'Hery Kurniawan' }}</strong></div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>
