<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penolakan</title>
    <style>
        /* Mengatur margin dan padding halaman */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9pt;
            margin: 30px;
            line-height: 1.6;
        }

        /* Header Surat */
        .kopsurat1 {
            align-items: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
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

        /* Tabel Informasi Surat */
        .surat2 p{
            text-align: right;
        }

        .surat2 .tabel{
            margin-top: -36px
        }

        .surat2 table {
            width: 50%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .surat2 table td {
            padding: 5px 0;
            vertical-align: top;
        }

        /* Halaman */
        .hal {
            margin-bottom: 5px;
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
    <div class="tanggal">
      <p>Jambi, {{ \Carbon\Carbon::parse($pengajuan->cetakTolak_timestamp)->format('d F Y') }}</p>
    </div>
    <div class="tabel">
      <table>
          <tr>
              <td>Nomor</td>
              <td>: 4SDM/X.{{ $pengajuan->noSuratTolak }}/{{ $bulanRomawi }}/{{ now()->year }}</td>
          </tr>
          <tr>
              <td>Hal</td>
              <td>: Penolakan Pengajuan</td>
          </tr>
      </table>
    </div>
</div>

<div class="hal">
    <p>Kepada Yth.</p>
    <p>{{ $pengajuan->user->name }}</p>
</div>

<div class="badan">
    <p>
        Menindaklanjuti surat pengajuan No: {{ $pengajuan->no_surat }} tanggal {{ \Carbon\Carbon::parse($pengajuan->tanggal_surat)->format('d F Y') }} 
        perihal {{ $pengajuan->perihal }}, kami mohon maaf karena pengajuan tersebut tidak dapat kami setujui.
    </p>
    <p>
        Alasan penolakan pengajuan adalah sebagai berikut:
        <br><br>
        <b>{{ $pengajuan->alasanTolak }}</b>
    </p>
    <p>
        Kami sangat menghargai pengajuan yang telah disampaikan, namun pada saat ini kami tidak dapat memenuhi permohonan tersebut. 
        Kami berharap agar dapat menjalin kerja sama di kesempatan yang akan datang.
    </p>
    <br>
    <p>Atas perhatian dan pengertian yang diberikan, kami ucapkan terima kasih.</p>
</div>

<div class="ttd">
    <table>
        <tr>
            <td>Bagian SDM & Sistem Manajemen</td><br><br><br><br><br><br>
        </tr>
        <tr>
            <td>
                <img src="{{ $qrCodeUri }}"/>
            </td>
        </tr>
        <tr>
            <td><u><b>{{ $pengajuan->nama_kabag }}</b></u></td>
        </tr>
        <tr>
            <td>Kepala Bagian</td>
        </tr>
    </table>
</div>

</body>
</html>
