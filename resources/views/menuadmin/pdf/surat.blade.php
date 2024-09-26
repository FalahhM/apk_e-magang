<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Proses</title>
    <style>
        /* Mengatur margin dan padding halaman */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            margin: 30px;
            line-height: 1.6;
        }

        /* Header Surat */
        .kopsurat1 {
            align-items: center;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kopsurat1 p {
            margin: 0;
            text-align: left;
            font-weight: bold;
            font-size: 11pt;
        }

        img {
            width: 90px; /* Tentukan ukuran logo */
            height: auto;
            float: right;
            margin-top: -70px;
        }

        /* Judul Surat */
        .surat2 h2 {
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            font-size: 14pt;
        }

        /* Tabel Informasi Surat */
        .surat2 table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .surat2 table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .surat2 table tr td:nth-child(2) {
            width: 15%;
            font-weight: bold;
        }

        hr {
            height: 3px;
            color: black; 
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Halaman */
        .hal {
            margin-bottom: 5px;
        }

        .hal p {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Badan Surat */
        .badan p {
            text-align: justify;
            text-indent: 50px;
            margin-bottom: 40px;
        }

        /* Tanda Tangan */
        .ttd {
            margin-top: 60px;
            margin-left: 450px;
        }
        
        .ttd table{
            text-align: center;
        }

    </style>
</head>
<body>
    
<div class="kopsurat1">
    <p>PT. PERKEBUNAN NUSANTARA IV <br> REGIONAL IV</p>
    <img src="gambar/logoptpn.png" alt="">
</div>

<div class="surat2">
    <h2>MEMORANDUM</h2>
    <table>
        <tr>
            <td></td>
            <td>Kepada</td>
            <td>: Yth. SEVP <i>Business Support</i></td>
        </tr>
        <tr>
            <td></td>
            <td>Dari</td>
            <td>: Bagian SDM & Sistem Manajemen</td>
        </tr>
        <tr>
            <td></td>
            <td>Nomor</td>
            <td>: 4SDM/4SBS/M-<span style="color: white">bla</span>/{{ $bulanRomawi }}/{{ now()->year }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Lampiran</td>
            <td>: 1 Set</td>
        </tr>
        <tr>
            <td></td>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($pengajuan->cetak_timestamp)->format('d F Y') }}</td>
        </tr>
    </table>
</div>

<hr>

<div class="hal">
    <p><b>Hal : {{ $pengajuan->perihal }}</b></p>
</div>

<div class="badan">
    <p>
        Sehubungan surat dari {{ $pengajuan->user->name }} Nomor : {{ $pengajuan->no_surat }} Hal : {{ $pengajuan->perihal }}, dengan ini mohon izin dan
        persetujuan Bapak untuk dapat melaksanakan kegiatan tersebut mulai tanggal <b>{{ \Carbon\Carbon::parse($pengajuan->mulai_tanggal)->format('d F Y') }}</b> s.d <b>{{ \Carbon\Carbon::parse($pengajuan->sampai_tanggal)->format('d F Y') }}</b> sebanyak {{ $jumlahOrang }} orang,
        dengan rincian terlampir. <br><br>
        Demikian disampaikan atas izin dan persetujuan Bapak, diucapkan terima kasih
    </p>
</div>

<div class="ttd">
    <table>
        <tr>
            <td>Bagian SDM & Sistem Manajemen</td>
        </tr>
        <tr>
            <td><br><br><br><br><br><br></td>
        </tr>
        <tr>
            <td><u><b>{{ $pengajuan->nama_kabag }}</b></u></td>
        </tr>
        <tr>
            <td>Kepala Bagian</td>
        </tr>
    </table>
</div>
<div class="pdfkampus">

</div>

</body>
</html>
